<?php

namespace app\tests\cases\models;

use app\models\Publications;
use app\models\PublicationsHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

use app\models\PublicationsLinks;
use app\models\Links;

class PublicationsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
	
		Publications::all()->delete();
		PublicationsHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();
	
	}

	public function testSave() {
	
		$publication = Publications::create();
		$data = array(
			'title' => 'Book Title',
		);

		$success = $publication->save($data);

		$this->assertTrue($success);

		$data = array(
			'earliest_date' => '2010',
		);

		$success = $publication->save($data);

		$this->assertTrue($success);

	}

	public function testCreateWithNoTitle() {
		$publication = Publications::create();
		$data = array (
			"title" => "",
			"Author" => "Book Author"
		);
		
		$this->assertFalse($publication->save($data), "The publication was able to be saved without a title.");

		$errors = $publication->errors();

		$this->assertEqual('Please enter a title.', $errors['title'][0]);

	}

	public function testInvalidDates() {

		$publication = Publications::create();
		$data = array (
			"title" => "Book Title",
			"earliest_date" => 'X',
			"latest_date" => 'Y'
		);
		
		$this->assertFalse($publication->save($data), "The publication was able to be saved with an invalid date.");

		$errors = $publication->errors();

		$this->assertEqual('Please enter a valid date.', $errors['earliest_date'][0]);
		$this->assertEqual('Please enter a valid date.', $errors['latest_date'][0]);

	}

	public function testBadLink() {
		$data = array(
			'title' => 'Bad Book',
			'url' => 'http:// bad url'
		);

		$publication = Publications::create();

		$success = $publication->save($data);

		$this->assertFalse($success, 'The publication could be saved with a bad URL.');

		$link_count = Links::count();

		$this->assertEqual(0, $link_count);

		$publication_link_count = PublicationsLinks::count();

		$this->assertEqual(0, $publication_link_count);

		$errors = $publication->errors();

		$this->assertEqual('The URL is not valid.', $errors['url'][0]);
	}

	public function testByline() {

		$data = array(
			'title' => 'Book Title',
			'author' => 'First Last'
		);

		$publication = Publications::create($data);

		$this->assertEqual("First Last", $publication->byline());

		$data['editor'] = 'Given Surname';

		$publication = Publications::create($data);

		$this->assertEqual("First Last, Given Surname (ed.)", $publication->byline());

		$data['author'] = '';

		$publication = Publications::create($data);

		$this->assertEqual("Given Surname (ed.)", $publication->byline());

	}

}

?>
