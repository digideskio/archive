<?php

namespace app\tests\cases\controllers;

use app\controllers\ExhibitionsController;

use app\models\Exhibitions;
use app\models\ExhibitionsHistories;
use app\models\Archives;
use app\models\ArchivesHistories;
use app\models\Links;
use app\models\ArchivesLinks;
use app\models\Documents;
use app\models\ArchivesDocuments;

use lithium\action\Request;
use lithium\net\http\Router;

class ExhibitionsControllerTest extends \li3_unit\test\ControllerUnit {

	public $controller = 'app\\controllers\ExhibitionsController';

	public function setUp() {
		// Create an archive and exhibition pair for testing purposes
		$archive_data = array(
			'title' => 'First Exhibition Title',
			'controller' => 'exhibitions'
		);
		$archive = Archives::create();
		$archive->save($archive_data);
	
		$exhibit = Exhibitions::create();
		$data = array(
			'id' => $archive->id,
			'venue' => 'Exhibition Venue',
		);

		$exhibit->save($data);

	}

	public function tearDown() {
	
		Exhibitions::all()->delete();
		ExhibitionsHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		Links::all()->delete();
		ArchivesLinks::all()->delete();

		Documents::all()->delete();
		ArchivesDocuments::all()->delete();
	
	}

	public function testIndex() {
		$data = $this->call('index');

		$exhibitions = $data['exhibitions'];
		$total = $data['total'];

		$exhibition = $exhibitions->first();

		$this->assertEqual('First Exhibition Title', $exhibition->archive->name);
		$this->assertEqual(1, $total);

		$this->assertTrue(isset($data['page']));
		$this->assertTrue(isset($data['limit']));
	
	}

	public function testView() {

		$data = $this->call('view', array(
			'params' => array(
				'slug' => 'First-Exhibition-Title'
			)
		));

		$exhibition = $data['exhibition'];

		$this->assertEqual('First Exhibition Title', $exhibition->archive->name);

	}

	public function testAdd() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/exhibitions/view/{:slug}', array('Exhibitions::view'));

		// Test that a exhibition model is created and passed to the view
		$data = $this->call('add', array(
			'params' => array()
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['exhibition']));
		$this->assertTrue(isset($data['link']));
		$this->assertTrue(isset($data['documents']));

		// Test that the action does not save if data is posted but not the
		// required objects
		$data = $this->call('add', array(
			'data' => array('fake' => 'fake')
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['exhibition']));
		$this->assertTrue(isset($data['link']));
		$this->assertTrue(isset($data['documents']));

		// Check that no new records were created
		$this->assertEqual(1, Exhibitions::count());
		$this->assertEqual(1, Archives::count());
		$this->assertEqual(0, Links::count());

		// Test that the action does not save and reports errors if we do not
		// post the required data
		$data = $this->call('add', array(
			'data' => array('archive' => array('title' => ''))
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['exhibition']));
		$this->assertTrue(isset($data['link']));
		$this->assertTrue(isset($data['documents']));

		$errors = isset($data['archive']) ? $data['archive']->errors() : '';
		$this->assertTrue(!empty($errors));

		// Check that no new records were created
		$this->assertEqual(1, Exhibitions::count());
		$this->assertEqual(1, Archives::count());
		$this->assertEqual(0, Links::count());

		// Test that this action processes and saves the correct data, namely
		// an exhibition, archive, and link model
		$title = 'Exhibition New Title';
		$slug = 'Exhibition-New-Title-New-Venue';
		$venue = 'New Venue';
		$url = 'http://example.com/exhibit-new-title';

		$data = $this->call('add', array(
			'data' => array(
				'archive' => compact('title'),
				'exhibition' => compact('venue'),
				'link' => compact('url')
			)
		));

		// Test that the controller returns a redirect response
		$this->assertTrue(!empty($data->status) && $data->status['code'] == 302);
		$this->assertEqual("/exhibitions/view/$slug", $data->headers["Location"]);

		// Look up the objects that were saved
		$archive = Archives::find('first', array(
			'conditions' => compact('slug')
		));

		$this->assertTrue(!empty($archive));
		$this->assertEqual($title, $archive->name);
		$this->assertEqual('exhibitions', $archive->controller);

		$exhibit = Exhibitions::find('first', array(
			'conditions' => array('id' => $archive->id)
		));

		$this->assertTrue(!empty($exhibit));
		$this->assertEqual($venue, $exhibit->venue);

		$link = Links::find('first', array(
			'conditions' => compact('url')
		));

		$this->assertTrue(!empty($link));

		$this->assertEqual($title, $link->title);

	}

	public function testAddWithDocuments() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/exhibitions/view/{:slug}', array('Exhibitions::view'));

		// Create a new document record which will be used to seed the new exhibition
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
		// a new ArchivesDocument to connect the exhibition and the document
		$data = $this->call('add', array(
			'data' => array(
				'archive' => array(
					'title' => 'Exhibition With Doc Title',
				),
				'exhibition' => array(),
				'link' => array(),
				'documents' => array($document->id)
			)
		));

		$archive = Archives::find('first', array(
			'conditions' => array('name' => 'Exhibition With Doc Title')
		));

		$archives_document = ArchivesDocuments::first();

		$this->assertEqual($archive->id, $archives_document->archive_id);
		$this->assertEqual($document->id, $archives_document->document_id);

	}
	
	public function testEdit() {

		$slug = 'First-Exhibition-Title';

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/works/view/{:slug}', array('Works::view'));

		$data = $this->call('edit', array(
			'params' => array(
				'slug' => $slug
			)
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['exhibition']));

		$archive = $data['archive'];
		$exhibition = $data['exhibition'];

		$this->assertEqual('First Exhibition Title', $archive->name);

		// Test that the action does not save and reports errors if we do not
		// post the required data
		$data = $this->call('add', array(
			'params' => array(
				'slug' => 'First-Exhibition-Title'
			),
			'data' => array(
				'archive' => array('title' => '')
			)
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['exhibition']));

		$errors = isset($data['archive']) ? $data['archive']->errors() : '';
		$this->assertTrue(!empty($errors));

		// Test that the records can be saved with new data
		$title = 'Exhibition Update Title';
		$venue = 'Exhibition Update Venue';

		$data = $this->call('edit', array(
			'params' => array(
				'slug' => $slug
			),
			'data' => array(
				'archive' => compact('title'),
				'exhibition' => compact('venue')
			)
		));

		// Test that the controller returns a redirect response
		$this->assertTrue(!empty($data->status) && $data->status['code'] == 302);
		$this->assertEqual("/exhibitions/view/$slug", $data->headers["Location"]);

		// Look up the objects that were saved
		$archive = Archives::find('first', array(
			'conditions' => compact('slug')
		));

		$this->assertTrue(!empty($archive));
		$this->assertEqual($title, $archive->name);
		$this->assertEqual('exhibitions', $archive->controller);

		$exhibit = Exhibitions::find('first', array(
			'conditions' => array('id' => $archive->id)
		));

		$this->assertTrue(!empty($exhibit));
		$this->assertEqual($venue, $exhibit->venue);

	}

	public function testDelete() {}
	
	public function testRules() {
	
		$ctrl = new ExhibitionsController();
		$rules = isset($ctrl->rules) ? $ctrl->rules : NULL;

		$this->assertTrue(!empty($rules));

		$this->assertEqual(1, sizeof($rules['index']));
		$this->assertEqual('allowAnyUser', $rules['index'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['search']));
		$this->assertEqual('allowAnyUser', $rules['search'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['histories']));
		$this->assertEqual('allowAnyUser', $rules['histories'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['venues']));
		$this->assertEqual('allowAnyUser', $rules['venues'][0]['rule']);

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
