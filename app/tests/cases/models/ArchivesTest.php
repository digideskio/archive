<?php

namespace app\tests\cases\models;

use app\models\Archives;
use app\models\ArchivesHistories;
use app\tests\mocks\data\MockArchives;

use lithium\util\Validator;

class ArchivesTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
	
		Archives::all()->delete();
		ArchivesHistories::all()->delete();

	}

	public function testSave() {

		$data = array (
			"name" => "Archive Title",
		);
		$archive = Archives::create($data);

		$this->assertTrue($archive->validates());

		$this->assertTrue($archive->save($data));
	}
	
	public function testSlugs() {
		$archive = Archives::create();
		$data = array (
			"name" => "Archive Title",
		);
		
		$slug = "Archive-Title";
		
		$this->assertTrue($archive->save($data));

		$this->assertEqual($slug, $archive->slug);
		
		$second_archive = Archives::create();
		$second_slug = "Archive-Title-1";
		
		$this->assertTrue($second_archive->save($data));
		$this->assertEqual($second_slug, $second_archive->slug);
		
		$archive->delete();
		$second_archive->delete();
	}

	public function testSlugsAfterSave() {
		$archive = Archives::create();
		$data = array (
			"name" => "Archive Name",
		);

		$archive->save($data);

		$slug = "Archive-Name";

		$first = Archives::first();

		$this->assertTrue(!empty($first->slug), 'The slug for the archive should be created and saved');
		$this->assertEqual($slug, $first->slug);
	}


	public function testCreateWithNoTitle() {
		$data = array (
			"name" => "",
			"classification" => "Artwork"
		);
		$archive = Archives::create($data);

		$this->assertFalse($archive->validates());
		
		$this->assertFalse($archive->save($data));
	}
	

	public function testYears() {

		$start_date_unique = "1999-11-12";
		$end_date_unique   = "2001-03-04";
		$data = array (
			"earliest_date" => $start_date_unique,
			"latest_date" => $end_date_unique,
			"earliest_date_format" => "Y-m-d",
			"latest_date_format" => "Y-m-d"
		);

		$archive = Archives::create($data);

		$this->assertEqual('1999–2001', $archive->years());

		$start_date_same_year = "2010-01-23";
		$end_date_same_year   = "2010-03-04";
		$data = array (
			"earliest_date" => $start_date_same_year,
			"latest_date" => $end_date_same_year
		);

		$archive = Archives::create($data);

		$this->assertEqual('2010', $archive->years());

	}

	public function testDates() {
		$data = array(
			"earliest_date" => "2011-12-01",
			"latest_date" => "2012-01-15",
			"earliest_date_format" => "Y-m-d",
			"latest_date_format" => "Y-m-d"
		);

		$archive = Archives::create($data);

		$dates = $archive->dates();

		$this->assertEqual("01 Dec 2011 – 15 Jan 2012", $dates);

		$data = array(
			"earliest_date" => "2012-01-01",
			"latest_date" => "2012-02-15",
			"earliest_date_format" => "Y-m-d",
			"latest_date_format" => "Y-m-d"
		);

		$archive = Archives::create($data);

		$dates = $archive->dates();

		$this->assertEqual("01 Jan – 15 Feb 2012", $dates);

		$data = array(
			"earliest_date" => "2012-02-01",
			"latest_date" => "2012-02-15",
			"earliest_date_format" => "Y-m-d",
			"latest_date_format" => "Y-m-d"
		);

		$archive = Archives::create($data);

		$dates = $archive->dates();

		$this->assertEqual("01 – 15 Feb 2012", $dates);

		$data = array(
			"earliest_date" => "2012-02-01",
			"latest_date" => "2012-02-01",
			"earliest_date_format" => "Y-m-d",
			"latest_date_format" => "Y-m-d"
		);

		$archive = Archives::create($data);

		$dates = $archive->dates();

		$this->assertEqual("01 Feb 2012", $dates);

		$data = array(
			"earliest_date" => "",
			"latest_date" => ""
		);

		$archive = Archives::create($data);

		$dates = $archive->dates() ?: '';

		$this->assertEqual('', $dates);

	}

	public function testDateFormats() {
		
		$data = array(
			"earliest_date" => "2012-02-01",
			"earliest_date_format" => "Y",
		);

		$archive = Archives::create($data);

		$dates = $archive->dates();

		$this->assertEqual("2012", $dates);

		$data = array(
			"earliest_date" => "2010-01-01",
			"earliest_date_format" => "Y",
			"latest_date" => "2012-01-01",
			"latest_date_format" => "Y",
		);

		$archive = Archives::create($data);

		$dates = $archive->dates();

		$this->assertEqual("2010 – 2012", $dates);

		$data = array(
			"earliest_date" => "2013-02-01",
			"earliest_date_format" => "M Y",
		);

		$archive = Archives::create($data);

		$dates = $archive->dates();

		$this->assertEqual("Feb 2013", $dates);

		$data = array(
			"earliest_date" => "2010-02-01",
			"earliest_date_format" => "M Y",
			"latest_date" => "2012-03-01",
			"latest_date_format" => "M Y",
		);

		$archive = Archives::create($data);

		$dates = $archive->dates();

		$this->assertEqual("Feb 2010 – Mar 2012", $dates);
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
	
		$data = array (
			"title" => "Artwork Title",
			"earliest_date" => $early_date_Ymd_input,
			"latest_date" => $later_date_Ymd_input
		);
		$archive = Archives::create($data);

		$this->assertTrue($archive->validates());
		
		$archive->save($data);
		
		$archive = Archives::first();
		
		$this->assertEqual($early_date_Ymd_expected, $archive->earliest_date);
		$this->assertEqual($later_date_Ymd_expected, $archive->latest_date);
		$this->assertEqual('Y-m-d', $archive->earliest_date_format);
		$this->assertEqual('Y-m-d', $archive->latest_date_format);
		
		$archive->delete();
		
		$data = array (
			"title" => "Artwork Title",
			"earliest_date" => $early_date_Y_input,
			"latest_date" => $later_date_Y_input
		);
		$archive = Archives::create($data);

		$this->assertTrue($archive->validates());
		
		$archive->save($data);
		
		$archive = Archives::first();
		
		$this->assertEqual($early_date_Y_expected, $archive->earliest_date, "If the user input the date $early_date_Y_input, it should be saved as $early_date_Y_expected, but it was saved as $archive->earliest_date");
		$this->assertEqual($later_date_Y_expected, $archive->latest_date, "If the user input the date $later_date_Y_input, it should be saved as $later_date_Y_expected, but it was saved as $archive->latest_date");
		$this->assertEqual('Y', $archive->earliest_date_format);
		$this->assertEqual('Y', $archive->latest_date_format);
		
		$archive->delete();
		
		$data = array (
			"title" => "Artwork Title",
			"earliest_date" => $early_date_FY_input,
		);
		$archive = Archives::create($data);

		$this->assertTrue($archive->validates());
		
		$archive->save($data);
		
		$archive = Archives::first();
		
		$this->assertEqual($early_date_FY_expected, $archive->earliest_date, "If the user input the date $early_date_FY_input, it should be saved as $early_date_FY_expected, but it was saved as $archive->earliest_date");
		$this->assertEqual('M Y', $archive->earliest_date_format);
		
		$archive->delete();
		
		$data = array (
			"title" => "Artwork Title",
			"earliest_date" => $early_date_MY_input,
		);
		$archive = Archives::create($data);

		$this->assertTrue($archive->validates());
		
		$archive->save($data);
		
		$archive = Archives::first();
		
		$this->assertEqual($early_date_MY_expected, $archive->earliest_date, "If the user input the date $early_date_MY_input, it should be saved as $early_date_MY_expected, but it was saved as $archive->earliest_date");
		$this->assertEqual('M Y', $archive->earliest_date_format);
		
		$archive->delete();
		
	}
	
	public function testInvalidDates() {
		
		$data = array (
			"title" => "Artwork Title",
			"earliest_date" => 'X',
			"latest_date" => 'Y'
		);
		$archive = Archives::create($data);

		$this->assertFalse($archive->validates());
		
		$this->assertFalse($archive->save($data), "The archive was able to be saved with an invalid date.");
		
	}

	public function testValidators() {

		$archive = Archives::create();

		$this->assertFalse(
			$archive->validates(),
			'The archive should not validate with a null title'
		);

		$errors = $archive->errors();

		$this->assertTrue(
			!empty($errors['title']),
			'An archive with a null title should produce an error'
		);

		$this->assertTrue(
			empty($errors['url']),
			'An archive with a null URL should not produce an error'
		);

		$this->assertTrue(
			empty($errors['earliest_date']),
			'An archive with a null earliest date should not produce an error'
		);

		$this->assertTrue(
			empty($errors['latest_date']),
			'An archive with a null latest date should not produce an error'
		);

		$archive = Archives::create(array(
			'title' => '',
			'url' => '',
			'earliest_date' => '',
			'latest_date' => ''
		));

		$this->assertFalse(
			$archive->validates(),
			'The archive should not validate with a blank title'
		);

		$errors = $archive->errors();

		$this->assertTrue(
			!empty($errors['title']),
			'An archive with a blank title should produce an error'
		);

		$this->assertTrue(
			empty($errors['url']),
			'An archive with a blank URL should not produce an error'
		);

		$this->assertTrue(
			empty($errors['earliest_date']),
			'An archive with a blank earliest date should not produce an error'
		);

		$this->assertTrue(
			empty($errors['latest_date']),
			'An archive with a blank latest date should not produce an error'
		);

	}

}

?>
