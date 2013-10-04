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

	public function testCreateArchivesLinks() {

		$link_data = array(
			'title' => 'Link Title',
			'url' => 'http://example.org'
		);

		$link = Links::create($link_data);

		$this->assertTrue($link->validates());

		$success = $link->save($link_data);

		$this->assertTrue($success);

		$link_id = $link->id;

		$data = array(
			'archive_id' => '1',
			'link_id' => $link_id
		);
		$archive_link = ArchivesLinks::create($link_data);

		$this->assertTrue($archive_link->validates());

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
		$archive_link = ArchivesLinks::create($data);

		$this->assertTrue($archive_link->validates());

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

	public function testValidators() {
		$data = array(
			'archive_id' => '',
			'link_id' => '1'
		);
		$archive_link = ArchivesLinks::create($data);

		$this->assertFalse($archive_link->validates());
	}

	public function testCreateLinkOnSave() {
		$archive_link_data = array(
			'archive_id' => '1',
			'url' => 'http://example.com/fjekod',
			'title' => 'Example Link'
		);

		$archive_link = ArchivesLinks::create();
		$archive_link->save($archive_link_data);

		$links_count = Links::count();
		$this->assertEqual(1, $links_count);

		$link = Links::first();

		$this->assertEqual($archive_link_data['url'], $link->url);
		$this->assertEqual($archive_link_data['title'], $link->title);

	}

	public function testAvoidDuplicateLinks() {
		$archive_link_data = array(
			'archive_id' => '1',
			'url' => 'http://example.com/fjekod',
			'title' => 'Example Link'
		);

		$archive_link = ArchivesLinks::create();
		$archive_link->save($archive_link_data);

		$archive_link_data['archive_id'] = '2';

		$archive_link_2 = ArchivesLinks::create();
		$archive_link_2->save($archive_link_data);

		$links_count = Links::count();
		$this->assertEqual(1, $links_count);

	}

	public function testAvoidDuplicateArchivesLinks() {
		$archive_link_data = array(
			'archive_id' => '1',
			'url' => 'http://example.com/fjekod',
			'title' => 'Example Link'
		);

		$archive_link = ArchivesLinks::create();
		$archive_link->save($archive_link_data);

		$archives_links_count = ArchivesLinks::count();
		$this->assertEqual(1, $archives_links_count);

		$archive_link_2 = ArchivesLinks::create();
		$archive_link_2->save($archive_link_data);

		$archives_links_count = ArchivesLinks::count();
		$this->assertEqual(1, $archives_links_count);
	}

}

?>
