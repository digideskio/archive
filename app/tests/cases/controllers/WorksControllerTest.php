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

	public function testView() {}
	public function testAdd() {}
	public function testEdit() {}
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
