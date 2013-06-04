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

}

?>
