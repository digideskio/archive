<?php

namespace app\tests\cases\controllers;

use app\controllers\MetricsController;

use lithium\storage\Session;
use app\models\Users;

use lithium\security\Auth;
use lithium\action\Request;

class MetricsControllerTest extends \lithium\test\Unit {

	public function setUp() {
	
		Session::config(array(
			'default' => array('adapter' => 'Php', 'session.name' => 'app')
		));
	
		Auth::clear('default');
	
	}

	public function tearDown() {}

	public function testIndex() {}
	public function testView() {}
	public function testAdd() {}
	public function testEdit() {}
	public function testDelete() {}

	public function testRules() {
	
		$ctrl = new MetricsController();
		$rules = isset($ctrl->rules) ? $ctrl->rules : NULL;

		$this->assertTrue(!empty($rules));

		$this->assertEqual(1, sizeof($rules['index']));
		$this->assertEqual('allowAnyUser', $rules['index'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['usage']));
		$this->assertEqual('allowAnyUser', $rules['usage'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['report']));
		$this->assertEqual('allowAnyUser', $rules['report'][0]['rule']);

	}
}

?>
