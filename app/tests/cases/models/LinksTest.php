<?php

namespace app\tests\cases\models;

use app\models\Links;
use app\models\Works;
use app\models\WorksLinks;

class LinksTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
	
		Links::all()->delete();
		Works::all()->delete();
		WorksLinks::all()->delete();
	}

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

	public function testDeletingLinks() {

		$data = array(
			'title' => 'Artwork Title for a Link',
			'url' => 'http://example.com'
		);

		$link = Links::create();
		$success = $link->save($data);

		$this->assertTrue($success);

		$link_count = Links::count();
		$this->assertEqual(1, $link_count);

		$link_id = $link->id;
		$work_id = '1';

		$work_link = WorksLinks::create();
		$success = $work_link->save(compact($work_id, $link_id));

		$this->assertTrue($success);

		$work_link_count = WorksLinks::count();

		$this->assertEqual(1, $work_link_count);
		
	}

}

?>
