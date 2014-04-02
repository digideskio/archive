<?php

namespace app\tests\cases\models;

use app\models\Works;
use app\models\WorksHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

class WorksTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {

		Works::all()->delete();
		WorksHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

	}

	public function testCreateWithNoId() {
		$data = array (
			"id" => "",
			"materials" => "The Materials"
		);
		$work = Works::create($data);

		$this->assertFalse($work->validates());

		$this->assertFalse($work->save($data), "The work was able to be saved without an id.");

		$errors = $work->errors();

		$this->assertEqual('This field may not be empty.', $errors['id'][0]);

	}

	public function testDimensions() {

		$work = Works::create();

		$this->assertEqual('', $work->dimensions());

		$data = array(
			'height' => 40,
			'width' => 50
		);

		$work = Works::create($data);

		$this->assertEqual("40 × 50 cm", $work->dimensions());

		$data['depth'] = '25';

		$work = Works::create($data);

		$this->assertEqual("40 × 50 × 25 cm", $work->dimensions());

		unset($data['width']);
		unset($data['depth']);

		$data['diameter'] = 30;

		$work = Works::create($data);

		$this->assertEqual("40 cm, Ø 30 cm", $work->dimensions());

		$data = array(
			'running_time' => '1 hour 50 minutes'
		);

		$work = Works::create($data);

		$this->assertEqual("1 hour 50 minutes", $work->dimensions());

	}

	public function testAttributes() {

		$work = Works::create();
		$data = array(
			'title' => 'Test Title',
			'signed' => '1',
		);

		$work->save($data);

		$this->assertEqual('{"signed":"1"}', $work->attributes);

		$signed = $work->attribute('signed');

		$this->assertEqual($data['signed'], $signed);

		$data = array(
			'title' => 'New Title'
		);

		$work->save($data);

		$this->assertEqual($work->attributes, '[]');
	}

}

?>
