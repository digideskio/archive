<?php

namespace app\tests\cases\controllers;

use app\controllers\SearchController;

class SearchControllerTest extends \lithium\test\Unit {

	public function tearDown() {}

	public function testIndex() {}
	
	public function testRules() {
	
		$ctrl = new SearchController();
		$rules = isset($ctrl->rules) ? $ctrl->rules : NULL;

		$this->assertTrue(!empty($rules));

		$this->assertEqual(1, sizeof($rules['index']));
		$this->assertEqual('allowAnyUser', $rules['index'][0]['rule']);

	}
}

?>
