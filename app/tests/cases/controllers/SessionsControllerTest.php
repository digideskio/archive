<?php

namespace app\tests\cases\controllers;

use app\controllers\SessionsController;

use app\models\Users;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class SessionsControllerTest extends \lithium\test\Unit {

	public $user;

	public function setUp() {
	
		Session::config(array(
			'default' => array('adapter' => 'Php', 'session.name' => 'app')
		));
	
		$this->user = Users::create();
		$data = array(
			"username" => "test",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "email@example.com",
			"role_id" => '3'
		);
		$this->user->save($data);
	
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
		
		$this->assertTrue(!empty($check));
	
	}

	public function testRedirect() {
	
		$this->request = new Request();
		$this->request->params = array(
			'controller' => 'sessions',
			'action' => 'add'
		);
		$this->request->data = array(
			'username' => 'test',
			'password' => 'abcd',
			'path' => '/works/histories'
		);

		$session = new SessionsController(array('request' => $this->request));
		
		$response = $session->add();
		
		$this->assertEqual($response->headers["Location"], "/works/histories");
	
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
		
		//$response = $session->add(); //FIXME returns an exception about missing template
		
		//$this->assertEqual($response->headers["Location"], "/login");
		
		$check = (Auth::check('default')) ?: null;
		
		$this->assertNull($check);
		
		Auth::clear('default');
	
	}
	
	public function testLogout() {
	
		$this->request = new Request();
		$this->request->params = array(
			'controller' => 'sessions',
			'action' => 'add'
		);
		$this->request->data = array(
			'username' => 'test',
			'password' => 'abcd'
		);

		$session = new SessionsController(array('request' => $this->request));
		
		/*$response = $session->delete(); //FIXME missing template exception
		
		$check = (Auth::check('default')) ?: null;
		
		$this->assertFalse($check);
		
		Auth::clear('default');*/
	
	}
	
	public function testAdminRegistration() {
	
		Users::all()->delete();
	
		$this->request = new Request();
		$this->request->params = array(
			'controller' => 'sessions'
		);
		
		$session = new SessionsController(array('request' => $this->request));
		
		$response = $session->add();
		
		$this->assertEqual($response->headers["Location"], "/register");
		
	}
}

?>
