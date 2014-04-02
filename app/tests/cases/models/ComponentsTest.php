<?php

namespace app\tests\cases\models;

use app\models\Components;
use app\models\ComponentsHistories;

class ComponentsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {

		Components::all()->delete();
		ComponentsHistories::all()->delete();

	}

	public function testDateCreatedModifiedFilter() {

		$component = Components::create();
		$component->save(array(
			'archive_id1' => 1,
			'archive_id2' =>2
		));

		$this->assertTrue(strtotime($component->date_created) != false);
		$this->assertTrue(strtotime($component->date_modified) != false);

		$this->assertTrue($component->date_created != '0000-00-00 00:00:00');
		$this->assertTrue($component->date_modified != '0000-00-00 00:00:00');

	}


}

?>
