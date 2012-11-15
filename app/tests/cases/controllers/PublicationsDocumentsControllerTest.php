<?php

namespace app\tests\cases\controllers;

use app\controllers\PublicationsDocumentsController;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class PublicationsDocumentsControllerTest extends \lithium\test\Unit {

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
			'controller' => 'publications_documents'
		);

		$publications_documents = new PublicationsDocumentsController(array('request' => $this->request));
		
		$response = $publications_documents->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $publications_documents->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	
	}
}

?>
