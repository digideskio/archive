<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\Link;

use app\models\Links;

class LinkTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}

	public function testIsVideo() {
		$www_youtube_com = Links::create(array('url' => 'http://www.youtube.com/'));
		$youtube_com = Links::create(array('url' => 'http://youtube.com/'));
		$youtu_be = Links::create(array('url' => 'http://youtu.be/'));
		$vimeo_com = Links::create(array('url' => 'http://vimeo.com/'));
		$example_com = Links::create(array('url' => 'http://example.com/'));

		$helper = new Link();

		$this->assertTrue($helper->isVideo($www_youtube_com));
		$this->assertTrue($helper->isVideo($youtube_com));
		$this->assertTrue($helper->isVideo($youtu_be));
		$this->assertTrue($helper->isVideo($vimeo_com));
		$this->assertFalse($helper->isVideo($example_com));
	}

}

?>
