<?php

namespace app\tests\integration;

use app\models\Components;
use app\models\ComponentsHistories;

class ComponentsHistoriesTest extends \lithium\test\Integration {


	public function setUp() {
	
		$component = Components::create();
		$data = array (
			'archive_id1' => '123',
			'archive_id2' => '456',
			'type' => 'albums_works',
			'role' => 'Role',
			'qualifier' => 'Mmm',
			'extent' => 'Yep',
			'remarks' => 'Some Remarks',
			'attributes'=> '{"x":5,"y":6}',
			'earliest_date' => '2012-01-10', 
			'latest_date' => '2012-02-28',
			'earliest_date_format' => 'Y-m-d',
			'latest_date_format' => 'Y-m-d',
			'date_created'=> '2013-01-01 10:10:10',
			'date_modified' => '2013-01-02 11:11:11',
			'user_id' => '1',
		);

		$component->save($data);

	}

	public function tearDown() {

		Components::find("all")->delete();
		ComponentsHistories::find("all")->delete();

	}

	public function testInsertHistory() {

		$component = Components::first();

		$component_history = ComponentsHistories::first();

		$this->assertEqual($component->archive_id1, $component->archive_id1);
		$this->assertEqual($component->archive_id2, $component->archive_id2);
		$this->assertEqual($component->type, $component->type);
		$this->assertEqual($component->role, $component->role);
		$this->assertEqual($component->qualifier, $component->qualifier);
		$this->assertEqual($component->extent, $component->extent);
		$this->assertEqual($component->remarks, $component->remarks);
		$this->assertEqual($component->attributes, $component->attributes);
		$this->assertEqual($component->earliest_date, $component->earliest_date);
		$this->assertEqual($component->latest_date, $component->latest_date);
		$this->assertEqual($component->earliest_date_format, $component->earliest_date_format);
		$this->assertEqual($component->latest_date_format, $component->latest_date_format);
		$this->assertEqual($component->date_created, $component->date_created);
		$this->assertEqual($component->date_modified, $component->date_modified);
		$this->assertEqual($component->user_id, $component->user_id);

		$this->assertNull($component_history->end_date);

		$count = ComponentsHistories::count();

		$this->assertEqual(1, $count);
	}

	public function testUpdateHistory() {
	
		$component = Components::first();
		$data = array(
			'qualifier' => 'Yeah'
		);

		$component->save($data); 

		$count = ComponentsHistories::count();

		$this->assertEqual(2, $count);

		$component_history = ComponentsHistories::find('first', array (
			'conditions' => array(
				'end_date' => NULL
			)
		));

		$this->assertEqual($component->qualifier, $component_history->qualifier);

	}

	public function testDeleteHistory() {
		$component = Components::first();
		$component->delete();

		$count = ComponentsHistories::count();

		$this->assertEqual(1, $count);

		$component_history = ComponentsHistories::first();

		$this->assertTrue($component_history->end_date);

	}

}


?>
