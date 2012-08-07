<?php

namespace app\tests\cases\models;

use app\models\Dates;

class DatesTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}
	
	public function testYears() {
	
		$start_date_unique = "1999-11-12 12:13:14";
		$end_date_unique   = "2001-03-04 15:16:17";
		$data = array (
			"start" => $start_date_unique,
			"end" => $end_date_unique
		);
	
		$date = Dates::create($data);
		
		$this->assertEqual('1999–2001', $date->years());
	
		$start_date_same_year = "2010-01-23 12:13:14";
		$end_date_same_year   = "2010-03-04 15:16:17";
		$data = array (
			"start" => $start_date_same_year,
			"end" => $end_date_same_year
		);
	
		$date = Dates::create($data);
		
		$this->assertEqual('2010', $date->years());
		
	}

	public function testValidDates() {
		$start_date_complete = "1999-11-12 12:13:14";
		$end_date_complete   = "2001-03-04 15:16:17";
		
		$start_date_Ymd_input    = "1999-11-12";
		$start_date_Ymd_expected = "1999-11-12 00:00:00";
		
		$end_date_Ymd_input    = "2001-03-04";
		$end_date_Ymd_expected = "2001-03-04 00:00:00";
		
		$start_date_Y_input    = '1999';
		$start_date_Y_expected = '1999-01-01 00:00:00';
		
		$end_date_Y_input    = '2000';
		$end_date_Y_expected = '2000-01-01 00:00:00';
		
		$start_date_FY_input    = 'February 1999';
		$start_date_FY_expected = '1999-02-01 00:00:00';
		
		$start_date_MY_input    = 'Feb 2001';
		$start_date_MY_expected = '2001-02-01 00:00:00';
	
		$date = Dates::create();
		$data = array (
			"start" => $start_date_complete,
			"end" => $end_date_complete
		);
		
		$date->save($data);
		
		$date = Dates::first();
		
		$this->assertEqual($start_date_complete, $date->start);
		$this->assertEqual($end_date_complete, $date->end);
		
		$date->delete();
		
		$date = Dates::create();
		$data = array (
			"start" => $start_date_Ymd_input,
			"end" => $end_date_Ymd_input
		);
		
		$date->save($data);
		
		$date = Dates::first();
		
		$this->assertEqual($start_date_Ymd_expected, $date->start);
		$this->assertEqual($end_date_Ymd_expected, $date->end);
		
		$date->delete();
		
		$date = Dates::create();
		$data = array (
			"start" => $start_date_Y_input,
			"end" => $end_date_Y_input
		);
		
		$date->save($data);
		
		$date = Dates::first();
		
		$this->assertEqual($start_date_Y_expected, $date->start, "If the user input the date $start_date_Y_input, it should be saved as $start_date_Y_expected, but it was saved as $date->start");
		$this->assertEqual($end_date_Y_expected, $date->end, "If the user input the date $end_date_Y_input, it should be saved as $end_date_Y_expected, but it was saved as $date->end");
		
		$date->delete();
		
		$date = Dates::create();
		$data = array (
			"title" => "Artwork Title",
			"start" => $start_date_FY_input,
		);
		
		$date->save($data);
		
		$date = Dates::first();
		
		$this->assertEqual($start_date_FY_expected, $date->start, "If the user input the date $start_date_FY_input, it should be saved as $start_date_FY_expected, but it was saved as $date->start");
		
		$date->delete();
		
		$date = Dates::create();
		$data = array (
			"title" => "Artwork Title",
			"start" => $start_date_MY_input,
		);
		
		$date->save($data);
		
		$date = Dates::first();
		
		$this->assertEqual($start_date_MY_expected, $date->start, "If the user input the date $start_date_MY_input, it should be saved as $start_date_MY_expected, but it was saved as $date->start");
		
		$date->delete();
		
	}
	
	public function testInvalidDates() {
		
		$date = Dates::create();
		$data = array (
			"start" => 'X',
			"end" => 'Y'
		);
		
		//$this->assertFalse($date->save($data), "The date was able to be saved with an invalid date.");
		
		//$date->delete();
		
	
	}

}

?>
