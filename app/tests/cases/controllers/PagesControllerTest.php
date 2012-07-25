<?php

namespace app\tests\cases\controllers;

use app\controllers\PagesController;
use app\models\Users;

use lithium\security\Auth;
use lithium\action\Request;

class PagesControllerTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}

	public function testIndex() {}
	public function testView() {}
	public function testAdd() {}
	public function testEdit() {}
	public function testDelete() {}
	
	public function testUnauthorizedView() {
	
		$this->request = new Request();
		$this->request->url = '/home';
		$this->request->params = array(
			'controller' => 'pages'
		);

		$pages = new PagesController(array('request' => $this->request));
		
		$response = $pages->view();
		
		$this->assertEqual($response->headers["Location"], "/login");
	
	}
}

?>
