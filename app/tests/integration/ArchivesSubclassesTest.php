<?php

namespace app\tests\integration;

use app\models\Archives;
use app\models\ArchivesHistories;

class ArchivesSubclassesTest extends \lithium\test\Integration {

	public function setUp() {

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

	}

	public function tearDown() {

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

	}

}

?>
