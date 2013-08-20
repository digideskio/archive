<?php

namespace app\tests\cases\controllers;

use app\controllers\ComponentsController;

class ComponentsControllerTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}

	public function testIndex() {}
	public function testView() {}
	public function testAdd() {}
	public function testEdit() {}
	public function testDelete() {}

	public function testRules() {
	
		$ctrl = new ComponentsController();
		$rules = isset($ctrl->rules) ? $ctrl->rules : NULL;

		$this->assertTrue(!empty($rules));

		$this->assertEqual(1, sizeof($rules['add']));
		$this->assertEqual('allowEditorUser', $rules['add'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['delete']));
		$this->assertEqual('allowEditorUser', $rules['delete'][0]['rule']);
	}
}

?>
