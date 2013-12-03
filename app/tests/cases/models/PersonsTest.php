<?php

namespace app\tests\cases\models;

use app\models\Persons;
use app\models\PersonsHistories;

class PersonsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {

		Persons::all()->delete();
		PersonsHistories::all()->delete();

	}

	public function testCreateWithNoId() {
		$data = array (
			"id" => "",
			"biography" => "The Biography"
		);

		$person = Persons::create($data);

		$this->assertFalse($person->validates());

		$this->assertFalse($person->save($data), "The person was able to be saved without an id.");

		$errors = $person->errors();

		$this->assertEqual('This field may not be empty.', $errors['id'][0]);

	}


}

?>
