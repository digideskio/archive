<?php

namespace app\tests\cases\models;

use app\models\Languages;

class LanguagesTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}

	public function testLanguages() {

		$count = Languages::count();

		$this->assertEqual('247', $count);

		$english = Languages::find('first', array(
			'conditions' => array('name' => 'English'),
		));

		$this->assertEqual('en', $english->code);

		$french = Languages::find('first', array(
			'conditions' => array('code' => 'fr'),
		));

		$this->assertEqual('Français', $french->native_name);

	}

}

?>
