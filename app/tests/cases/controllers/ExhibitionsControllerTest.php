<?php

namespace app\tests\cases\controllers;

use app\controllers\ExhibitionsController;

use app\model\Exhibitions;
use app\models\Users;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class ExhibitionsControllerTest extends \lithium\test\Unit {

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
			'controller' => 'architectures'
		);

		$exhibitions = new ExhibitionsController(array('request' => $this->request));
		
		$response = $exhibitions->index();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $exhibitions->search();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $exhibitions->histories();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $exhibitions->venues();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $exhibitions->view();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $exhibitions->history();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $exhibitions->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $exhibitions->edit();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $exhibitions->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	
	}
}

?>
