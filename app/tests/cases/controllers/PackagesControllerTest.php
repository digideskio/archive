<?php

namespace app\tests\cases\controllers;

use app\controllers\PackagesController;

use lithium\storage\Session;
use app\models\Users;

use lithium\security\Auth;
use lithium\action\Request;

class PackagesControllerTest extends \lithium\test\Unit {

	public function setUp() {
	
		Session::config(array(
			'default' => array('adapter' => 'Php', 'session.name' => 'app')
		));
	
		Auth::clear('default');
	
	}

	public function tearDown() {}

	public function testIndex() {}
	public function testView() {}
	public function testAdd() {}
	public function testEdit() {}
	public function testDelete() {}

	public function testUnauthorizedAccess() {
	
		$this->request = new Request();
		$this->request->params = array(
			'controller' => 'packages'
		);

		$collections = new PackagesController(array('request' => $this->request));
		
		$response = $collections->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $collections->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	}
}

?>
