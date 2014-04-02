<?php

namespace app\tests\cases\models;

use app\models\Exhibitions;
use app\models\ExhibitionsHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

class ExhibitionsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {

		Exhibitions::all()->delete();
		ExhibitionsHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

	}

	public function testCreateWithNoId() {
		$data = array (
			"id" => "",
			"venue" => "The Venue"
		);
		$exhibit = Exhibitions::create($data);

		$this->assertFalse($exhibit->validates());

		$this->assertFalse($exhibit->save($data), "The exhibition was able to be saved without an id.");

		$errors = $exhibit->errors();

		$this->assertEqual('This field may not be empty.', $errors['id'][0]);

	}

}

?>
