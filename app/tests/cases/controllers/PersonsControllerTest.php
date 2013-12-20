<?php

namespace app\tests\cases\controllers;

use app\controllers\PersonsController;

use app\models\Persons;
use app\models\PersonsHistories;
use app\models\Archives;
use app\models\ArchivesHistories;
use app\models\Links;
use app\models\ArchivesLinks;

use lithium\action\Request;
use lithium\net\http\Router;

class PersonsControllerTest extends \li3_unit\test\ControllerUnit {

	public $controller = 'app\\controllers\\PersonsController';

	public function setUp() {
		// Create an archive and person pair for testing purposes
		$archive_data = array(
			'name' => 'First Person',
			'controller' => 'artists'
		);
		$archive = Archives::create();
		$archive->save($archive_data);

		$person = Persons::create(array(
			'id' => $archive->id,
			'given_name' => 'First',
			'family_name' => 'Person',
		));

		$person->save();
	}

	public function tearDown() {
	
		Persons::all()->delete();
		PersonsHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		Links::all()->delete();
		ArchivesLinks::all()->delete();

	}

	public function testIndex() {
		$data = $this->call('index');

		$persons = $data['persons'];
		$total = $data['total'];

		$person = $persons->first();

		$this->assertEqual('First Person', $person->archive->name);
		$this->assertEqual('First', $person->given_name);
		$this->assertEqual('Person', $person->family_name);
		$this->assertEqual(1, $total);
	}

	public function testView() {

		$data = $this->call('view', array(
			'params' => array(
				'slug' => 'First-Person'
			)
		));

		$person = $data['person'];

		$this->assertEqual('First Person', $person->archive->name);
	}

	public function testAdd() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/artists/view/{:slug}', array('Persons::view'));

		// Test that a person model is created and passed to the view
		$data = $this->call('add', array(
			'params' => array()
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['person']));
		$this->assertTrue(isset($data['link']));

		// Test that the action does not save if data is posted but not the
		// required objects
		$data = $this->call('add', array(
			'data' => array('fake' => 'fake')
		));

		// Check that no new records were created
		$this->assertEqual(1, Persons::count());
		$this->assertEqual(1, Archives::count());
		$this->assertEqual(0, Links::count());

		// Test that the action does not save and reports errors if we do not
		// post the required data
		$data = $this->call('add', array(
			'data' => array('archive' => array('name' => ''))
		));

		// Check that no new records were created
		$this->assertEqual(1, Persons::count());
		$this->assertEqual(1, Archives::count());
		$this->assertEqual(0, Links::count());

		$errors = isset($data['archive']) ? $data['archive']->errors() : '';
		$this->assertTrue(!empty($errors));

		// Test that this action processes and saves the correct data, namely
		// a person, archive, aand link model
		$name = 'New Person';
		$classification = 'Artist';
		$slug = 'New-Person';
		$given_name = 'New';
		$family_name = 'Person';
		$url = 'http://example.com/person-new';

		$data = $this->call('add', array(
			'data' => array(
				'archive' => compact('name', 'classification'),
				'person' => compact('given_name', 'family_name'),
				'link' => compact('url'),
			)
		));

		// Test that the controller returns a redirect response
		$this->assertTrue(!empty($data->status) && $data->status['code'] == 302);
		$this->assertEqual('/artists/view/New-Person', $data->headers["Location"]);

		// Look up the objects that were saved
		$archive = Archives::find('first', array(
			'conditions' => compact('slug')
		));

		$this->assertTrue(!empty($archive));
		$this->assertEqual($name, $archive->name);
		$this->assertEqual($classification, $archive->classification);
		$this->assertEqual('artists', $archive->controller);

		$person = Persons::find('first', array(
			'conditions' => array('id' => $archive->id)
		));

		$this->assertTrue(!empty($person));
		$this->assertEqual($given_name, $person->given_name);
		$this->assertEqual($family_name, $person->family_name);

		$link = Links::find('first', array(
			'conditions' => compact('url')
		));

		$this->assertTrue(!empty($link));

		$this->assertEqual($name, $link->title);
	}

	public function testEdit() {

		// Make sure the route that the action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/artists/view/{:slug}', array('Works::view'));

		$data = $this->call('edit', array(
			'params' => array(
				'slug' => 'First-Person'
			)
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['person']));

		$archive = $data['archive'];
		$person = $data['person'];

		$this->assertEqual('First Person', $archive->name);
		$this->assertEqual('First', $person->given_name);

		// Test that the action does not save and reports errors if we do not
		// post the required data
		$data = $this->call('add', array(
			'params' => array(
				'slug' => 'First-Person'
			),
			'data' => array(
				'archive' => array('name' => '')
			)
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['person']));

		$errors = isset($data['archive']) ? $data['archive']->errors() : '';
		$this->assertTrue(!empty($errors));

		// Test that the records can be saved with new data
		$name = 'Updated Person';
		$given_name = "Updated";
		$family_name = "Person";

		$slug = 'First-Person';
		$classification = 'Artist';
		$data = $this->call('edit', array(
			'params' => array(
				'slug' => $slug
			),
			'data' => array(
				'archive' => compact('name', 'classification'),
				'person' => compact('given_name', 'family_name'),
			)
		));

		// Test that the controller returns a redirect response
		$this->assertTrue(!empty($data->status) && $data->status['code'] == 302);
		$this->assertEqual('/artists/view/First-Person', $data->headers["Location"]);

		// Look up the objects that were saved
		$archive = Archives::find('first', array(
			'conditions' => compact('slug')
		));

		$this->assertTrue(!empty($archive));
		$this->assertEqual($name, $archive->name);
		$this->assertEqual($classification, $archive->classification);
		$this->assertEqual('artists', $archive->controller);

		$person = Persons::find('first', array(
			'conditions' => array('id' => $archive->id)
		));

		$this->assertTrue(!empty($person));
		$this->assertEqual($given_name, $person->given_name);
		$this->assertEqual($family_name, $person->family_name);
	}

	public function testRules() {
	
		$ctrl = new PersonsController();
		$rules = isset($ctrl->rules) ? $ctrl->rules : NULL;

		$this->assertTrue(!empty($rules));
		$this->assertEqual(5, sizeof($rules));

		$this->assertEqual(1, sizeof($rules['index']));
		$this->assertEqual('allowAnyUser', $rules['index'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['view']));
		$this->assertEqual('allowAnyUser', $rules['view'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['add']));
		$this->assertEqual('allowEditorUser', $rules['add'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['edit']));
		$this->assertEqual('allowEditorUser', $rules['edit'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['delete']));
		$this->assertEqual('allowEditorUser', $rules['delete'][0]['rule']);
	}

}

?>
