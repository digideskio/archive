<?php

namespace app\tests\integration;

use app\models\Albums;
use app\models\AlbumsHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

class AlbumsHistoriesTest extends \lithium\test\Integration {

	public function setUp() {

		Albums::find("all")->delete();
		AlbumsHistories::find("all")->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		$album = Albums::create();
		$data = array (
			'id' => '1',
			'remarks' => 'Some remarks'
		);

		$album->save($data);
	}

	public function tearDown() {

		Albums::find("all")->delete();
		AlbumsHistories::find("all")->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

	}

	public function testInsertHistory() {

		$album = Albums::first();

		$album_history = AlbumsHistories::first();

		$this->assertEqual($album->remarks, $album_history->remarks);

		$this->assertNull($album_history->end_date);

		$count = AlbumsHistories::count();

		$this->assertEqual(1, $count);

	}

	public function testUpdateHistory() {

		$album = Albums::first();
		$data = array(
			'remarks' => 'New remarks'
		);

		$album->save($data);

		$count = AlbumsHistories::count();

		$this->assertEqual(2, $count);

		$album_history = AlbumsHistories::find('first', array (
			'conditions' => array(
				'end_date' => NULL
			)
		));

		$this->assertEqual($album->remarks, $album_history->remarks);

	}

	public function testDeleteHistory() {
		$album = Albums::first();
		$album->delete();

		$count = AlbumsHistories::count();

		$this->assertEqual(1, $count);

		$album_history = AlbumsHistories::first();

		$this->assertTrue(!empty($album_history->end_date));

	}

}

?>
