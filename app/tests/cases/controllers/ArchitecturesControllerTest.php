<?php

namespace app\tests\cases\controllers;

use app\controllers\ArchitecturesController;

use app\model\Architectures;
use app\models\Users;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class ArchitecturesControllerTest extends \lithium\test\Unit {

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
			'controller' => 'architectures'
		);

		$architectures = new ArchitecturesController(array('request' => $this->request));
		
		$response = $architectures->index();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $architectures->histories();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $architectures->search();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $architectures->view();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $architectures->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $architectures->edit();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $architectures->history();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $architectures->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	*/	
	}
}

?>
