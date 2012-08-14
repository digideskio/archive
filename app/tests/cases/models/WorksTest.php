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
	
	public function testCreateWithNoTitle() {
		$work = Works::create();
		$data = array (
			"title" => "",
			"artist" => "Artwork Artist"
		);
		
		$this->assertFalse($work->save($data));
	}
	
	public function testValidDates() {
		$early_date_complete = "1999-11-12 12:13:14";
		$later_date_complete = "2001-03-04 15:16:17";
		
		$early_date_Ymd_input = "1999-11-12";
		$early_date_Ymd_expected = "1999-11-12";
		
		$later_date_Ymd_input = "2001-03-04";
		$later_date_Ymd_expected = "2001-03-04";
		
		$early_date_Y_input = '1999';
		$early_date_Y_expected = '1999-01-01';
		
		$later_date_Y_input = '2000';
		$later_date_Y_expected = '2000-01-01';
		
		$early_date_FY_input = 'February 1999';
		$early_date_FY_expected = '1999-02-01';
		
		$early_date_MY_input = 'Feb 2001';
		$early_date_MY_expected = '2001-02-01';
	
		$work = Works::create();
		$data = array (
			"title" => "Artwork Title",
			"earliest_date" => $early_date_complete,
			"latest_date" => $later_date_complete
		);
		
		$work->save($data);
		
		$work = Works::first();
		
		$this->assertEqual($early_date_Ymd_input, $work->earliest_date);
		$this->assertEqual($later_date_Ymd_input, $work->latest_date);
		
		$work->delete();
		
		$work = Works::create();
		$data = array (
			"title" => "Artwork Title",
			"earliest_date" => $early_date_Ymd_input,
			"latest_date" => $later_date_Ymd_input
		);
		
		$work->save($data);
		
		$work = Works::first();
		
		$this->assertEqual($early_date_Ymd_expected, $work->earliest_date);
		$this->assertEqual($later_date_Ymd_expected, $work->latest_date);
		
		$work->delete();
		
		$work = Works::create();
		$data = array (
			"title" => "Artwork Title",
			"earliest_date" => $early_date_Y_input,
			"latest_date" => $later_date_Y_input
		);
		
		$work->save($data);
		
		$work = Works::first();
		
		$this->assertEqual($early_date_Y_expected, $work->earliest_date, "If the user input the date $early_date_Y_input, it should be saved as $early_date_Y_expected, but it was saved as $work->earliest_date");
		$this->assertEqual($later_date_Y_expected, $work->latest_date, "If the user input the date $later_date_Y_input, it should be saved as $later_date_Y_expected, but it was saved as $work->latest_date");
		
		$work->delete();
		
		$work = Works::create();
		$data = array (
			"title" => "Artwork Title",
			"earliest_date" => $early_date_FY_input,
		);
		
		$work->save($data);
		
		$work = Works::first();
		
		$this->assertEqual($early_date_FY_expected, $work->earliest_date, "If the user input the date $early_date_FY_input, it should be saved as $early_date_FY_expected, but it was saved as $work->earliest_date");
		
		$work->delete();
		
		$work = Works::create();
		$data = array (
			"title" => "Artwork Title",
			"earliest_date" => $early_date_MY_input,
		);
		
		$work->save($data);
		
		$work = Works::first();
		
		$this->assertEqual($early_date_MY_expected, $work->earliest_date, "If the user input the date $early_date_MY_input, it should be saved as $early_date_MY_expected, but it was saved as $work->earliest_date");
		
		$work->delete();
		
	}
	
	public function testInvalidDates() {
		
		$work = Works::create();
		$data = array (
			"title" => "Artwork Title",
			"earliest_date" => 'X',
			"latest_date" => 'Y'
		);
		
		//$this->assertFalse($work->save($data), "The artwork was able to be saved with an invalid date.");
		
		//$work->delete();
		
	
	}


}

?>
