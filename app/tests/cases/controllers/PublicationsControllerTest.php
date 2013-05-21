<?php

namespace app\tests\cases\controllers;

use app\controllers\PublicationsController;

use app\models\Users;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class PublicationsControllerTest extends \lithium\test\Unit {

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
	
		$this->request = new Request();
		$this->request->params = array(
			'controller' => 'publications'
		);

		$publications = new PublicationsController(array('request' => $this->request));
		
		$response = $publications->index();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $publications->search();
		$this->assertEqual($response->headers["Location"], "/login");

		$response = $publications->languages();
		$this->assertEqual($response->headers["Location"], "/login");

		$response = $publications->subjects();
		$this->assertEqual($response->headers["Location"], "/login");

		$response = $publications->search();
		$this->assertEqual($response->headers["Location"], "/login");

		$response = $publications->view();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $publications->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $publications->edit();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $publications->history();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $publications->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	
	}
}

?>
