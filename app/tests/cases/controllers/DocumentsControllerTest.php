<?php

namespace app\tests\cases\controllers;

use app\controllers\DocumentsController;

use app\model\Documents;
use app\models\Users;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class DocumentsControllerTest extends \lithium\test\Unit {

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
			'controller' => 'documents'
		);

		$documents = new DocumentsController(array('request' => $this->request));
		
		$response = $documents->index();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $documents->search();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $documents->view();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $documents->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $documents->edit();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $documents->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	
	}
}

?>
