<?php

namespace app\tests\integration;

use app\models\Archives;
use app\models\ArchivesHistories;

class ArchivesHistoriesTest extends \lithium\test\Integration {

	public function setUp() {

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		$archive = Archives::create();
		$data = array (
			'name' => 'name',
			'native_name' => 'namen',
			'language_code' => 'sv',
			'controller' => 'archives',
			'classification' => 'Artwork',
			'type' => 'Painting',
			'catalog_level' => 'item',
			'description' => 'some text',
			'slug' => 'name',
			'earliest_date' => '2012-01-10', 
			'latest_date' => '2012-02-28',
			'earliest_date_format' => 'Y-m-d',
			'latest_date_format' => 'Y-m-d',
			'date_created'=> '2013-01-01 10:10:10',
			'date_modified' => '2013-01-02 11:11:11',
			'user_id' => '1',
			'parent_id' => '10',
		);
		$archive->save($data);
	}

	public function tearDown() {
	
		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

	}

	public function testInsertHistory() {

		$archive = Archives::first();

		$archive_history = ArchivesHistories::first();

		$this->assertEqual($archive->name, $archive_history->name);
		$this->assertEqual($archive->native_name, $archive_history->native_name);
		$this->assertEqual($archive->language_code, $archive_history->language_code);
		$this->assertEqual($archive->controller, $archive_history->controller);
		$this->assertEqual($archive->classification, $archive_history->classification);
		$this->assertEqual($archive->type, $archive_history->type);
		$this->assertEqual($archive->catalog_level, $archive_history->catalog_level);
		$this->assertEqual($archive->description, $archive_history->description);
		$this->assertEqual($archive->slug, $archive_history->slug);
		$this->assertEqual($archive->earliest_date, $archive_history->earliest_date);
		$this->assertEqual($archive->latest_date, $archive_history->latest_date);
		$this->assertEqual($archive->earliest_date_format, $archive_history->earliest_date_format);
		$this->assertEqual($archive->latest_date_format, $archive_history->latest_date_format);
		$this->assertEqual($archive->date_created, $archive_history->date_created);
		$this->assertEqual($archive->date_modified, $archive_history->date_modified);
		$this->assertEqual($archive->user_id, $archive_history->user_id);
		$this->assertEqual($archive->parent_id, $archive_history->parent_id);

		$this->assertNull($archive_history->end_date);

		$count = ArchivesHistories::count();

		$this->assertEqual(1, $count);


	}

	public function testUpdateHistory() {
	
		$archive = Archives::first();
		$data = array(
			'name' => 'New Name'
		);

		$archive->save($data); 

		$count = ArchivesHistories::count();

		$this->assertEqual(2, $count);

		$archive_history = ArchivesHistories::find('first', array (
			'conditions' => array(
				'end_date' => NULL
			)
		));

		$this->assertEqual($archive->name, $archive_history->name);

	}

	public function testDeleteHistory() {
		$archive = Archives::first();
		$archive->delete();

		$count = ArchivesHistories::count();

		$this->assertEqual(1, $count);

		$archive_history = ArchivesHistories::first();

		$this->assertTrue($archive_history->end_date);

	}

}

?>
