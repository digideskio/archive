<?php

namespace app\tests\integration;

use app\models\Works;
use app\models\WorksHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

class WorksHistoriesTest extends \lithium\test\Integration {

	public function setUp() {

		Works::find("all")->delete();
		WorksHistories::find("all")->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		$work = Works::create();
		$data = array (
			'id' => '1',
			'artist' => 'The Artist',
			'artist_native' => '艺术家',
			'creation_number' => 'ARTWORK001',
			'materials' => 'Some Materials',
			'techniques' => 'A Technique',
			'color'=> 'Rainbow',
			'format'=> 'VHS',
			'shape'=> 'round',
			'size'=> 'XS',
			'state'=> 'Finished',
			'location'=> 'Nowhere',
			'quantity'=> 'Many',
			'annotation' => 'The Annotation',
			'inscriptions' => 'Some words',
			'height' => '10',
			'width' => '11',
			'depth' => '12',
			'length' => '12.5',
			'circumference' => '26',
			'diameter' => '13',
			'volume' => '13.35',
			'weight' => '14',
			'area' => '100.5',
			'base' => '50.8',
			'running_time'=> '15 hours',
			'measurement_remark' => 'It is big',
			'attributes'=> '{"x":5,"y":6}',
		);

		$work->save($data);
	}

	public function tearDown() {
	
		Works::find("all")->delete();
		WorksHistories::find("all")->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

	}

	public function testInsertHistory() {

		$work = Works::first();

		$work_history = WorksHistories::first();

		$this->assertEqual($work->artist, $work_history->artist);
		$this->assertEqual($work->artist_native, $work_history->artist_native);
		$this->assertEqual($work->creation_number, $work_history->creation_number);
		$this->assertEqual($work->materials, $work_history->materials);
		$this->assertEqual($work->techniques, $work_history->techniques);
		$this->assertEqual($work->color, $work_history->color);
		$this->assertEqual($work->format, $work_history->format);
		$this->assertEqual($work->shape, $work_history->shape);
		$this->assertEqual($work->size, $work_history->size);
		$this->assertEqual($work->state, $work_history->state);
		$this->assertEqual($work->location, $work_history->location);
		$this->assertEqual($work->quantity, $work_history->quantity);
		$this->assertEqual($work->annotation, $work_history->annotation);
		$this->assertEqual($work->inscriptions, $work_history->inscriptions);
		$this->assertEqual($work->height, $work_history->height);
		$this->assertEqual($work->width, $work_history->width);
		$this->assertEqual($work->depth, $work_history->depth);
		$this->assertEqual($work->length, $work_history->length);
		$this->assertEqual($work->circumference, $work_history->circumference);
		$this->assertEqual($work->diameter, $work_history->diameter);
		$this->assertEqual($work->volume, $work_history->volume);
		$this->assertEqual($work->weight, $work_history->weight);
		$this->assertEqual($work->area, $work_history->area);
		$this->assertEqual($work->base, $work_history->base);
		$this->assertEqual($work->running_time, $work_history->running_time);
		$this->assertEqual($work->measurement_remarks, $work_history->measurement_remarks);
		$this->assertEqual($work->attributes, $work_history->attributes);

		$this->assertNull($work_history->end_date);

		$count = WorksHistories::count();

		$this->assertEqual(1, $count);

	}

	public function testUpdateHistory() {
	
		$work = Works::first();
		$data = array(
			'materials' => 'New Materials'
		);

		$work->save($data); 

		$count = WorksHistories::count();

		$this->assertEqual(2, $count);

		$work_history = WorksHistories::find('first', array (
			'conditions' => array(
				'end_date' => NULL
			)
		));

		$this->assertEqual($work->materials, $work_history->materials);

	}

	public function testDeleteHistory() {
		$work = Works::first();
		$work->delete();

		$count = WorksHistories::count();

		$this->assertEqual(1, $count);

		$work_history = WorksHistories::first();

		$this->assertTrue(!empty($work_history->end_date));

	}

}

?>
