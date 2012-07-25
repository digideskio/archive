<?php

namespace app\tests\cases\models;

use app\models\Works;

class WorksTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}
	
	public function testCreateArtwork() {
		$work = Works::create();
		$data = array (
			"title" => "Artwork Title",
			"artist" => "Artwork Artist"
		);
		
		$slug = "Artwork-Title";
		
		$this->assertTrue($work->save($data));
		$this->assertEqual($slug, $work->slug);
		
		$second_work = Works::create();
		$second_slug = "Artwork-Title-2";
		
		$this->assertTrue($second_work->save($data));
		$this->assertEqual($second_slug, $second_work->slug);
		
		$work->delete();
		$second_work->delete();
	}
	
	public function testCreateCollectionWithNoTitle() {
		$work = Works::create(array(
			"title" => "",
			"artist" => "Artwork Artist"
		));
		
		$this->assertFalse($work->save());
	}


}

?>
