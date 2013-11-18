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
use app\models\Documents;
use app\models\ArchivesDocuments;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;
use lithium\net\http\Router;

class WorksControllerTest extends \li3_unit\test\ControllerUnit {

	public $controller = 'app\\controllers\WorksController';

	public function setUp() {
		//Create an archive and work pair for testing purposes
		$archive_data = array(
			'name' => 'First Artwork Title',
			'controller' => 'works'
		);
		$archive = Archives::create();
		$archive->save($archive_data);

		$work = Works::create(array(
			'id' => $archive->id,
			'materials' => 'The Materials'
		));

		$success = $work->save();

	}

	public function tearDown() {
	
		Works::all()->delete();
		WorksHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		Links::all()->delete();
		ArchivesLinks::all()->delete();

		Documents::all()->delete();
		ArchivesDocuments::all()->delete();
	
	}

	public function testIndex() {
		$data = $this->call('index');

		$works = $data['works'];
		$total = $data['total'];

		$work = $works->first();

		$this->assertEqual('First Artwork Title', $work->archive->name);
		$this->assertEqual(1, $total);

		$this->assertTrue(isset($data['page']));
		$this->assertTrue(isset($data['limit']));
	
	}

	public function testView() {

		$data = $this->call('view', array(
			'params' => array(
				'slug' => 'First-Artwork-Title'
			)
		));

		$work = $data['work'];

		$this->assertEqual('First Artwork Title', $work->archive->name);

	}

	public function testAdd() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/works/view/{:slug}', array('Works::view'));

		// Test that a work model is created and passed to the view
		$data = $this->call('add', array(
			'params' => array()
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['work']));
		$this->assertTrue(isset($data['link']));
		$this->assertTrue(isset($data['documents']));

		// Test that the action does not save if data is posted but not the
		// required objects
		$data = $this->call('add', array(
			'data' => array('fake' => 'fake')
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['work']));
		$this->assertTrue(isset($data['link']));
		$this->assertTrue(isset($data['documents']));

		// Check that no new records were created
		$this->assertEqual(1, Works::count());
		$this->assertEqual(1, Archives::count());
		$this->assertEqual(0, Links::count());

		// Test that the action does not save and reports errors if we do not
		// post the required data
		$data = $this->call('add', array(
			'data' => array('archive' => array('name' => ''))
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['work']));
		$this->assertTrue(isset($data['link']));
		$this->assertTrue(isset($data['documents']));

		$errors = isset($data['archive']) ? $data['archive']->errors() : '';
		$this->assertTrue(!empty($errors));

		// Check that no new records were created
		$this->assertEqual(1, Works::count());
		$this->assertEqual(1, Archives::count());
		$this->assertEqual(0, Links::count());

		// Test that this action processes and saves the correct data, namely
		// a work, archive, and link model
		$name = 'Artwork New Title';
		$slug = 'Artwork-New-Title';
		$materials = 'Artwork New Materials';
		$url = 'http://example.com/artwork-new';

		$data = $this->call('add', array(
			'data' => array(
				'archive' => compact('name'),
				'work' => compact('materials'),
				'link' => compact('url')
			)
		));

		// Test that the controller returns a redirect response
		$this->assertTrue(!empty($data->status) && $data->status['code'] == 302);
		$this->assertEqual('/works/view/Artwork-New-Title', $data->headers["Location"]);

		// Look up the objects that were saved
		$archive = Archives::find('first', array(
			'conditions' => compact('slug')
		));

		$this->assertTrue(!empty($archive));
		$this->assertEqual($name, $archive->name);
		$this->assertEqual('works', $archive->controller);

		$work = Works::find('first', array(
			'conditions' => array('id' => $archive->id)
		));

		$this->assertTrue(!empty($work));
		$this->assertEqual($materials, $work->materials);

		$link = Links::find('first', array(
			'conditions' => compact('url')
		));

		$this->assertTrue(!empty($link));

		$this->assertEqual($name, $link->title);

	}

	public function testAddWithDocuments() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/works/view/{:slug}', array('Works::view'));

		// Create a new document record which will be used to seed the new work
		Documents::connection()->create("INSERT INTO documents (title, slug, format_id) VALUES ('Document Title', 'Document-Title', '783')");

		// Look up the document to access the id
		$document = Documents::find('first', array(
			'with' => 'Formats'
		));

		// For sanity, test that the insert worked, and that no ArchivesDocuments exist yet
		$this->assertEqual('Document Title', $document->title);
		$this->assertEqual(1, Documents::count());
		$this->assertEqual(0, ArchivesDocuments::count());

		// Test that the action processes and saves the correct data, including
		// a new ArchivesDocument to connect the work and the document
		$data = $this->call('add', array(
			'data' => array(
				'archive' => array(
					'name' => 'Artwork With Doc Title',
				),
				'work' => array(),
				'link' => array(),
				'documents' => array($document->id)
			)
		));

		$archive = Archives::find('first', array(
			'conditions' => array('name' => 'Artwork With Doc Title')
		));

		$archives_document = ArchivesDocuments::first();

		$this->assertEqual($archive->id, $archives_document->archive_id);
		$this->assertEqual($document->id, $archives_document->document_id);

	}

	public function testEdit() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/works/view/{:slug}', array('Works::view'));

		$data = $this->call('edit', array(
			'params' => array(
				'slug' => 'First-Artwork-Title'
			)
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['work']));

		$archive = $data['archive'];
		$work = $data['work'];

		$this->assertEqual('First Artwork Title', $archive->name);

		// Test that the action does not save and reports errors if we do not
		// post the required data
		$data = $this->call('add', array(
			'params' => array(
				'slug' => 'First-Artwork-Title'
			),
			'data' => array(
				'archive' => array('name' => '')
			)
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['work']));

		$errors = isset($data['archive']) ? $data['archive']->errors() : '';
		$this->assertTrue(!empty($errors));

		// Test that the records can be saved with new data
		$name = 'Artwork Update Title';
		$materials = "Artwork Update Materials";

		$data = $this->call('edit', array(
			'params' => array(
				'slug' => 'First-Artwork-Title'
			),
			'data' => array(
				'archive' => compact('name'),
				'work' => compact('materials')
			)
		));

		// Test that the controller returns a redirect response
		$this->assertTrue(!empty($data->status) && $data->status['code'] == 302);
		$this->assertEqual('/works/view/First-Artwork-Title', $data->headers["Location"]);

		// Look up the objects that were saved
		$archive = Archives::find('first', array(
			'conditions' => compact('slug')
		));

		$this->assertTrue(!empty($archive));
		$this->assertEqual($name, $archive->name);
		$this->assertEqual('works', $archive->controller);

		$work = Works::find('first', array(
			'conditions' => array('id' => $archive->id)
		));

		$this->assertTrue(!empty($work));
		$this->assertEqual($materials, $work->materials);
	}

	public function testDelete() {}
	
	public function testRules() {
	
		$ctrl = new WorksController();
		$rules = isset($ctrl->rules) ? $ctrl->rules : NULL;

		$this->assertTrue(!empty($rules));
		$this->assertEqual(13, sizeof($rules));

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

		$this->assertEqual(1, sizeof($rules['publish']));
		$this->assertEqual('allowAnyUser', $rules['publish'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['delete']));
		$this->assertEqual('allowEditorUser', $rules['delete'][0]['rule']);
	}
}

?>
