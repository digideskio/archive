<?php

namespace app\tests\cases\models;

use app\models\WorksLinks;
use app\models\Links;

class WorksLinksTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
		WorksLinks::all()->delete();
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
			'work_id' => '1',
			'link_id' => $link_id
		);

		$work_link = WorksLinks::create();
		$success = $work_link->save($data);

		$this->assertTrue($success, "The work link could not be saved");

		$links_count = Links::count();
		$this->assertEqual(1, $links_count);

		$works_links_count = WorksLinks::count();
		$this->assertEqual(1, $works_links_count);

		$data = array(
			'work_id' => '1',
			'url' => 'http://example.com'
		);

		$work_link = WorksLinks::create();
		$success = $work_link->save($data);

		$this->assertTrue($success, "The work link could not be saved");

		$link->delete();

		$links_count = Links::count();
		$this->assertEqual(1, $links_count);

		$works_links_count = WorksLinks::count();
		$this->assertEqual(1, $works_links_count);

		Links::all()->delete();

		$links_count = Links::count();
		$this->assertEqual(0, $links_count);

		$works_links_count = WorksLinks::count();
		$this->assertEqual(0, $works_links_count);
	}

}

?>
