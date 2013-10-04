<?php

namespace app\tests\cases\models;

use app\models\Works;
use app\models\WorksHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

use app\models\ArchivesLinks;
use app\models\Links;

class WorksTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
	
		Works::all()->delete();
		WorksHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		Links::all()->delete();
		ArchivesLinks::all()->delete();

	}

	public function testValidateAndSave() {
	
		$data = array(
			'title' => 'Artwork Title',
		);

		$work = Works::create($data);

		$this->assertTrue($work->validates());

		$success = $work->save($data);

		$this->assertTrue($success);

		$data = array(
			'earliest_date' => '2010',
		);

		$work = Works::create($data);

		$this->assertTrue($work->validates());

		$success = $work->save($data);

		$this->assertTrue($success);

	}

	
	public function testCreateWithNoTitle() {
		$data = array (
			"title" => "",
			"artist" => "Artwork Artist"
		);

		$work = Works::create($data);
		
		$this->assertFalse($work->validates());

		$this->assertFalse($work->save($data), "The artwork was able to be saved without a title.");

		$errors = $work->errors();

		$this->assertEqual('Please enter a title.', $errors['title'][0]);

	}

	public function testInvalidDates() {

		$work = Works::create();
		$data = array (
			"title" => "Artwork Title",
			"artist" => "Artwork Artist",
			"earliest_date" => 'X',
			"latest_date" => 'Y'
		);
		
		$this->assertFalse($work->save($data), "The archive was able to be saved with an invalid date.");

		$errors = $work->errors();

		$this->assertEqual('Please enter a valid date.', $errors['earliest_date'][0]);
		$this->assertEqual('Please enter a valid date.', $errors['latest_date'][0]);

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

	public function testBadLink() {
		$data = array(
			'title' => 'Bad Artwork',
			'url' => 'http:// bad url'
		);

		$work = Works::create();

		$success = $work->save($data);

		$this->assertFalse($success, 'The work could be saved with a bad URL.');

		$link_count = Links::count();

		$this->assertEqual(0, $link_count);

		$work_link_count = ArchivesLinks::count();

		$this->assertEqual(0, $work_link_count);

		$errors = $work->errors();

		$this->assertEqual('The URL is not valid.', $errors['url'][0]);
	}

	public function testLinks() {
		
		$data = array(
			'title' => 'Artwork Title',
			'url' => 'http://example.com'
		);

		$work = Works::create();

		$success = $work->save($data);

		$this->assertTrue($success);

		$link = Links::first();
		$link_count = Links::count();

		$this->assertTrue(!empty($link));
		$this->assertEqual(1, $link_count);

		$this->assertEqual($work->title, $link->title);

		$work_link = ArchivesLinks::first();
		$work_link_count = ArchivesLinks::count();

		$this->assertTrue(!empty($work_link));
		$this->assertEqual(1, $work_link_count);

		$this->assertEqual($work->id, $work_link->archive_id);
		$this->assertEqual($link->id, $work_link->link_id);

		$new_data = array(
			'title' => 'Another Titlte',
			'url' => 'http://example.com'
		);

		$new_work = Works::create();

		$success = $new_work->save($new_data);

		$this->assertTrue($success);

		$new_link_count = Links::count();

		$this->assertEqual(1, $new_link_count);

		$new_works_links_count = ArchivesLinks::count();

		$this->assertEqual(2, $new_works_links_count);

		$new_work_link = ArchivesLinks::find('first', array(
			'conditions' => array('archive_id' => $new_work->id)
		));

		$this->assertEqual($new_work_link->link_id, $link->id);

		$new_work->delete();
	
		$after_delete_links_count = Links::count();

		$this->assertEqual(1, $after_delete_links_count);

		$after_delete_works_links_count = ArchivesLinks::count();

		$this->assertEqual(1, $after_delete_works_links_count);

		$work->delete();

		$final_delete_links_count = Links::count();

		$this->assertEqual(1, $final_delete_links_count);

		$final_delete_works_links_count = ArchivesLinks::count();

		$this->assertEqual(0, $final_delete_works_links_count);

	}


}

?>
