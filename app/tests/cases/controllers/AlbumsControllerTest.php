<?php

namespace app\tests\cases\controllers;

use app\controllers\AlbumsController;

use app\model\Albums;
use lithium\storage\Session;
use app\models\Users;

use lithium\security\Auth;
use lithium\action\Request;

class AlbumsControllerTest extends \lithium\test\Unit {

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
			'controller' => 'albums'
		);

		$albums = new AlbumsController(array('request' => $this->request));
		
		$response = $albums->index();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $albums->view();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $albums->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $albums->edit();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $albums->history();
		$this->assertEqual($response->headers["Location"], "/login");

		$response = $albums->publish();
		$this->assertEqual($response->headers["Location"], "/login");

		$response = $albums->packages();
		$this->assertEqual($response->headers["Location"], "/login");

		$response = $albums->package();
		$this->assertEqual($response->headers["Location"], "/login");

		$response = $albums->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	
	}
}

?>
