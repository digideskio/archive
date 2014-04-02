<?php

namespace app\tests\cases\controllers;

use app\controllers\PagesController;
use app\models\Users;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class PagesControllerTest extends \lithium\test\Unit {

	public function setUp() {

		Session::config(array(
			'default' => array('adapter' => 'Php', 'session.name' => 'app')
		));
	}

	public function tearDown() {}

	public function testIndex() {}
	public function testView() {}
	public function testAdd() {}
	public function testEdit() {}
	public function testDelete() {}

	public function testRules() {

		$ctrl = new PagesController();
		$rules = isset($ctrl->rules) ? $ctrl->rules : NULL;

		$this->assertTrue(!empty($rules));

		$this->assertEqual(1, sizeof($rules['home']));
		$this->assertEqual('allowAnyUser', $rules['home'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['view']));
		$this->assertEqual('allowAnyUser', $rules['view'][0]['rule']);

	}
}

?>
