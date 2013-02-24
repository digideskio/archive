<?php

namespace app\tests\cases\models;

use app\models\Archives;
use app\models\ArchivesHistories;
use app\tests\mocks\data\MockArchives;

class ArchivesTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
	
		Archives::all()->delete();
		ArchivesHistories::all()->delete();

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

	public function testCreateWithNoTitle() {
		$archive = Archives::create();
		$data = array (
			"name" => "",
			"classification" => "Artwork"
		);
		
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
			"earliest_date_format" => "Y",
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

}

?>
