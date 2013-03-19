<?php

namespace app\tests\cases\models;

use app\models\Exhibitions;
use app\models\ExhibitionsHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

use app\models\ExhibitionsLinks;
use app\models\Links;

class ExhibitionsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {

		Exhibitions::all()->delete();
		ExhibitionsHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();
	
	}

	public function testSave() {
	
		$exhibition = Exhibitions::create();
		$data = array(
			'title' => 'Exhibition Title',
		);

		$success = $exhibition->save($data);

		$this->assertTrue($success);

		$data = array(
			'earliest_date' => '2010',
		);

		$success = $exhibition->save($data);

		$this->assertTrue($success);

	}

	public function testCreateWithNoTitle() {
		$exhibition = Exhibitions::create();
		$data = array (
			"title" => "",
			"curator" => "Exhibition Curator"
		);
		
		$this->assertFalse($exhibition->save($data), "The exhibition was able to be saved without a title.");

		$errors = $exhibition->errors();

		$this->assertEqual('Please enter a title.', $errors['title'][0]);

	}

	public function testInvalidDates() {

		$exhibition = Exhibitions::create();
		$data = array (
			"title" => "Exhibition Title",
			"earliest_date" => 'X',
			"latest_date" => 'Y'
		);
		
		$this->assertFalse($exhibition->save($data), "The exhibition was able to be saved with an invalid date.");

		$errors = $exhibition->errors();

		$this->assertEqual('Please enter a valid date.', $errors['earliest_date'][0]);
		$this->assertEqual('Please enter a valid date.', $errors['latest_date'][0]);

	}

	public function testBadLink() {
		$data = array(
			'title' => 'Bad Exhibition',
			'url' => 'http:// bad url'
		);

		$exhibition = Exhibitions::create();

		$success = $exhibition->save($data);

		$this->assertFalse($success, 'The exhibition could be saved with a bad URL.');

		$link_count = Links::count();

		$this->assertEqual(0, $link_count);

		$exhibition_link_count = ExhibitionsLinks::count();

		$this->assertEqual(0, $exhibition_link_count);

		$errors = $exhibition->errors();

		$this->assertEqual('The URL is not valid.', $errors['url'][0]);
	}


}

?>
