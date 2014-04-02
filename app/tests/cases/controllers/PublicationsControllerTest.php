<?php

namespace app\tests\cases\controllers;

use app\controllers\PublicationsController;

use app\models\Publications;
use app\models\PublicationsHistories;
use app\models\Archives;
use app\models\ArchivesHistories;
use app\models\Links;
use app\models\ArchivesLinks;
use app\models\Documents;
use app\models\ArchivesDocuments;

use lithium\action\Request;
use lithium\net\http\Router;

class PublicationsControllerTest extends \li3_unit\test\ControllerUnit {

	public $controller = 'app\\controllers\PublicationsController';

	public function setUp() {
		// Create an archive and publications pair for testing purposes
		$archive_data = array(
			'name' => 'First Publication Title',
			'controller' => 'publications'
		);
		$archive = Archives::create();
		$archive->save($archive_data);

		$pub = Publications::create();
		$data = array(
			'id' => $archive->id,
			'publisher' => 'The Publisher',
		);

		$pub->save($data);

	}

	public function tearDown() {

		Publications::all()->delete();
		PublicationsHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		Links::all()->delete();
		ArchivesLinks::all()->delete();

		Documents::all()->delete();
		ArchivesDocuments::all()->delete();

	}

	public function testIndex() {
		$data = $this->call('index');

		$pubs = $data['publications'];
		$total = $data['total'];

		$pub = $pubs->first();

		$this->assertEqual('First Publication Title', $pub->archive->name);
		$this->assertEqual(1, $total);

		$this->assertTrue(isset($data['page']));
		$this->assertTrue(isset($data['limit']));

	}

	public function testView() {

		$data = $this->call('view', array(
			'params' => array(
				'slug' => 'First-Publication-Title'
			)
		));

		$pub = $data['publication'];

		$this->assertEqual('First Publication Title', $pub->archive->name);

	}

	public function testAdd() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/publications/view/{:slug}', array('Publications::view'));

		// Test that a publication model is created and passed to the view
		$data = $this->call('add', array(
			'params' => array()
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['publication']));
		$this->assertTrue(isset($data['link']));
		$this->assertTrue(isset($data['documents']));

		// Test that the action does not save if data is posted but not the
		// required objects
		$data = $this->call('add', array(
			'data' => array('fake' => 'fake')
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['publication']));
		$this->assertTrue(isset($data['link']));
		$this->assertTrue(isset($data['documents']));

		// Check that no new records were created
		$this->assertEqual(1, Publications::count());
		$this->assertEqual(1, Archives::count());
		$this->assertEqual(0, Links::count());

		// Test that the action does not save and reports errors if we do not
		// post the required data
		$data = $this->call('add', array(
			'data' => array('archive' => array('name' => ''))
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['publication']));
		$this->assertTrue(isset($data['link']));
		$this->assertTrue(isset($data['documents']));

		$errors = isset($data['archive']) ? $data['archive']->errors() : '';
		$this->assertTrue(!empty($errors));

		// Check that no new records were created
		$this->assertEqual(1, Publications::count());
		$this->assertEqual(1, Archives::count());
		$this->assertEqual(0, Links::count());

		// Test that this action processes and saves the correct data, namely
		// a publication, archive, and link model
		$name = 'Publication New Title';
		$slug = 'Publication-New-Title';
		$publisher = 'New Publisher';
		$language = 'French';
		$url = 'http://example.com/publication-new-title';

		$data = $this->call('add', array(
			'data' => array(
				'archive' => compact('name'),
				'publication' => compact('publisher', 'language'),
				'link' => compact('url')
			)
		));

		// Test that the controller returns a redirect response
		$this->assertTrue(!empty($data->status) && $data->status['code'] == 302);
		$this->assertEqual("/publications/view/$slug", $data->headers["Location"]);

		// Look up the objects that were saved
		$archive = Archives::find('first', array(
			'conditions' => compact('slug')
		));

		$this->assertTrue(!empty($archive));
		$this->assertEqual($name, $archive->name);
		$this->assertEqual('publications', $archive->controller);
		$this->assertEqual('fr', $archive->language_code);

		$pub = Publications::find('first', array(
			'conditions' => array('id' => $archive->id)
		));

		$this->assertTrue(!empty($pub));
		$this->assertEqual($publisher, $pub->publisher);

		$link = Links::find('first', array(
			'conditions' => compact('url')
		));

		$this->assertTrue(!empty($link));

		$this->assertEqual($name, $link->title);

	}

	public function testAddWithDocuments() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/publications/view/{:slug}', array('Publications::view'));

		// Create a new document record which will be used to seed the new publication
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
		// a new ArchivesDocument to connect the publication and the document
		$data = $this->call('add', array(
			'data' => array(
				'archive' => array(
					'name' => 'Publication With Doc Title',
				),
				'publication' => array(),
				'link' => array(),
				'documents' => array($document->id)
			)
		));

		$archive = Archives::find('first', array(
			'conditions' => array('name' => 'Publication With Doc Title')
		));

		$archives_document = ArchivesDocuments::first();

		$this->assertEqual($archive->id, $archives_document->archive_id);
		$this->assertEqual($document->id, $archives_document->document_id);

	}

	public function testEdit() {

		$slug = 'First-Publication-Title';

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/publications/view/{:slug}', array('Publications::view'));

		$data = $this->call('edit', array(
			'params' => array(
				'slug' => $slug
			)
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['publication']));

		$archive = $data['archive'];
		$publication = $data['publication'];

		$this->assertEqual('First Publication Title', $archive->name);

		// Test that the action does not save and reports errors if we do not
		// post the required data
		$data = $this->call('add', array(
			'params' => array(
				'slug' => 'First-Publication-Title'
			),
			'data' => array(
				'archive' => array('name' => '')
			)
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['publication']));

		$errors = isset($data['archive']) ? $data['archive']->errors() : '';
		$this->assertTrue(!empty($errors));

		// Test that the records can be saved with new data
		$name = 'Publication Update Title';
		$publisher = 'Publication Update Publisher';
		$language = 'Korean';

		$data = $this->call('edit', array(
			'params' => array(
				'slug' => $slug
			),
			'data' => array(
				'archive' => compact('name'),
				'publication' => compact('publisher', 'language')
			)
		));

		// Test that the controller returns a redirect response
		$this->assertTrue(!empty($data->status) && $data->status['code'] == 302);
		$this->assertEqual("/publications/view/$slug", $data->headers["Location"]);

		// Look up the objects that were saved
		$archive = Archives::find('first', array(
			'conditions' => compact('slug')
		));

		$this->assertTrue(!empty($archive));
		$this->assertEqual($name, $archive->name);
		$this->assertEqual('publications', $archive->controller);
		$this->assertEqual('ko', $archive->language_code);

		$pub = Publications::find('first', array(
			'conditions' => array('id' => $archive->id)
		));

		$this->assertTrue(!empty($pub));
		$this->assertEqual($publisher, $pub->publisher);

	}

	public function testDelete() {}

	public function testRules() {

		$ctrl = new PublicationsController();
		$rules = isset($ctrl->rules) ? $ctrl->rules : NULL;

		$this->assertTrue(!empty($rules));

		$this->assertEqual(1, sizeof($rules['index']));
		$this->assertEqual('allowAnyUser', $rules['index'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['search']));
		$this->assertEqual('allowAnyUser', $rules['search'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['languages']));
		$this->assertEqual('allowAnyUser', $rules['languages'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['subjects']));
		$this->assertEqual('allowAnyUser', $rules['subjects'][0]['rule']);

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
