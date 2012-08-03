<?php

namespace app\tests\cases\models;

use app\models\Exhibitions;

class ExhibitionsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}
	
	public function testSlugs() {
	
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
	
	public function testSelection() {
		$exhibition = Exhibitions::create();
		$data = array(
			"title" => "The Somewhat Long and Exaggerated Title",
			"venue" => "The Venue That is Also Long and Exagerated",
			"earliest_date" => "Dec 1 2011",
			"latest_date" => "Jan 15, 2012"
		);
	
		$exhibition->save($data);
		
		$selection = $exhibition->selection();
		
		$this->assertEqual("2011 · The Somewhat Long and Exaggera… · The Venue That is Also Long an…", $selection);
		
		$exhibition->delete();
		$exhibition = Exhibitions::create();
		$data = array(
			"title" => "The Somewhat Long and Exaggerated Title",
			"earliest_date" => "Dec 1 2011",
			"latest_date" => "Jan 15, 2012"
		);
	
		$exhibition->save($data);
		
		$selection = $exhibition->selection();
		
		$this->assertEqual("2011 · The Somewhat Long and Exaggera…", $selection);
		
		$exhibition->delete();

	
	}
	
	public function testDates() {
		$exhibition = Exhibitions::create();
		$data = array(
			"title" => "Exhibition Title",
			"earliest_date" => "Dec 1 2011",
			"latest_date" => "Jan 15, 2012"
		);
	
		$exhibition->save($data);
	
		$dates = $exhibition->dates();
	
		$this->assertEqual("01 Dec, 2011 – 15 Jan, 2012", $dates);
	
		$data = array(
			"earliest_date" => "Jan 1 2012",
			"latest_date" => "Feb 15, 2012"
		);
	
		$exhibition->save($data);
	
		$dates = $exhibition->dates();
	
		$this->assertEqual("01 Jan – 15 Feb, 2012", $dates);
	
		$data = array(
			"earliest_date" => "Feb 1 2012",
			"latest_date" => "Feb 15, 2012"
		);
	
		$exhibition->save($data);
	
		$dates = $exhibition->dates();
	
		$this->assertEqual("01 – 15 Feb, 2012", $dates);
	
		$data = array(
			"earliest_date" => "",
			"latest_date" => ""
		);
	
		$exhibition->save($data);
		
		$exhibition = Exhibitions::first();
	
		$dates = $exhibition->dates() ?: '';
	
		$this->assertEqual('', $dates);
	
		$exhibition->delete();
	
	}
	
	public function testCreateExhibitionWithNoTitle() {
		$exhibition = Exhibitions::create();
		$data = array (
			"title" => "",
		);
		
		$this->assertFalse($exhibition->save($data));
	}


}

?>
