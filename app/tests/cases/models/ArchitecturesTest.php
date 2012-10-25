<?php

namespace app\tests\cases\models;

use app\models\Architectures;

class ArchitecturesTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
	
		Architectures::all()->delete();
		
	}


	public function testCreateArchitecture() {
		$architecture = Architectures::create();
		$data = array (
			"title" => "Architecture Title",
		);

		$slug = "Architecture-Title";

		$this->assertTrue($architecture->save($data));
		$this->assertEqual($slug, $architecture->slug);

		$second_arch = Architectures::create();
		$second_slug = "Architecture-Title-1";

		$this->assertTrue($second_arch->save($data));
		$this->assertEqual($second_slug, $second_arch->slug);

	}

	public function testCreateArchitectureWithNoTitle() {
		$architecture = Architectures::create();
		$data = array (
			"title" => "",
		);

		$this->assertFalse($architecture->save($data));
	}

}

?>
