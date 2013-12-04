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

	}

	public function tearDown() {
	
		Persons::all()->delete();
		PersonsHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		Links::all()->delete();
		ArchivesLinks::all()->delete();

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
