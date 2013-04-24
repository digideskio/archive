<?php

namespace app\tests\functional;

use app\controllers\UsersController;
use app\controllers\WorksController;

use app\models\Users;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class AccessControlTest extends \lithium\test\Integration {

	public $users = array(
		'admin' => array(
			"username" => "admin",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "admin@example.com",
			"role_id" => '1'
		),
		'editor' => array(
			"username" => "editor",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "editor@example.com",
			"role_id" => '2'
		),
		'viewer' => array(
			"username" => "viewer",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "editor@example.com",
			"role_id" => '3'
		),
	);

	public function setUp() {
	
		$users = $this->users;

		Session::config(array(
			'default' => array('adapter' => 'Php', 'session.name' => 'app')
		));
	
		Auth::clear('default');
	
		$admin = Users::create();
		$admin->save($users['admin']);
	
		$editor = Users::create();
		$editor->save($users['editor']);
	
		$viewer = Users::create();
		$viewer->save($users['viewer']);
	
	}

	public function tearDown() {
	
		Users::all()->delete();
		Auth::clear('default');
	
	}

	public function testWorkAccess() {

		$users = $this->users;

		$access = array('editor', 'viewer');

		foreach ($access as $username) {

			Auth::set('default', $users[$username]);

			//Non-Admins cannot see the locations
			$this->request = new Request();
			$this->request->params = array(
				'controller' => 'works',
				'action' => 'locations'
			);

			$controller = new WorksController(array('request' => $this->request));

			$response = $controller->locations();
			$this->assertEqual($response->headers["Location"], "/works"); 

		}

	}

	public function testUserAccess() {

		$users = $this->users;

		$access = array('editor', 'viewer');

		foreach ($access as $username) {

			Auth::set('default', $users[$username]);

			//Non-Admins cannot see the user list
			$this->request = new Request();
			$this->request->params = array(
				'controller' => 'users'
			);

			$usersController = new UsersController(array('request' => $this->request));

			$response = $usersController->index();
			$this->assertEqual($response->headers["Location"], "/users/view/$username"); 

			//Non-Admins cannot add users
			$response = $usersController->add();
			$this->assertEqual($response->headers["Location"], "/users"); 

			//Non-Admins can view an individual user
			$this->request = new Request();
			$this->request->params = array(
				'controller' => 'users',
				'action' => 'view',
				'username' => 'admin'
			);

			$usersController = new UsersController(array('request' => $this->request));

			$response = $usersController->view();
			$this->assertTrue($response['user']);

			//Non-Admins cannot edit other users
			$this->request = new Request();
			$this->request->params = array(
				'controller' => 'users',
				'action' => 'edit',
				'username' => 'admin'
			);

			$usersController = new UsersController(array('request' => $this->request));

			$response = $usersController->edit();
			$this->assertEqual($response->headers["Location"], "/users/view/admin");

			//Non-Admins cannot delete users
			$this->request = new Request();
			$this->request->params = array(
				'controller' => 'users',
				'action' => 'delete',
				'username' => 'admin'
			);

			$usersController = new UsersController(array('request' => $this->request));
			
			$response = $usersController->delete();
			$this->assertEqual($response->headers["Location"], "/users/view/admin");
		
		}

	}

}

?>
