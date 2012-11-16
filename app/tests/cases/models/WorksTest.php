<?php

namespace app\tests\cases\models;

use app\models\Works;
use app\models\WorksHistories;
use app\models\WorksLinks;
use app\models\Links;

class WorksTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
	
		Works::all()->delete();
		WorksHistories::all()->delete();
		Links::all()->delete();
		WorksLinks::all()->delete();

	}
	
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
		$second_slug = "Artwork-Title-1";
		
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
		
		$this->assertFalse($work->save($data), "The artwork was able to be saved with an invalid date.");
		
	}

	public function testDimensions() {

		$work = Works::create();

		$this->assertFalse($work->dimensions());

		$data = array(
			'height' => 40,
			'width' => 50
		);

		$work = Works::create($data);

		$this->assertEqual("40 × 50 cm", $work->dimensions());

		$data['depth'] = '25';

		$work = Works::create($data);

		$this->assertEqual("40 × 50 × 25 cm", $work->dimensions());

		unset($data['width']);
		unset($data['depth']);

		$data['diameter'] = 30;

		$work = Works::create($data);

		$this->assertEqual("40 cm, Ø 30 cm", $work->dimensions());

		$data = array(
			'running_time' => '1 hour 50 minutes'
		);

		$work = Works::create($data);

		$this->assertEqual("1 hour 50 minutes", $work->dimensions());

	}

	public function testCaptions() {

		$data = array(
			'title' => 'Artwork Title'
		);

		$work = Works::create($data);

		$this->assertEqual('<em>Artwork Title</em>.', $work->caption());

		$data['artist'] = 'Artist Name';

		$work = Works::create($data);

		$this->assertEqual('Artist Name, <em>Artwork Title</em>.', $work->caption());

		$data['earliest_date'] = '2004-05-15';

		$work = Works::create($data);

		$this->assertEqual('Artist Name, <em>Artwork Title</em>, 2004.', $work->caption()); 

		$data['latest_date'] = '2005-02-20';

		$work = Works::create($data);

		$this->assertEqual('Artist Name, <em>Artwork Title</em>, 2004–2005.', $work->caption()); 

		$data['height'] = 40;
		$data['width'] = 50;

		$work = Works::create($data);

		$this->assertEqual('Artist Name, <em>Artwork Title</em>, 2004–2005, 40 × 50 cm.', $work->caption());
	
	}

	public function testLinks() {
		
		$data = array(
			'title' => 'Artwork Title',
			'url' => 'http://example.com'
		);

		$work = Works::create();

		$success = $work->save($data);

		$this->assertTrue($success);

		$link = Links::first();
		$link_count = Links::count();

		$this->assertTrue($link);
		$this->assertEqual(1, $link_count);

		$work_link = WorksLinks::first();
		$work_link_count = WorksLinks::count();

		$this->assertTrue($work_link);
		$this->assertEqual(1, $work_link_count);

		$this->assertEqual($work->id, $work_link->work_id);
		$this->assertEqual($link->id, $work_link->link_id);

		$new_data = array(
			'title' => 'Another Titlte',
			'url' => 'http://example.com'
		);

		$new_work = Works::create();

		$success = $new_work->save($new_data);

		$this->assertTrue($success);

		$new_link_count = Links::count();

		$this->assertEqual(1, $new_link_count);

		$new_works_links_count = WorksLinks::count();

		$this->assertEqual(2, $new_works_links_count);

		$new_work_link = WorksLinks::find('first', array(
			'conditions' => array('work_id' => $new_work->id)
		));

		$this->assertEqual($new_work_link->link_id, $link->id);

		$new_work->delete();
	
		$after_delete_links_count = Links::count();

		$this->assertEqual(1, $after_delete_links_count);

		$after_delete_works_links_count = WorksLinks::count();

		$this->assertEqual(1, $after_delete_works_links_count);

		$work->delete();

		$final_delete_links_count = Links::count();

		$this->assertEqual(1, $final_delete_links_count);

		$final_delete_works_links_count = WorksLinks::count();

		$this->assertEqual(0, $final_delete_works_links_count);

	}


}

?>
