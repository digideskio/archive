<?php

namespace app\tests\cases\controllers;

use app\controllers\PublicationsController;

use app\models\Publications;
use app\models\PublicationsHistories;
use app\models\Archives;
use app\models\ArchivesHistories;
use app\models\Links;
use app\models\ArchivesLinks;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;
use lithium\net\http\Router;

class PublicationsControllerTest extends \li3_unit\test\ControllerUnit {

	public $controller = 'app\\controllers\PublicationsController';

	public function setUp() {
	
		$pub = Publications::create();
		$data = array(
			'title' => 'Publication Title',
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
	
	}

	public function testIndex() {
		$data = $this->call('index');

		$pubs = $data['publications'];
		$total = $data['total'];

		$pub = $pubs->first();

		$this->assertEqual('Publication Title', $pub->title);
		$this->assertEqual(1, $total);

		$this->assertTrue(isset($data['page']));
		$this->assertTrue(isset($data['limit']));
	
	}

	public function testView() {

		$data = $this->call('view', array(
			'params' => array(
				'slug' => 'Publication-Title'
			)
		));

		$pub = $data['publication'];

		$this->assertEqual('Publication Title', $pub->title);

	}

	public function testAdd() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/publications/view/{:slug}', array('Publications::view'));

		// Test that a publication model is created and passed to the view
		$data = $this->call('add', array(
			'params' => array()
		));

		$this->assertTrue(isset($data['publication']));

		// Test that this action processes and saves the correct data, namely
		// a publication, archive, and link model
		$title = 'Publication New Title';
		$slug = 'Publication-New-Title';
		$url = 'http://example.com/publication-new-title';

		$data = $this->call('add', array(
			'data' => compact('title', 'url')
		));

		$pub = Publications::find('first', array(
			'conditions' => compact('title')
		));

		$this->assertTrue(!empty($pub));

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
				'slug' => 'Publication-Title'
			)
		));

		$pub = $data['publication'];

		$this->assertEqual('Publication Title', $pub->title);

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
