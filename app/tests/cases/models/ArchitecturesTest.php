<?php

namespace app\tests\cases\models;

use app\models\Architectures;
use app\models\ArchitecturesHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

class ArchitecturesTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
	
		Architectures::all()->delete();
		ArchitecturesHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();
	}

	public function testSave() {
	
		$architecture = Architectures::create();
		$data = array(
			'title' => 'Building Name',
		);

		$success = $architecture->save($data);

		$this->assertTrue($success);

		$data = array(
			'earliest_date' => '2010',
		);

		$success = $architecture->save($data);

		$this->assertTrue($success);

	}

	public function testCreateWithNoTitle() {
		$architecture = Architectures::create();
		$data = array (
			"title" => "",
			"architect" => "The Architect"
		);
		
		$this->assertFalse($architecture->save($data), "The architecture was able to be saved without a title.");

		$errors = $architecture->errors();

		$this->assertEqual('Please enter a title.', $errors['title'][0]);

	}

	public function testInvalidDates() {

		$architecture = Architectures::create();
		$data = array (
			"title" => "Book Title",
			"earliest_date" => 'X',
			"latest_date" => 'Y'
		);
		
		$this->assertFalse($architecture->save($data), "The architecture was able to be saved with an invalid date.");

		$errors = $architecture->errors();

		$this->assertEqual('Please enter a valid date.', $errors['earliest_date'][0]);
		$this->assertEqual('Please enter a valid date.', $errors['latest_date'][0]);

	}

	public function testDimensions() {

		$data = array(
			'Title' => 'Building Title',
			'area' => '2050',
		);

		$architecture = Architectures::create($data);

		$this->assertEqual('2050 square meters', $architecture->dimensions());

	}

}

?>
