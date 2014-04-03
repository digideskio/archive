<?php

namespace app\tests\cases\controllers;

use app\controllers\ComponentsController;

use app\models\Components;
use app\models\ComponentsHistories;

class ComponentsControllerTest extends \li3_unit\test\ControllerUnit {

	public $controller = 'app\controllers\ComponentsController';

	public function setUp() {}

    public function tearDown() {
        Components::all()->delete();
        ComponentsHistories::all()->delete();
    }

	public function testIndex() {}
	public function testView() {}

	public function testAdd() {
		// Test that this action processes and saves the correct data, namely
		// a component
        $component_data = array(
            'archive_id1' => '1',
            'archive_id2' => '2',
            'type' => 'albums_works'
        );
		$data = $this->call('add', array(
            'data' => $component_data
		));

        // Test that the controller returns a redirect response
        $this->assertTrue(!empty($data->status) && $data->status['code'] == 302);

		// Look up the objects that were saved
        $component = Components::find('first', array(
            'conditions' => $component_data
        ));

        $this->assertTrue(!empty($component));
    }

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
