<?php

namespace app\tests\cases\controllers;

use app\controllers\AlbumsWorksController;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class AlbumsWorksControllerTest extends \lithium\test\Unit {

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
			'controller' => 'albums_works'
		);

		$albums_works = new AlbumsWorksController(array('request' => $this->request));
		
		$response = $albums_works->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $albums_works->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	
	}
}

?>
