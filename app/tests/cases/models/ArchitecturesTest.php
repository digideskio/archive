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

	public function testCreateWithNoId() {
		$data = array (
			"id" => "",
			"architect" => "The Architect"
		);
		$architecture = Architectures::create($data);

		$this->assertFalse($architecture->validates());

		$this->assertFalse($architecture->save($data), "The architecture was able to be saved without an id.");

		$errors = $architecture->errors();

		$this->assertEqual('This field may not be empty.', $errors['id'][0]);

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
