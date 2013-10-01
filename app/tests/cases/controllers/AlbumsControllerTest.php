<?php

namespace app\tests\cases\controllers;

use app\controllers\AlbumsController;

use app\models\Albums;
use app\models\Archives;
use app\models\ArchivesHistories;
use app\models\Works;
use app\models\WorksHistories;

use lithium\net\http\Router;

class AlbumsControllerTest extends \li3_unit\test\ControllerUnit {

	public $controller = 'app\controllers\AlbumsController';

	public function setUp() {
		$album = Albums::create();
		$data = array(
			'title' => 'First Album Title',
			'remarks' => 'First Album Description'
		);

		$album->save($data);
	
	}

	public function tearDown() {
		Albums::all()->delete();
	
		Works::all()->delete();
		WorksHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

	}

	public function testIndex() {
		$data = $this->call('index');

		$albums = $data['albums'];

		$album = $albums->first();

		$this->assertEqual('First Album Title', $album->title);
	
	}

	public function testView() {

		$data = $this->call('view', array(
			'params' => array(
				'slug' => 'First-Album-Title'
			)
		));

		$album = $data['album'];

		$this->assertEqual('First Album Title', $album->title);
		$this->assertEqual('First Album Description', $album->remarks);
	
	}

	public function testAdd() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/albums/view/{:slug}', array('Albums::view'));

		// Test that an album model is created and passed to the view
		$data = $this->call('add', array(
			'params' => array()
		));

		$this->assertTrue(isset($data['album']));

		// Test that this action processes and saves the correct data, namely
		// an album and archive
		$title = 'Album New Title';
		$slug = 'Album-New-Title';

		$data = $this->call('add', array(
			'data' => array('album' => compact('title'))
		));

		$album = Albums::find('first', array(
			'conditions' => compact('title')
		));

		$this->assertTrue(!empty($album));

		$archive = Archives::find('first', array(
			'conditions' => compact('slug')
		));

		$this->assertTrue(!empty($archive));

	}

	public function testEdit() {

		$data = $this->call('edit', array(
			'params' => array(
				'slug' => 'First-Album-Title'
			)
		));

		$album = $data['album'];

		$this->assertEqual('First Album Title', $album->title);
	
	}

	public function testDelete() {}
	
	public function testRules() {
	
		$ctrl = new AlbumsController();
		$rules = isset($ctrl->rules) ? $ctrl->rules : NULL;

		$this->assertTrue(!empty($rules));

		$this->assertEqual(1, sizeof($rules['index']));
		$this->assertEqual('allowAnyUser', $rules['index'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['view']));
		$this->assertEqual('allowAnyUser', $rules['view'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['add']));
		$this->assertEqual('allowEditorUser', $rules['add'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['edit']));
		$this->assertEqual('allowEditorUser', $rules['edit'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['history']));
		$this->assertEqual('allowAnyUser', $rules['history'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['publish']));
		$this->assertEqual('allowAnyUser', $rules['publish'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['packages']));
		$this->assertEqual('allowAnyUser', $rules['packages'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['package']));
		$this->assertEqual('allowAnyUser', $rules['package'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['delete']));
		$this->assertEqual('allowEditorUser', $rules['delete'][0]['rule']);
	}
}

?>
