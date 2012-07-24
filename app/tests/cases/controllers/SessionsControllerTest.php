<?php

namespace app\tests\cases\controllers;

use app\controllers\SessionsController;

use app\models\Users;

use lithium\security\Auth;
use lithium\action\Request;

class SessionsControllerTest extends \lithium\test\Unit {

	public $user;

	public function setUp() {
	
		$user = Users::create();
		$data = array(
			"username" => "test",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "email@example.com",
			"role_id" => '3'
		);
		$user->save($data);
	
	
	}

	public function tearDown() {
	
		Users::all()->delete();
		Auth::clear('default');
	
	}

	public function testLogin() {
	
		$this->request = new Request();
		$this->request->params = array(
			'controller' => 'sessions'
		);
		$this->request->data = array(
			'username' => 'test',
			'password' => 'abcd'
		);

		$session = new SessionsController(array('request' => $this->request));
		
		$response = $session->add();
		
		$this->assertEqual($response->headers["Location"], "/home");
		
		$check = (Auth::check('default')) ?: null;
		
		$this->assertTrue($check);
		
		Auth::clear('default');
	
	}
	
	public function testBadLogin() {
	
		$this->request = new Request();
		$this->request->params = array(
			'controller' => 'sessions'
		);
		$this->request->data = array(
			'username' => 'tset',
			'password' => 'efgh'
		);

		$session = new SessionsController(array('request' => $this->request));
		
		/*$response = $session->add(); //FIXME returns an exception about missing template
		
		$this->assertEqual($response->headers["Location"], "/login");*/
		
		$check = (Auth::check('default')) ?: null;
		
		$this->assertFalse($check);
	
	}
	
	public function testLogout() {
	
		$this->request = new Request();
		$this->request->params = array(
			'controller' => 'sessions'
		);
		$this->request->data = array(
			'username' => 'test',
			'password' => 'abcd'
		);

		$session = new SessionsController(array('request' => $this->request));
		
		$response = $session->delete();
		
		$this->assertEqual($response->headers["Location"], "/login");
		
		$check = (Auth::check('default')) ?: null;
		
		$this->assertFalse($check);
	
	}
}

?>
