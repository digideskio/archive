<?php

namespace app\tests\cases\controllers;

use app\controllers\AlbumsController;

use app\models\Albums;
use app\models\AlbumsHistories;
use app\models\Archives;
use app\models\ArchivesHistories;
use app\models\Works;
use app\models\WorksHistories;
use app\models\Components;

use lithium\net\http\Router;

class AlbumsControllerTest extends \li3_unit\test\ControllerUnit {

	public $controller = 'app\controllers\AlbumsController';

	public function setUp() {
		//Create an archive and album pair for testing purposes
		$archive_data = array(
			'title' => 'First Album Title',
			'controller' => 'albums'
		);
		$archive = Archives::create();
		$archive->save($archive_data);

		$album = Albums::create(array(
			'id' => $archive->id,
			'remarks' => 'First Album Description'
		));

		$success = $album->save();
	
	}

	public function tearDown() {
		Albums::all()->delete();
		AlbumsHistories::all()->delete();
	
		Works::all()->delete();
		WorksHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		Components::all()->delete();

	}

	public function testIndex() {
		$data = $this->call('index');

		$albums = $data['albums'];

		$album = $albums->first();

		$this->assertEqual('First Album Title', $album->archive->name);
		$this->assertEqual('First Album Description', $album->remarks);
	
	}

	public function testView() {

		$data = $this->call('view', array(
			'params' => array(
				'slug' => 'First-Album-Title'
			)
		));

		$album = $data['album'];

		$this->assertEqual('First Album Title', $album->archive->name);
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

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['album']));
		$this->assertTrue(isset($data['archives']));

		// Test that the action does not save if data is posted but not the
		// required objects
		$data = $this->call('add', array(
			'data' => array('fake' => 'fake')
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['album']));
		$this->assertTrue(isset($data['archives']));

		// Check that no new albums or archives were created
		$this->assertEqual(1, Albums::count());
		$this->assertEqual(1, Archives::count());

		// Test that the action does not save and reports errors if we do not
		// post the required data
		$data = $this->call('add', array(
			'data' => array('archive' => array('title' => ''))
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['album']));
		$this->assertTrue(isset($data['archives']));

		$errors = $data['archive']->errors();
		$this->assertTrue(!empty($errors));

		// Check that no new albums or archives were created
		$this->assertEqual(1, Albums::count());
		$this->assertEqual(1, Archives::count());

		// Test that this action processes and saves the correct data, namely
		// an album and archive
		$title = 'Album New Title';
		$slug = 'Album-New-Title';
		$remarks = "Album New Description";

		$data = $this->call('add', array(
			'data' => array(
				'archive' => compact('title'),
				'album' => compact('remarks')
			)
		));

		// Test that the controller returns a redirect response
		$this->assertTrue(!empty($data->status) && $data->status['code'] == 302);
		$this->assertEqual('/albums/view/Album-New-Title', $data->headers["Location"]);

		// Look up the objects that were saved
		$archive = Archives::find('first', array(
			'conditions' => compact('slug')
		));

		$this->assertTrue(!empty($archive));

		$album = Albums::find('first', array(
			'conditions' => array('id' => $archive->id)
		));

		$this->assertTrue(!empty($album));

	}

	public function testAddWithArchives() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/albums/view/{:slug}', array('Albums::view'));
		
		// Create a new archive (it could represent an artwork, publication, or something
		// else) which we will use to seed the new album
		$work = Works::create();

		// XXX Create this work with an archive object
		$work_data = array(
			'title' => 'Artwork Title'
		);

		$work->save($work_data);

		// Test that the archive is loaded and passed to the view
		$data = $this->call('add', array(
			'data' => array('archives' => array($work->id))
		));

		$this->assertTrue(isset($data['archives']));

		// Check that the archive loaded matches the id we passed in
		$archives = $data['archives'];
		$found_work_archive = $archives->first();

		$this->assertEqual($work->id, $found_work_archive->id);

		// Test that this action processes and saves the correct data, including
		// a new component representing the artwork we passed in
		$title = 'Album New Title';
		$slug = 'Album-New-Title';

		$data = $this->call('add', array(
			'data' => array(
				'archive' => compact('title'),
				'album' => array(),
				'archives' => array($work->id)
			)
		));

		$album_archive = Archives::find('first', array(
			'conditions' => compact('slug')
		));

		$component = Components::find('first', array(
			'conditions' => array(
				'archive_id1' => $album_archive->id,
				'archive_id2' => $work->id
			)
		));

		$this->assertTrue(!empty($component));
	}

	public function testEdit() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/albums/view/{:slug}', array('Albums::view'));

		// Test that an album is looked up and passed to the view
		$data = $this->call('edit', array(
			'params' => array(
				'slug' => 'First-Album-Title'
			)
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['album']));
		$this->assertTrue(isset($data['archives_documents']));

		$album = $data['album'];

		$this->assertEqual('First Album Title', $album->archive->name);

		// Test that the action does not save and reports errors if we do not
		// post the required data
		$data = $this->call('edit', array(
			'params' => array(
				'slug' => 'First-Album-Title'
			),
			'data' => array(
				'archive' => array('title' => '')
			)
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['album']));

		$errors = $data['archive']->errors();
		$this->assertTrue(!empty($errors));

		// Test that the album and archive can be saved with new data
		$title = 'Album Update Title';
		$remarks = "Album Update Description";

		$data = $this->call('edit', array(
			'params' => array(
				'slug' => 'First-Album-Title'
			),
			'data' => array(
				'archive' => compact('title'),
				'album' => compact('remarks')
			)
		));

		// Test that the controller returns a redirect response
		$this->assertTrue(!empty($data->status) && $data->status['code'] == 302);
		$this->assertEqual('/albums/view/First-Album-Title', $data->headers["Location"]);

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
