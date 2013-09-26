<?php

namespace app\tests\cases\controllers;

use app\controllers\ExhibitionsController;

use app\models\Exhibitions;
use app\models\ExhibitionsHistories;
use app\models\Archives;
use app\models\ArchivesHistories;
use app\models\Links;
use app\models\ArchivesLinks;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;
use lithium\net\http\Router;

class ExhibitionsControllerTest extends \li3_unit\test\ControllerUnit {

	public $controller = 'app\\controllers\ExhibitionsController';

	public function setUp() {
	
		$exhibit = Exhibitions::create();
		$data = array(
			'title' => 'Exhibition Title',
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
	
	}

	public function testIndex() {
		$data = $this->call('index');

		$exhibitions = $data['exhibitions'];
		$total = $data['total'];

		$exhibition = $exhibitions->first();

		$this->assertEqual('Exhibition Title', $exhibition->title);
		$this->assertEqual(1, $total);

		$this->assertTrue(isset($data['page']));
		$this->assertTrue(isset($data['limit']));
	
	}

	public function testView() {

		$data = $this->call('view', array(
			'params' => array(
				'slug' => 'Exhibition-Title'
			)
		));

		$exhibition = $data['exhibition'];

		$this->assertEqual('Exhibition Title', $exhibition->title);

	}

	public function testAdd() {

		// Make sure the route that the add action redirects to is connected,
		// otherwise we get an error that there is no match for this route.
		Router::connect('/exhibitions/view/{:slug}', array('Exhibitions::view'));

		// Test that a exhibition model is created and passed to the view
		$data = $this->call('add', array(
			'params' => array()
		));

		$this->assertTrue(isset($data['exhibition']));

		// Test that this action processes and saves the correct data, namely
		// an exhibition, archive, and link model
		$title = 'Exhibition New Title';
		$slug = 'Exhibition-New-Title';
		$url = 'http://example.com/exhibit-new-title';

		$data = $this->call('add', array(
			'data' => compact('title', 'url')
		));

		$exhibit = Exhibitions::find('first', array(
			'conditions' => compact('title')
		));

		$this->assertTrue(!empty($exhibit));

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
				'slug' => 'Exhibition-Title'
			)
		));

		$exhibition = $data['exhibition'];

		$this->assertEqual('Exhibition Title', $exhibition->title);

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
