<?php

namespace app\tests\cases\models;

use app\models\Albums;
use app\models\AlbumsHistories;
use app\models\Archives;
use app\models\ArchivesHistories;


class AlbumsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
	
		Albums::find('all')->delete();
		AlbumsHistories::find('all')->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

	}

	public function testCreateWithNoTitle() {
		$album = Albums::create();
		$data = array (
			"title" => "",
			"remarks" => "This is the description."
		);
		
		$this->assertFalse($album->save($data), "The album was able to be saved without a title.");

		$errors = $album->errors();

		$this->assertEqual('Please enter a title.', $errors['title'][0]);

	}


}

?>
