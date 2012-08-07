<?php

namespace app\tests\cases\models;

use app\models\Exhibitions;

class ExhibitionsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}
	
	// FIXME These need to be integration tests. Exhibitions are no longer
	// created separately from Collections
	
	/*public function testSlugs() {
	
		$exhibition = Exhibitions::create();
		$data = array (
			"title" => "Exhibition Title",
			"venue" => "Exhibition Venue"
		);
		
		$slug = "Exhibition-Title-Exhibition-Venue";
		
		$this->assertTrue($exhibition->save($data));
		$this->assertEqual($slug, $exhibition->slug);
	
		$second_exhibition = Exhibitions::create();
		$data = array (
			"title" => "Exhibition Title",
			"venue" => "Exhibition Venue"
		);
		
		$second_slug = "Exhibition-Title-Exhibition-Venue-2";
		
		$this->assertTrue($second_exhibition->save($data));
		$this->assertEqual($second_slug, $second_exhibition->slug);
		
		$exhibition->delete();
		$second_exhibition->delete();
	
		$exhibition = Exhibitions::create();
		
		$data = array (
			"title" => "Exhibition Title",
			"venue" => ""
		);
		
		$slug = "Exhibition-Title";
		
		$this->assertTrue($exhibition->save($data));
		$this->assertEqual($slug, $exhibition->slug);
		
		$exhibition->delete();
		
	}
	
	public function testCreateExhibitionWithNoTitle() {
		$exhibition = Exhibitions::create();
		$data = array (
			"title" => "",
		);
		
		$this->assertFalse($exhibition->save($data));
	}*/


}

?>
