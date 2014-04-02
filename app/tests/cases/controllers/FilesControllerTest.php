<?php

namespace app\tests\cases\controllers;

use app\controllers\FilesController;

use app\models\Users;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class FilesControllerTest extends \lithium\test\Unit {

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

		$ctrl = new FilesController();
		$rules = isset($ctrl->rules) ? $ctrl->rules : NULL;

		$this->assertTrue(!empty($rules));

		$this->assertEqual(1, sizeof($rules['view']));
		$this->assertEqual('allowAnyUser', $rules['view'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['small']));
		$this->assertEqual('allowAnyUser', $rules['small'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['thumb']));
		$this->assertEqual('allowAnyUser', $rules['thumb'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['download']));
		$this->assertEqual('allowAnyUser', $rules['download'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['package']));
		$this->assertEqual('allowAll', $rules['package'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['secure']));
		$this->assertEqual('allowAnyUser', $rules['secure'][0]['rule']);
	}
}

?>
