<?php

namespace app\tests\cases\controllers;

use app\controllers\WorksLinksController;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class WorksLinksControllerTest extends \lithium\test\Unit {

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
	/*	
		Auth::clear('default');
	
		$this->request = new Request();
		$this->request->params = array(
			'controller' => 'works_links'
		);

		$works_links = new WorksLinksController(array('request' => $this->request));
		
		$response = $works_links->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $works_links->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	*/	
	}
}

?>
