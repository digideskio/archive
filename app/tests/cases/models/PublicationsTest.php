<?php

namespace app\tests\cases\models;

use app\models\Publications;
use app\models\PublicationsHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

use app\models\ArchivesLinks;
use app\models\Links;

class PublicationsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
	
		Publications::all()->delete();
		PublicationsHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		Links::all()->delete();
		ArchivesLinks::all()->delete();
	
	}

	public function testSave() {
	
		$data = array(
			'title' => 'Book Title',
		);
		$publication = Publications::create($data);

		$this->assertTrue($publication->validates());

		$success = $publication->save($data);

		$this->assertTrue($success);

		$data = array(
			'earliest_date' => '2010',
		);
		$publication = Publications::create($data);

		$this->assertTrue($publication->validates());

		$success = $publication->save($data);

		$this->assertTrue($success);

	}

	public function testLanguages() {

		$publication = Publications::create();
		$data = array(
			'title' => 'Book Title',
			'language' => 'French',
		);

		$publication->save($data);

		$publication = Publications::find('first', array(
			'with' => 'Archives'
		));

		$this->assertEqual('fr', $publication->archive->language_code);

		$data['language'] = 'Korean 朝鲜语';

		$publication->save($data);

		$publication = Publications::find('first', array(
			'with' => 'Archives'
		));

		$this->assertEqual('ko', $publication->archive->language_code);
	}

	public function testCreateWithNoTitle() {
		$data = array (
			"title" => "",
			"author" => "Book Author"
		);
		$publication = Publications::create($data);

		$this->assertFalse($publication->validates());
		
		$this->assertFalse($publication->save($data), "The publication was able to be saved without a title.");

		$errors = $publication->errors();

		$this->assertEqual('Please enter a title.', $errors['title'][0]);

	}

	public function testInvalidDates() {

		$data = array (
			"title" => "Book Title",
			"earliest_date" => 'X',
			"latest_date" => 'Y'
		);
		$publication = Publications::create($data);

		$this->assertFalse($publication->validates());
		
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
		$publication = Publications::create($data);

		$this->assertFalse($publication->validates());

		$success = $publication->save($data);

		$this->assertFalse($success, 'The publication could be saved with a bad URL.');

		$link_count = Links::count();

		$this->assertEqual(0, $link_count);

		$publication_link_count = ArchivesLinks::count();

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

	public function testLinks() {
		
		$data = array(
			'title' => 'Publication Title',
			'url' => 'http://example.com'
		);
		$pub = Publications::create($data);

		$this->assertTrue($pub->validates());

		$success = $pub->save($data);

		$this->assertTrue($success);

		$link = Links::first();
		$link_count = Links::count();

		$this->assertTrue(!empty($link));
		$this->assertEqual(1, $link_count);

		$this->assertEqual($pub->title, $link->title);

		$pub_link = ArchivesLinks::first();
		$pub_link_count = ArchivesLinks::count();

		$this->assertTrue(!empty($pub_link));
		$this->assertEqual(1, $pub_link_count);

		$this->assertEqual($pub->id, $pub_link->archive_id);
		$this->assertEqual($link->id, $pub_link->link_id);

		$new_data = array(
			'title' => 'Another Titlte',
			'url' => 'http://example.com'
		);

		$new_pub = Publications::create();

		$success = $new_pub->save($new_data);

		$this->assertTrue($success);

		$new_link_count = Links::count();

		$this->assertEqual(1, $new_link_count);

		$new_pubs_links_count = ArchivesLinks::count();

		$this->assertEqual(2, $new_pubs_links_count);

		$new_pub_link = ArchivesLinks::find('first', array(
			'conditions' => array('archive_id' => $new_pub->id)
		));

		$this->assertEqual($new_pub_link->link_id, $link->id);

		$new_pub->delete();
	
		$after_delete_links_count = Links::count();

		$this->assertEqual(1, $after_delete_links_count);

		$after_delete_pubs_links_count = ArchivesLinks::count();

		$this->assertEqual(1, $after_delete_pubs_links_count);

		$pub->delete();

		$final_delete_links_count = Links::count();

		$this->assertEqual(1, $final_delete_links_count);

		$final_delete_pubs_links_count = ArchivesLinks::count();

		$this->assertEqual(0, $final_delete_pubs_links_count);

	}

}

?>
