<?php

namespace app\tests\cases\models;

use app\models\Exhibitions;

class ExhibitionsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}
	
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
