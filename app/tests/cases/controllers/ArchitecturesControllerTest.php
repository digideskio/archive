<?php

namespace app\tests\cases\controllers;

use app\controllers\ArchitecturesController;

use app\models\Architectures;
use app\models\ArchitecturesHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

use lithium\net\http\Router;

class ArchitecturesControllerTest extends \li3_unit\test\ControllerUnit {

	public $controller = 'app\controllers\ArchitecturesController';

	public function setUp() {
		//Create an archive and architecture pair for testing purposes
		$archive_data = array(
			'name' => 'First Architecture Title',
			'controller' => 'architectures'
		);
		$archive = Archives::create();
		$archive->save($archive_data);

		$arch = Architectures::create(array(
			'id' => $archive->id,
			'architect' => 'The Architect'
		));

		$success = $arch->save();

	}

	public function tearDown() {
		Architectures::all()->delete();
		ArchitecturesHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

	}

	public function testIndex() {
		$data = $this->call('index');

		$arches = $data['architectures'];

		$arch = $arches->first();

		$this->assertEqual('First Architecture Title', $arch->archive->name);
		$this->assertEqual('The Architect', $arch->architect);

	}

	public function testView() {

		$data = $this->call('view', array(
			'params' => array(
				'slug' => 'First-Architecture-Title'
			)
		));

		$arch = $data['architecture'];

		$this->assertEqual('First Architecture Title', $arch->archive->name);
		$this->assertEqual('The Architect', $arch->architect);

	}

	public function testAdd() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/architectures/view/{:slug}', array('Architectures::view'));

		// Test that an architecture model is created and passed to the view
		$data = $this->call('add', array(
			'params' => array()
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['architecture']));

		// Test that the action does not save if data is posted but not the
		// required objects
		$data = $this->call('add', array(
			'data' => array('fake' => 'fake')
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['architecture']));

		// Check that no new architectures or archives were created
		$this->assertEqual(1, Architectures::count());
		$this->assertEqual(1, Archives::count());

		// Test that the action does not save and reports errors if we do not
		// post the required data
		$data = $this->call('add', array(
			'data' => array('archive' => array('name' => ''))
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['architecture']));

		$errors = $data['archive']->errors();
		$this->assertTrue(!empty($errors));

		// Check that no new architectures or archives were created
		$this->assertEqual(1, Architectures::count());
		$this->assertEqual(1, Archives::count());

		// Test that this action processes and saves the correct data, namely
		// an architecture and archive
		$name = 'Architecture New Title';
		$slug = 'Architecture-New-Title';
		$architect = "That Architect";

		$data = $this->call('add', array(
			'data' => array(
				'archive' => compact('name'),
				'architecture' => compact('architect')
			)
		));

		// Test that the controller returns a redirect response
		$this->assertTrue(!empty($data->status) && $data->status['code'] == 302);
		$this->assertEqual('/architectures/view/Architecture-New-Title', $data->headers["Location"]);

		// Look up the objects that were saved
		$archive = Archives::find('first', array(
			'conditions' => compact('slug')
		));

		$this->assertTrue(!empty($archive));
		$this->assertEqual('architectures', $archive->controller);

		$architecture = Architectures::find('first', array(
			'conditions' => array('id' => $archive->id)
		));

		$this->assertTrue(!empty($architecture));

	}

	public function testEdit() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/architectures/view/{:slug}', array('Architectures::view'));

		$slug = 'First-Architecture-Title';

		// Test that an architecture is looked up and passed to the view
		$data = $this->call('edit', array(
			'params' => array(
				'slug' => $slug
			)
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['architecture']));
		$this->assertTrue(isset($data['archives_documents']));

		$architecture = $data['architecture'];

		$this->assertEqual('First Architecture Title', $architecture->archive->name);

		// Test that the action does not save and reports errors if we do not
		// post the required data
		$data = $this->call('edit', array(
			'params' => array(
				'slug' => $slug
			),
			'data' => array(
				'archive' => array('name' => '')
			)
		));

		$this->assertTrue(isset($data['archive']));
		$this->assertTrue(isset($data['architecture']));

		$errors = $data['archive']->errors();
		$this->assertTrue(!empty($errors));

		// Test that the architecture and archive can be saved with new data
		$name = 'Architecture Update Title';
		$architect = "The Updated Architect";

		$data = $this->call('edit', array(
			'params' => array(
				'slug' => $slug
			),
			'data' => array(
				'archive' => compact('name'),
				'architecture' => compact('architect')
			)
		));

		// Test that the controller returns a redirect response
		$this->assertTrue(!empty($data->status) && $data->status['code'] == 302);
		$this->assertEqual('/architectures/view/First-Architecture-Title', $data->headers["Location"]);

		// Look up the objects that were saved
		$archive = Archives::find('first', array(
			'conditions' => compact('slug')
		));

		$this->assertTrue(!empty($archive));
		$this->assertEqual('architectures', $archive->controller);
		$this->assertEqual($name, $archive->name);

		$architecture = Architectures::find('first', array(
			'conditions' => array('id' => $archive->id)
		));

		$this->assertTrue(!empty($architecture));
		$this->assertEqual($architect, $architecture->architect);

	}

	public function testDelete() {}

	public function testRules() {

		$ctrl = new ArchitecturesController();
		$rules = isset($ctrl->rules) ? $ctrl->rules : NULL;

		$this->assertTrue(!empty($rules));

		$this->assertEqual(1, sizeof($rules['index']));
		$this->assertEqual('allowAnyUser', $rules['index'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['search']));
		$this->assertEqual('allowAnyUser', $rules['search'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['histories']));
		$this->assertEqual('allowAnyUser', $rules['histories'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['view']));
		$this->assertEqual('allowAnyUser', $rules['view'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['add']));
		$this->assertEqual('allowEditorUser', $rules['add'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['edit']));
		$this->assertEqual('allowEditorUser', $rules['edit'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['history']));
		$this->assertEqual('allowAnyUser', $rules['history'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['delete']));
		$this->assertEqual('allowEditorUser', $rules['delete'][0]['rule']);
	}

}

?>
