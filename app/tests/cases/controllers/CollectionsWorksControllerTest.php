<?php

namespace app\tests\cases\controllers;

use app\controllers\CollectionsWorksController;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class CollectionsWorksControllerTest extends \lithium\test\Unit {

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
			'controller' => 'collections_works'
		);

		$collections_works = new CollectionsWorksController(array('request' => $this->request));
		
		$response = $collections_works->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $collections_works->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	
	}
}

?>
