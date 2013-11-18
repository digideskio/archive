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

	public function testCreateWithNoId() {
		$data = array (
			"id" => "",
			"remarks" => "This is the description."
		);
		$album = Albums::create($data);

		$this->assertFalse($album->validates());
		
		$this->assertFalse($album->save($data), "The album was able to be saved without an id.");

		$errors = $album->errors();

		$this->assertEqual('This field may not be empty.', $errors['id'][0]);

	}


}

?>
