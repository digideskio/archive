<?php

namespace app\tests\functional;

use app\controllers\UsersController;
use app\controllers\WorksController;

use app\models\Users;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

use li3_access\security\Access;

class AccessConfigurationTest extends \lithium\test\Integration {

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
			"email" => "viewer@example.com",
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

	public function testAdminRule() {
		$users = $this->users;

		//Define the access rules for this action
		$rules = array(
			array('rule' => 'isAdminUser', 'message' => 'You are not an admin!', 'redirect' => "/home"),
		);
		
    	/* ... */

		$username = 'admin';

		$request = new Request();
		$check = array('username' => $username);

	    $access = Access::check('rule_based', $check, $request, array('rules' => $rules));

		$this->assertTrue(empty($access));

    	/* ... */

		Auth::clear('default');

		$username = 'editor';

		$request = new Request();
		$check = array('username' => $username);
		
	    $access = Access::check('rule_based', $check, $request, array('rules' => $rules));

		$this->assertEqual($access, $rules[0]);
	}

}

?>
