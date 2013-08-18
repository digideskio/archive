<?php

namespace app\tests\cases\controllers;

use app\controllers\NoticesController;

use app\model\Notices;
use lithium\storage\Session;

use lithium\security\Auth;
use lithium\action\Request;

class NoticesControllerTest extends \lithium\test\Unit {

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
			'controller' => 'notices'
		);

		$notices = new NoticesController(array('request' => $this->request));
		
		$response = $notices->index();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $notices->view();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $notices->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $notices->edit();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $notices->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	*/	
	}
}

?>
