<?php

namespace app\tests\cases\models;

use app\models\Albums;

class AlbumsTest extends \lithium\test\Unit {

	public function setUp() {
		Albums::find('all')->delete();
	}

	public function tearDown() {
	
		Albums::find('all')->delete();

	}
	
	public function testCreateAlbum() {
		$album = Albums::create();
		$data = array (
			"title" => "Album Title",
			"description" => "This is the Album Description"
		);
		
		$slug = "Album-Title";
		
		$this->assertTrue($album->save($data));
		$this->assertEqual($slug, $album->slug);
		
		$second_album = Albums::create();
		$second_slug = "Album-Title-1";
		
		$this->assertTrue($second_album->save($data));
		$this->assertEqual($second_slug, $second_album->slug);
		
	}
	
	public function testCreateAlbumWithNoTitle() {
		$album = Albums::create();
		$data = array (
			"title" => "",
			"description" => "This is the Album Description"
		);
		
		$this->assertFalse($album->save($data));
	}


}

?>
