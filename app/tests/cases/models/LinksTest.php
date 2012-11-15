<?php

namespace app\tests\cases\models;

use app\models\Links;

class LinksTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}

	public function testCreateLinksWithNoUrl() {
		$link = Links::create();
		$data = array (
			"url" => "",
		);

		$this->assertFalse($link->save($data));
	}

	public function testCreateLinksWithBadUrl() {
		$link = Links::create();
		$data = array (
			"url" => "http://example com",
		);

		$this->assertFalse($link->save($data));

		$data = array (
			"url" => "example.com",
		);

		$this->assertFalse($link->save($data));
	}

}

?>
