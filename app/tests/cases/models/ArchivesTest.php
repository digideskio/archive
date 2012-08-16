<?php

namespace app\tests\cases\models;

use app\models\Archives;
use app\tests\mocks\data\MockArchives;

use app\models\Works;

class ArchivesTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}

	public function testYears() {

		$start_date_unique = "1999-11-12";
		$end_date_unique   = "2001-03-04";
		$data = array (
			"earliest_date" => $start_date_unique,
			"latest_date" => $end_date_unique
		);

		$archive = Works::create($data);

		$this->assertEqual('1999–2001', $archive->years());

		$start_date_same_year = "2010-01-23";
		$end_date_same_year   = "2010-03-04";
		$data = array (
			"earliest_date" => $start_date_same_year,
			"latest_date" => $end_date_same_year
		);

		$archive = Works::create($data);

		$this->assertEqual('2010', $archive->years());

	}

	public function testDates() {
		$data = array(
			"earliest_date" => "2011-12-01",
			"latest_date" => "2012-01-15"
		);

		$archive = Works::create($data);

		$dates = $archive->dates();

		$this->assertEqual("01 Dec, 2011 – 15 Jan, 2012", $dates);

		$data = array(
			"earliest_date" => "2012-01-01",
			"latest_date" => "2012-02-15"
		);

		$archive = Works::create($data);

		$dates = $archive->dates();

		$this->assertEqual("01 Jan – 15 Feb, 2012", $dates);

		$data = array(
			"earliest_date" => "2012-02-01",
			"latest_date" => "2012-02-15"
		);

		$archive = Works::create($data);

		$dates = $archive->dates();

		$this->assertEqual("01 – 15 Feb, 2012", $dates);

		$data = array(
			"earliest_date" => "",
			"latest_date" => ""
		);

		$archive = Works::create($data);

		$dates = $archive->dates() ?: '';

		$this->assertEqual('', $dates);

	}

}

?>
