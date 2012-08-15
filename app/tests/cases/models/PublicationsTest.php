<?php

namespace app\tests\cases\models;

use app\models\Publications;

class PublicationsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}

	public function testCreatePublication() {
		$publication = Publications::create();
		$data = array (
			"title" => "Publication Title",
		);

		$slug = "Publication-Title";

		$this->assertTrue($publication->save($data));
		$this->assertEqual($slug, $publication->slug);

		$second_pub = Publications::create();
		$second_slug = "Publication-Title-1";

		$this->assertTrue($second_pub->save($data));
		$this->assertEqual($second_slug, $second_pub->slug);

		$publication->delete();
		$second_pub->delete();
	}

	public function testCreatePublicationWithNoTitle() {
		$publication = Publications::create();
		$data = array (
			"title" => "",
		);

		$this->assertFalse($publication->save($data));
	}

}

?>
