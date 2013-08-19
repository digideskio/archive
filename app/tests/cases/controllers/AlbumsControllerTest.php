<?php

namespace app\tests\cases\controllers;

use app\controllers\AlbumsController;

use app\model\Albums;
use lithium\storage\Session;
use app\models\Users;

use lithium\security\Auth;
use lithium\action\Request;

class AlbumsControllerTest extends \lithium\test\Unit {

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
	
		$ctrl = new AlbumsController();
		$rules = isset($ctrl->rules) ? $ctrl->rules : NULL;

		$this->assertTrue(!empty($rules));

		$this->assertEqual(1, sizeof($rules['index']));
		$this->assertEqual('allowAnyUser', $rules['index'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['view']));
		$this->assertEqual('allowAnyUser', $rules['view'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['add']));
		$this->assertEqual('allowEditorUser', $rules['add'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['edit']));
		$this->assertEqual('allowEditorUser', $rules['edit'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['history']));
		$this->assertEqual('allowAnyUser', $rules['history'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['publish']));
		$this->assertEqual('allowAnyUser', $rules['publish'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['packages']));
		$this->assertEqual('allowAnyUser', $rules['packages'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['package']));
		$this->assertEqual('allowAnyUser', $rules['package'][0]['rule']);

		$this->assertEqual(1, sizeof($rules['delete']));
		$this->assertEqual('allowEditorUser', $rules['delete'][0]['rule']);
	}
}

?>
