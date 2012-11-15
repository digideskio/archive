<?php

namespace app\tests\cases\controllers;

use app\controllers\ExhibitionsWorksController;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class ExhibitionsWorksControllerTest extends \lithium\test\Unit {

	public function setUp() {
	
		Session::config(array(
			'default' => array('adapter' => 'Php', 'session.name' => 'app')
		));
	}

	public function tearDown() {}

	public function testIndex() {}
	public function testView() {}
	public function testAdd() {}
	public function testEdit() {}
	public function testDelete() {}

	public function testUnauthorizedAccess() {
	
		Auth::clear('default');
	
		$this->request = new Request();
		$this->request->params = array(
			'controller' => 'exhibitions_works'
		);

		$exhibitions_works = new ExhibitionsWorksController(array('request' => $this->request));
		
		$response = $exhibitions_works->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $exhibitions_works->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	
	}
}

?>
