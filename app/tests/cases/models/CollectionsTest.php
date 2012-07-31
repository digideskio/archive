<?php

namespace app\tests\cases\models;

use app\models\Collections;

class CollectionsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}
	
	public function testCreateCollection() {
		$collection = Collections::create();
		$data = array (
			"title" => "Collection Title",
			"description" => "This is the Collection Description"
		);
		
		$slug = "Collection-Title";
		
		$this->assertTrue($collection->save($data));
		$this->assertEqual($slug, $collection->slug);
		
		$second_collection = Collections::create();
		$second_slug = "Collection-Title-2";
		
		$this->assertTrue($second_collection->save($data));
		$this->assertEqual($second_slug, $second_collection->slug);
		
		$collection->delete();
		$second_collection->delete();
	}
	
	public function testCreateCollectionWithNoTitle() {
		$collection = Collections::create();
		$data = array (
			"title" => "",
			"description" => "This is the Collection Description"
		);
		
		$this->assertFalse($collection->save($data));
	}


}

?>
