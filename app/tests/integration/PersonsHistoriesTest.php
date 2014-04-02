<?php

namespace app\tests\integration;

use app\models\Persons;
use app\models\PersonsHistories;

class PersonsHistoriesTest extends \lithium\test\Integration {

	public function setUp() {

		Persons::find("all")->delete();
		PersonsHistories::find("all")->delete();

		$person = Persons::create();
		$data = array (
			'id' => '1',
			'family_name' => 'Last Name',
			'given_name' => 'First Name',
			'native_family_name' => 'First',
			'native_given_name' => 'Last',
			'sex' => 'F',
			'nationality' => 'Global',
			'biography' => 'Life.',
			'remarks' => 'some text',
			'roles' => 'many',
			'email' => 'ex@example.com',
			'address' => 'Nowhere',
			'phone' => '0001000',
		);
		$person->save($data);
	}

	public function tearDown() {

		Persons::find("all")->delete();
		PersonsHistories::find("all")->delete();

	}

	public function testInsertHistory() {

		$person = Persons::first();

		$person_history = PersonsHistories::first();

		$this->assertEqual($person->family_name, $person_history->family_name);
		$this->assertEqual($person->given_name, $person_history->given_name);
		$this->assertEqual($person->native_family_name, $person_history->native_family_name);
		$this->assertEqual($person->native_given_name, $person_history->native_given_name);
		$this->assertEqual($person->sex, $person_history->sex);
		$this->assertEqual($person->nationality, $person_history->nationality);
		$this->assertEqual($person->biography, $person_history->biography);
		$this->assertEqual($person->roles, $person_history->roles);
		$this->assertEqual($person->email, $person_history->email);
		$this->assertEqual($person->address, $person_history->address);
		$this->assertEqual($person->phone, $person_history->phone);

		$this->assertNull($person_history->end_date);

		$count = PersonsHistories::count();

		$this->assertEqual(1, $count);

	}

	public function testUpdateHistory() {

		$person = Persons::first();
		$data = array(
			'family_name' => 'Last',
			'given_name' => 'First'
		);

		$person->save($data);

		$count = PersonsHistories::count();

		$this->assertEqual(2, $count);

		$person_history = PersonsHistories::find('first', array (
			'conditions' => array(
				'end_date' => NULL
			)
		));

		$this->assertEqual($person->name, $person_history->name);

	}

	public function testDeleteHistory() {
		$person = Persons::first();
		$person->delete();

		$count = PersonsHistories::count();

		$this->assertEqual(1, $count);

		$person_history = PersonsHistories::first();

		$this->assertTrue(!empty($person_history->end_date));

	}

}

?>
