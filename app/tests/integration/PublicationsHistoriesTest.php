<?php

namespace app\tests\integration;

use app\models\Publications;
use app\models\PublicationsHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

class PublicationsHistoriesTest extends \lithium\test\Integration {

	public function setUp() {

		Publications::find("all")->delete();
		PublicationsHistories::find("all")->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		$publication = Publications::create();
		$data = array(
			'title' => 'Book Title',
			'remarks' => 'Some Remarks',
			'storage_location' => 'SL',
			'storage_number' => 'SN',
			'publication_number' => 'P 001',
			'subject' => 'Tags',
			'attributes'=> '{"A":1,"B":2}',
			'language' => 'Tongue',
			'access_date' => 'Yesterday',
			'address' => '1017 Brick Street',
			'annotation' => 'Some Text',
			'author' => 'The Author',
			'book_title' => 'The Book Title',
			'chapter' => 'Chapter One',
			'edition' => 'Second Edition',
			'editor' => 'The Editor',
			'format' => 'Paperback',
			'how_published' => 'Strangely',
			'identifier' => 'ID',
			'institution' => 'The Institute',
			'ISBN' => '1234',
			'jounral' => 'The Journal',
			'location' => 'Some City',
			'note' => "A Note",
			'number' => 'Issue 1',
			'organization' => 'The Organization',
			'original_date' => 'c. 2009',
			'pages' => 'pp. 105-204',
			'publisher' => 'The Publisher',
			'school' => 'The School',
			'translator' => 'The Translator',
			'volume' => 'Volume Three'
		);

		$publication->save($data);

	}

	public function tearDown() {

		Publications::find("all")->delete();
		PublicationsHistories::find("all")->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

	}

	public function testInsertHistory() {

		$publication = Publications::first();

		$this->assertTrue(!empty($publication));

		$publication_history = PublicationsHistories::first();

		$this->assertEqual($publication->title, $publication_history->title);
		$this->assertEqual($publication->remarks, $publication_history->remarks);
		$this->assertEqual($publication->storage_location, $publication_history->storage_location);
		$this->assertEqual($publication->storage_number, $publication_history->storage_number);
		$this->assertEqual($publication->publication_number, $publication_history->publication_number);
		$this->assertEqual($publication->subject, $publication_history->subject);
		$this->assertEqual($publication->attributes, $publication_history->attributes);
		$this->assertEqual($publication->language, $publication_history->language);
		$this->assertEqual($publication->access_date, $publication_history->access_date);
		$this->assertEqual($publication->address, $publication_history->address);
		$this->assertEqual($publication->annotation, $publication_history->annotation);
		$this->assertEqual($publication->author, $publication_history->author);
		$this->assertEqual($publication->book_title, $publication_history->book_title);
		$this->assertEqual($publication->chapter, $publication_history->chapter);
		$this->assertEqual($publication->edition, $publication_history->edition);
		$this->assertEqual($publication->editor, $publication_history->editor);
		$this->assertEqual($publication->format, $publication_history->format);
		$this->assertEqual($publication->how_published, $publication_history->how_published);
		$this->assertEqual($publication->identifier, $publication_history->identifier);
		$this->assertEqual($publication->institution, $publication_history->institution);
		$this->assertEqual($publication->isbn, $publication_history->isbn);
		$this->assertEqual($publication->journal, $publication_history->journal);
		$this->assertEqual($publication->location, $publication_history->location);
		$this->assertEqual($publication->note, $publication_history->note);
		$this->assertEqual($publication->number, $publication_history->number);
		$this->assertEqual($publication->organization, $publication_history->organization);
		$this->assertEqual($publication->original_date, $publication_history->original_date);
		$this->assertEqual($publication->pages, $publication_history->pages);
		$this->assertEqual($publication->publisher, $publication_history->publisher);
		$this->assertEqual($publication->school, $publication_history->school);
		$this->assertEqual($publication->series, $publication_history->series);
		$this->assertEqual($publication->translator, $publication_history->translator);
		$this->assertEqual($publication->volume, $publication_history->volume);

		$this->assertNull($publication_history->end_date);

		$count = PublicationsHistories::count();

		$this->assertEqual(1, $count);

	}

	public function testUpdateHistory() {
	
		$publication = Publications::first();
		$data = array(
			'title' => 'New Title'
		);

		$publication->save($data); 

		$count = PublicationsHistories::count();

		$this->assertEqual(2, $count);

		$publication_history = PublicationsHistories::find('first', array (
			'conditions' => array(
				'end_date' => NULL
			)
		));

		$this->assertEqual($publication->title, $publication_history->title);

	}

	public function testDeleteHistory() {
		$publication = Publications::first();
		$publication->delete();

		$count = PublicationsHistories::count();

		$this->assertEqual(1, $count);

		$publication_history = PublicationsHistories::first();

		$this->assertTrue(!empty($publication_history->end_date));

	}
}

?>
