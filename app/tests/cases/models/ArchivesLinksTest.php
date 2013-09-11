<?php

namespace app\tests\cases\models;

use app\models\ArchivesLinks;
use app\models\Links;

class ArchivesLinksTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
		ArchivesLinks::all()->delete();
		Links::all()->delete();
	}

	public function testCreateArchiveLinks() {

		$link_data = array(
			'title' => 'Link Title',
			'url' => 'http://example.org'
		);

		$link = Links::create();
		$success = $link->save($link_data);

		$this->assertTrue($success);

		$link_id = $link->id;

		$data = array(
			'archive_id' => '1',
			'link_id' => $link_id
		);

		$archive_link = ArchivesLinks::create();
		$success = $archive_link->save($data);

		$this->assertTrue($success, "The archive link could not be saved");

		$links_count = Links::count();
		$this->assertEqual(1, $links_count);

		$archives_links_count = ArchivesLinks::count();
		$this->assertEqual(1, $archives_links_count);

		$data = array(
			'archive_id' => '1',
			'url' => 'http://example.com'
		);

		$archive_link = ArchivesLinks::create();
		$success = $archive_link->save($data);

		$this->assertTrue($success, "The archive link could not be saved");

		$link->delete();

		$links_count = Links::count();
		$this->assertEqual(1, $links_count);

		$archives_links_count = ArchivesLinks::count();
		$this->assertEqual(1, $archives_links_count);

		Links::all()->delete();

		$links_count = Links::count();
		$this->assertEqual(0, $links_count);

		$archives_links_count = ArchivesLinks::count();
		$this->assertEqual(0, $archives_links_count);
	}

}

?>
