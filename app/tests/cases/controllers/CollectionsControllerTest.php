<?php

namespace app\tests\cases\controllers;

use app\controllers\CollectionsController;

use app\model\Collections;
use lithium\storage\Session;
use app\models\Users;

use lithium\security\Auth;
use lithium\action\Request;

class CollectionsControllerTest extends \lithium\test\Unit {

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
			'controller' => 'collections'
		);

		$collections = new CollectionsController(array('request' => $this->request));
		
		$response = $collections->index();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $collections->view();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $collections->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $collections->edit();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $collections->history();
		$this->assertEqual($response->headers["Location"], "/login");

		$response = $collections->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	
	}
}

?>
