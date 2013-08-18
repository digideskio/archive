<?php

namespace app\tests\cases\controllers;

use app\controllers\LinksController;

use lithium\model\Links;
use lithium\storage\Session;

use lithium\security\Auth;
use lithium\action\Request;
class LinksControllerTest extends \lithium\test\Unit {

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
	/*	
		$this->request = new Request();
		$this->request->params = array(
			'controller' => 'links'
		);

		$links = new LinksController(array('request' => $this->request));
		
		$response = $links->index();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $links->view();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $links->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $links->edit();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $links->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	*/	
	}
}

?>
