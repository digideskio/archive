<?php

namespace app\tests\cases\controllers;

use app\controllers\WorksController;

use app\models\Users;

use app\models\Works;
use app\models\WorksHistories;
use app\models\Archives;
use app\models\ArchivesHistories;
use app\models\Links;
use app\models\ArchivesLinks;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;
use lithium\net\http\Router;

class WorksControllerTest extends \li3_unit\test\ControllerUnit {

	public $controller = 'app\\controllers\WorksController';

	public function setUp() {
	
		$work = Works::create();
		$data = array(
			'title' => 'Artwork Title',
		);

		$work->save($data);

	}

	public function tearDown() {
	
		Works::all()->delete();
		WorksHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		Links::all()->delete();
		ArchivesLinks::all()->delete();
	
	}

	public function testIndex() {
		$data = $this->call('index');

		$works = $data['works'];
		$total = $data['total'];

		$work = $works->first();

		$this->assertEqual('Artwork Title', $work->title);
		$this->assertEqual(1, $total);

		$this->assertTrue(isset($data['page']));
		$this->assertTrue(isset($data['limit']));
	
	}

	public function testView() {

		$data = $this->call('view', array(
			'params' => array(
				'slug' => 'Artwork-Title'
			)
		));

		$work = $data['work'];

		$this->assertEqual('Artwork Title', $work->title);

	}

	public function testAdd() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/works/view/{:slug}', array('Works::view'));

		// Test that a work model is created and passed to the view
		$data = $this->call('add', array(
			'params' => array()
		));

		$this->assertTrue(isset($data['work']));

		// Test that this action processes and saves the correct data, namely
		// a work, archive, and link model
		$title = 'Artwork New Title';
		$slug = 'Artwork-New-Title';
		$url = 'http://example.com/artwork-new-title';

		$data = $this->call('add', array(
			'data' => compact('title', 'url')
		));

		$work = Works::find('first', array(
			'conditions' => compact('title')
		));

		$this->assertTrue(!empty($work));

		$archive = Archives::find('first', array(
			'conditions' => compact('slug')
		));

		$this->assertTrue(!empty($archive));

		$link = Links::find('first', array(
			'conditions' => compact('url')
		));

		$this->assertTrue(!empty($link));

		$this->assertEqual($title, $link->title);

	}

	public function testEdit() {

		$data = $this->call('edit', array(
			'params' => array(
				'slug' => 'Artwork-Title'
			)
		));

		$work = $data['work'];

		$this->assertEqual('Artwork Title', $work->title);

	}

	public function testDelete() {}
	
	public function testRules() {
	
		$ctrl = new WorksController();
		$rules = isset($ctrl->rules) ? $ctrl->rules : NULL;

		$this->assertTrue(!empty($rules));

		$this->assertEqual(1, sizeof($rules['index']));
		$this->assertEqual('allowAnyUser', $rules['index'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['search']));
		$this->assertEqual('allowAnyUser', $rules['search'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['artists']));
		$this->assertEqual('allowAnyUser', $rules['artists'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['classifications']));
		$this->assertEqual('allowAnyUser', $rules['classifications'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['locations']));
		$this->assertEqual('allowAdminUser', $rules['locations'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['histories']));
		$this->assertEqual('allowAnyUser', $rules['histories'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['view']));
		$this->assertEqual('allowAnyUser', $rules['view'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['add']));
		$this->assertEqual('allowEditorUser', $rules['add'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['edit']));
		$this->assertEqual('allowEditorUser', $rules['edit'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['attachments']));
		$this->assertEqual('allowEditorUser', $rules['attachments'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['history']));
		$this->assertEqual('allowAnyUser', $rules['history'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['delete']));
		$this->assertEqual('allowEditorUser', $rules['delete'][0]['rule']);
	}
}

?>
