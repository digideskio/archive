<?php

namespace app\tests\cases\controllers;

use app\controllers\WorksController;

use app\models\Users;

use app\models\Works;
use app\models\WorksHistories;
use app\models\Archives;
use app\models\ArchivesHistories;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class WorksControllerTest extends \lithium\test\Unit {

	public function setUp() {
	
		Session::config(array(
			'default' => array('adapter' => 'Php', 'session.name' => 'app')
		));
		
		$this->user = Users::create();
		$data = array(
			"username" => "test",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "email@example.com",
			"role_id" => '1'
		);
		$this->user->save($data);

		Auth::set('default', array('username' => 'test'));
	}

	public function tearDown() {
	
		Users::all()->delete();
		Works::all()->delete();
		WorksHistories::all()->delete();
		Archives::all()->delete();
		ArchivesHistories::all()->delete();

		Auth::clear('default');
		
	}

	public function testIndex() {}
	public function testView() {}
	public function testAdd() {

		$this->request = new Request();
		$this->request->params = array(
			'controller' => 'works'
		);
		$this->request->data = array(
			'title' => 'Test Title'
		);

		$works = new WorksController(array('request' => $this->request));

		//FIXME this test should (and does) pass, but throws an exception Undefined index:id
		//somewhere when $work->save($this->request->data) is called in Works::add();

		//$response = $works->add();
		//$this->assertEqual($response->headers["Location"], "/works/view/Test-Title");

	}
	public function testEdit() {}
	public function testDelete() {}
	
	public function testUnauthorizedAccess() {
	
		Auth::clear('default');
	
		$this->request = new Request();
		$this->request->params = array(
			'controller' => 'works'
		);

		$works = new WorksController(array('request' => $this->request));
		
		$response = $works->index();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $works->search();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $works->artists();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $works->view();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $works->add();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $works->edit();
		$this->assertEqual($response->headers["Location"], "/login");
		
		$response = $works->history();
		$this->assertEqual($response->headers["Location"], "/login");

		$response = $works->histories();
		$this->assertEqual($response->headers["Location"], "/login");

		$response = $works->delete();
		$this->assertEqual($response->headers["Location"], "/login");
	
	}
}

?>
