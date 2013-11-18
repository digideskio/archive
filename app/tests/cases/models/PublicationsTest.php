<?php

namespace app\tests\cases\models;

use app\models\Publications;
use app\models\PublicationsHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

class PublicationsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
	
		Publications::all()->delete();
		PublicationsHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();
	
	}

	public function testCreateWithNoId() {
		$data = array (
			"id" => "",
			"publisher" => "The Publisher"
		);
		$pub = Publications::create($data);

		$this->assertFalse($pub->validates());

		$this->assertFalse($pub->save($data), "The publication was able to be saved without an id.");

		$errors = $pub->errors();

		$this->assertEqual('This field may not be empty.', $errors['id'][0]);

	}

}

?>
