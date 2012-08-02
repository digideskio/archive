<?php

namespace app\tests\cases\controllers;

use app\controllers\WorksDocumentsController;

use lithium\security\Auth;
use lithium\action\Request;

class WorksDocumentsControllerTest extends \lithium\test\Unit {

	public function setUp() {}

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
			'controller' => 'works_documents'
		);

		$works_documents = new WorksDocumentsController(array('request' => $this->request));
		
		$response = $works_documents->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $works_documents->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	
	}
}

?>
