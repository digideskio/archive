<?php

namespace app\tests\cases\controllers;

use app\controllers\UsersController;

use app\models\Users;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;
use lithium\net\http\Router;

class UsersControllerTest extends \li3_unit\test\ControllerUnit {

	public $controller = 'app\\controllers\UsersController';

	public $admin;
	public $admin_data;

	public $editor;
	public $editor_data;

	public function setUp() {
	
		Session::config(array(
			'default' => array('adapter' => 'Php', 'session.name' => 'app')
		));
	
		Auth::clear('default');
	
		$this->admin = Users::create();
		$this->admin_data = array(
			"username" => "admin",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "admin@example.com",
			"role_id" => '1'
		);
		$this->admin->save($this->admin_data);
	
		$this->editor = Users::create();
		$this->editor_data = array(
			"username" => "editor",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "editor@example.com",
			"role_id" => '2'
		);
		$this->editor->save($this->editor_data);
	
		$viewer = Users::create();
		$viewer_data = array(
			"username" => "viewer",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "editor@example.com",
			"role_id" => '3'
		);
		$viewer->save($viewer_data);
	
	}

	public function tearDown() {
	
		Users::all()->delete();
		Auth::clear('default');
	
	}

	public function testIndex() {
		$data = $this->call('index');

		$users = $data['users'];

		$this->assertEqual(3, $users->count());
	}

	public function testView() {
		$data = $this->call('view', array(
			'params' => array(
				'username' => 'admin'
			)
		));

		$user = $data['user'];

		$this->assertEqual('admin', $user->username);
	}

	public function testAdd() {
		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/users/view/{:username}', array('Users::view'));

		// Test that a model is created and passed to the view
		$data = $this->call('add', array(
			'params' => array()
		));

		$this->assertTrue(isset($data['user']));
		$this->assertTrue(isset($data['role_list']));

		// Test that this action processes and saves the correct data
		$username = 'new';
		$name = 'New';
		$password = 'strange';
		$email = 'new@example.com';
		$role_id = 3;

		$data = $this->call('add', array(
			'data' => compact('username', 'name', 'password', 'email', 'role_id')
		));

		// Test that the controller returns a redirect response
		$this->assertTrue(!empty($data->status) && $data->status['code'] == 302);
		$this->assertEqual('/users/view/new', $data->headers["Location"]);

		// Look up the objects that were saved
		$user = Users::find('first', array(
			'with' => 'Roles',
			'conditions' => compact('username')
		));

		$this->assertTrue(!empty($user));
		$this->assertEqual($username, $user->username);
		$this->assertEqual('Viewer', $user->role->name);
		$this->assertTrue($user->active == true);
	}

	public function testEdit() {
		// Make sure the route that the edit action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/users/view/{:username}', array('Users::view'));

		$data = $this->call('edit', array(
			'params' => array(
				'username' => 'editor'
			)
		));

		$this->assertTrue(isset($data['user']));
		$this->assertTrue(isset($data['role_list']));

		// Set the user who will be performing the action
		Auth::set('default', $this->editor_data);

		// Test that only allowed records can be saved with new data
		$username = 'updated'; // Not allowed to be changed
		$name = 'Updated Name';
		$email = 'updated@example.com';
		$password = 'updated';
		$role_id = '1'; // Not allowed to be changed

		$data = $this->call('edit', array(
			'params' => array('username' => 'editor'),
			'data' => compact('username', 'name', 'email', 'password', 'role_id')
		));

		// Test that the controller returns a redirect response
		$this->assertTrue(!empty($data->status) && $data->status['code'] == 302);
		$this->assertEqual('/users/view/editor', $data->headers["Location"]);

		// Look up the objects that were saved
		$user = Users::find('first', array(
			'with' => 'Roles',
			'conditions' => array('username' => 'editor')
		));

		$this->assertTrue(!empty($user));
		$this->assertEqual('editor', $user->username);
		$this->assertEqual('Editor', $user->role->name);
		$this->assertEqual($name, $user->name);
		$this->assertEqual($email, $user->email);

		// Set the user who will be performing the action
		Auth::set('default', $this->admin_data);

		// Test that admins can modify user roles
		$username = 'editor';
		$role_id = '1';

		$data = $this->call('edit', array(
			'params' => compact('username'),
			'data' => compact('username', 'name', 'email', 'password', 'role_id')
		));

		// Look up the objects that were saved
		$user = Users::find('first', array(
			'with' => 'Roles',
			'conditions' => array('username' => 'editor')
		));

		$this->assertEqual('Admin', $user->role->name);

	}

	public function testDelete() {
		// Make sure the routes are connected
		Router::connect('/users', array('Users::index'));

		$username = 'editor';
		$data = $this->call('delete', array(
			'params' => array('username' => 'editor'),
			'env' => array('REQUEST_METHOD' => 'POST'), // Force the request to be a POST
		));

		// Test that the controller returns a redirect response
		$this->assertTrue(!empty($data->status) && $data->status['code'] == 302);
		$this->assertEqual('/users', $data->headers["Location"]);

		// Look up the user
		$user = Users::find('first', array(
			'conditions' => compact('username')
		));

		// Check that the user has been de-activated
		$this->assertEqual(0, $user->active);

	}

	public function testRegister() {
		// Make sure the routes are connected
		Router::connect('/login', 'Sessions::add');
		Router::connect('/register', 'Users::register');
	
		// Test that the register route is not accessible since the system
		// has users.
		Auth::set('default', $this->editor_data);

		$data = $this->call('register');
		$this->assertEqual('/login', $data->headers["Location"]);

		// Delete all users, then test that the register page is accessible
		Auth::clear('default');
		Users::all()->delete();

		$data = $this->call('register');
		$this->assertEqual(200, $data->status["code"]);

		// Test the registration process
		$data = $this->call('register', array(
			'data' => $this->admin_data
		));
		$this->assertEqual('/home', $data->headers["Location"]);

		$data = $this->call('view', array(
			'params' => array(
				'username' => 'admin'
			)
		));

		$user = $data['user'];

		$this->assertEqual('admin', $user->username);

	}

	public function testRules() {

		$ctrl = new UsersController();
		$rules = isset($ctrl->rules) ? $ctrl->rules : NULL;

		$this->assertTrue(!empty($rules));

		$this->assertEqual(1, sizeof($rules['index']));
		$this->assertEqual('allowAdminUser', $rules['index'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['view']));
		$this->assertEqual('allowAnyUser', $rules['view'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['add']));
		$this->assertEqual('allowAdminUser', $rules['add'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['edit']));
		$this->assertEqual('allowAdminOrUserRequestingSelf', $rules['edit'][0]['rule']);

		$this->assertEqual(2, sizeof($rules['delete']));
		$this->assertEqual('allowAdminUser', $rules['delete'][0]['rule']);
		$this->assertEqual('denyUserRequestingSelf', $rules['delete'][1]['rule']);
	
	}

}

?>
