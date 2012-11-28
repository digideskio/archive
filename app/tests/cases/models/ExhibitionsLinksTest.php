<?php

namespace app\tests\cases\models;

use app\models\ExhibitionsLinks;
use app\models\Links;

class ExhibitionsLinksTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
		ExhibitionsLinks::all()->delete();
		Links::all()->delete();
	}

	public function testCreateWorkLinks() {

		$link_data = array(
			'title' => 'Link Title',
			'url' => 'http://example.org'
		);

		$link = Links::create();
		$success = $link->save($link_data);

		$this->assertTrue($success);

		$link_id = $link->id;

		$data = array(
			'exhibition_id' => '1',
			'link_id' => $link_id
		);

		$exhibition_link = ExhibitionsLinks::create();
		$success = $exhibition_link->save($data);

		$this->assertTrue($success, "The exhibition link could not be saved");

		$links_count = Links::count();
		$this->assertEqual(1, $links_count);

		$exhibitions_links_count = ExhibitionsLinks::count();
		$this->assertEqual(1, $exhibitions_links_count);

		$data = array(
			'exhibition_id' => '1',
			'url' => 'http://example.com'
		);

		$exhibition_link = ExhibitionsLinks::create();
		$success = $exhibition_link->save($data);

		$this->assertTrue($success, "The exhibition link could not be saved");

		$link->delete();

		$links_count = Links::count();
		$this->assertEqual(1, $links_count);

		$exhibitions_links_count = ExhibitionsLinks::count();
		$this->assertEqual(1, $exhibitions_links_count);

		Links::all()->delete();

		$links_count = Links::count();
		$this->assertEqual(0, $links_count);

		$exhibitions_links_count = ExhibitionsLinks::count();
		$this->assertEqual(0, $exhibitions_links_count);
	}

}

?>
