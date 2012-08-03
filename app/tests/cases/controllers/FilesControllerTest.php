<?php

namespace app\tests\cases\controllers;

use app\controllers\FilesController;

use app\models\Users;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class FilesControllerTest extends \lithium\test\Unit {

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
			'controller' => 'files'
		);

		$files = new FilesController(array('request' => $this->request));
		
		$response = $files->view();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $files->small();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $files->thumb();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $files->download();
		$this->assertEqual($response->headers["Location"], "/login");
	
	}
}

?>
