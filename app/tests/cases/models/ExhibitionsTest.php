<?php

namespace app\tests\cases\models;

use app\models\Exhibitions;
use app\models\ExhibitionsHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

use app\models\ArchivesLinks;
use app\models\Links;

class ExhibitionsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {

		Exhibitions::all()->delete();
		ExhibitionsHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		Links::all()->delete();
		ArchivesLinks::all()->delete();
	
	}

	public function testSave() {
	
		$data = array(
			'title' => 'Exhibition Title',
		);
		$exhibition = Exhibitions::create($data);

		$this->assertTrue($exhibition->validates());

		$success = $exhibition->save($data);

		$this->assertTrue($success);

		$data = array(
			'earliest_date' => '2010',
		);
		$exhibition = Exhibitions::create($data);

		$this->assertTrue($exhibition->validates());

		$success = $exhibition->save($data);

		$this->assertTrue($success);

	}

	public function testCreateWithNoTitle() {
		$data = array (
			"title" => "",
			"curator" => "Exhibition Curator"
		);
		$exhibition = Exhibitions::create($data);

		$this->assertFalse($exhibition->validates());
		
		$this->assertFalse($exhibition->save($data), "The exhibition was able to be saved without a title.");

		$errors = $exhibition->errors();

		$this->assertEqual('Please enter a title.', $errors['title'][0]);

	}

	public function testInvalidDates() {

		$data = array (
			"title" => "Exhibition Title",
			"earliest_date" => 'X',
			"latest_date" => 'Y'
		);
		$exhibition = Exhibitions::create($data);

		$this->assertFalse($exhibition->validates());
		
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
		$exhibition = Exhibitions::create($data);

		$this->assertFalse($exhibition->validates());

		$success = $exhibition->save($data);

		$this->assertFalse($success, 'The exhibition could be saved with a bad URL.');

		$link_count = Links::count();

		$this->assertEqual(0, $link_count);

		$exhibition_link_count = ArchivesLinks::count();

		$this->assertEqual(0, $exhibition_link_count);

		$errors = $exhibition->errors();

		$this->assertEqual('The URL is not valid.', $errors['url'][0]);
	}

	public function testLinks() {
		
		$data = array(
			'title' => 'Exhibition Title',
			'url' => 'http://example.com'
		);
		$exhibit = Exhibitions::create($data);

		$this->assertTrue($exhibit->validates());

		$success = $exhibit->save($data);

		$this->assertTrue($success);

		$link = Links::first();
		$link_count = Links::count();

		$this->assertTrue(!empty($link));
		$this->assertEqual(1, $link_count);

		$this->assertEqual($exhibit->title, $link->title);

		$exhibit_link = ArchivesLinks::first();
		$exhibit_link_count = ArchivesLinks::count();

		$this->assertTrue(!empty($exhibit_link));
		$this->assertEqual(1, $exhibit_link_count);

		$this->assertEqual($exhibit->id, $exhibit_link->archive_id);
		$this->assertEqual($link->id, $exhibit_link->link_id);

		$new_data = array(
			'title' => 'Another Titlte',
			'url' => 'http://example.com'
		);
		$new_exhibit = Exhibitions::create($new_data);

		$this->assertTrue($new_exhibit->validates());

		$success = $new_exhibit->save($new_data);

		$this->assertTrue($success);

		$new_link_count = Links::count();

		$this->assertEqual(1, $new_link_count);

		$new_exhibits_links_count = ArchivesLinks::count();

		$this->assertEqual(2, $new_exhibits_links_count);

		$new_exhibit_link = ArchivesLinks::find('first', array(
			'conditions' => array('archive_id' => $new_exhibit->id)
		));

		$this->assertEqual($new_exhibit_link->link_id, $link->id);

		$new_exhibit->delete();
	
		$after_delete_links_count = Links::count();

		$this->assertEqual(1, $after_delete_links_count);

		$after_delete_exhibits_links_count = ArchivesLinks::count();

		$this->assertEqual(1, $after_delete_exhibits_links_count);

		$exhibit->delete();

		$final_delete_links_count = Links::count();

		$this->assertEqual(1, $final_delete_links_count);

		$final_delete_exhibits_links_count = ArchivesLinks::count();

		$this->assertEqual(0, $final_delete_exhibits_links_count);

	}


}

?>
