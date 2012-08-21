<?php

namespace app\tests\integration;

use app\models\Works;
use app\models\WorksHistories;

class WorksHistoriesTest extends \lithium\test\Integration {

	public function setUp() {

		Works::find("all")->delete();
		WorksHistories::find("all")->delete();

		$work = Works::create();
		$data = array (
			'user_id' => '1',
			'artist' => 'The Artist',
			'title' => 'Artwork Title',
			'classifiction' => 'An Object',
			'materials' => 'Some Materials',
			'quantity'=> 'Many',
			'earliest_date' => '2012-01-10', 
			'latest_date' => '2012-02-28', 
			'creation_number' => 'ARTWORK001',
			'height' => '10',
			'width' => '11',
			'depth' => '12',
			'diameter' => '13',
			'weight' => '14',
			'running_time'=> '15 hours',
			'measurement_remark' => 'It is big',
			'annotation' => 'The Annotation'
		);

		$work->save($data);
	}

	public function tearDown() {
	
		Works::find("all")->delete();
		WorksHistories::find("all")->delete();

	}

	public function testInsertHistory() {

		$work = Works::first();

		$work_history = WorksHistories::first();

		$this->assertEqual($work->user_id, $work_history->user_id);
		$this->assertEqual($work->artist, $work_history->artist);
		$this->assertEqual($work->title, $work_history->title);
		$this->assertEqual($work->classification, $work_history->classification);
		$this->assertEqual($work->materials, $work_history->materials);
		$this->assertEqual($work->quantity, $work_history->quantity);
		$this->assertEqual($work->earliest_date, $work_history->earliest_date);
		$this->assertEqual($work->latest_date, $work_history->latest_date);
		$this->assertEqual($work->creation_number, $work_history->creation_number);
		$this->assertEqual($work->height, $work_history->height);
		$this->assertEqual($work->width, $work_history->width);
		$this->assertEqual($work->depth, $work_history->depth);
		$this->assertEqual($work->diameter, $work_history->diameter);
		$this->assertEqual($work->weight, $work_history->weight);
		$this->assertEqual($work->running_time, $work_history->running_time);
		$this->assertEqual($work->measurement_remarks, $work_history->measurement_remarks);
		$this->assertEqual($work->annotation, $work_history->annotation);
		$this->assertEqual($work->slug, $work_history->slug);

		$this->assertNull($work_history->end_date);

		$count = WorksHistories::count();

		$this->assertEqual(1, $count);


	}

	public function testUpdateHistory() {
	
		$work = Works::first();
		$data = array(
			'title' => 'New Title'
		);

		$work->save($data); 

		$count = WorksHistories::count();

		$this->assertEqual(2, $count);

		$work_history = WorksHistories::find('first', array (
			'conditions' => array(
				'end_date' => NULL
			)
		));

		$this->assertEqual($work->title, $work_history->title);

	}

	public function testDeleteHistory() {
		$work = Works::first();
		$work->delete();

		$count = WorksHistories::count();

		$this->assertEqual(1, $count);

		$work_history = WorksHistories::first();

		$this->assertTrue($work_history->end_date);

	}

}

?>
