<?php

namespace app\tests\cases\models;

use app\models\PublicationsLinks;
use app\models\Links;

class PublicationsLinksTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
		PublicationsLinks::all()->delete();
		Links::all()->delete();
	}

	public function testCreatePublicationsLinks() {

		$link_data = array(
			'title' => 'Link Title',
			'url' => 'http://example.org'
		);

		$link = Links::create();
		$success = $link->save($link_data);

		$this->assertTrue($success);

		$link_id = $link->id;

		$data = array(
			'publication_id' => '1',
			'link_id' => $link_id
		);

		$publication_link = PublicationsLinks::create();
		$success = $publication_link->save($data);

		$this->assertTrue($success, "The publication link could not be saved");

		$links_count = Links::count();
		$this->assertEqual(1, $links_count);

		$publications_links_count = PublicationsLinks::count();
		$this->assertEqual(1, $publications_links_count);

		$data = array(
			'publication_id' => '1',
			'url' => 'http://example.com'
		);

		$publication_link = PublicationsLinks::create();
		$success = $publication_link->save($data);

		$this->assertTrue($success, "The publication link could not be saved");

		$link->delete();

		$links_count = Links::count();
		$this->assertEqual(1, $links_count);

		$publications_links_count = PublicationsLinks::count();
		$this->assertEqual(1, $publications_links_count);

		Links::all()->delete();

		$links_count = Links::count();
		$this->assertEqual(0, $links_count);

		$publications_links_count = PublicationsLinks::count();
		$this->assertEqual(0, $publications_links_count);
	}


}

?>
