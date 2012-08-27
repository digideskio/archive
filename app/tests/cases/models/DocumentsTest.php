<?php

namespace app\tests\cases\models;

use app\models\Documents;

class DocumentsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}

	public function testResolution() {
		$data = array(
			'width' => 1920,
			'height' => 1080
		);

		$document = Documents::create($data);

		$this->assertEqual("1920 × 1080 px", $document->resolution());

		$this->assertEqual("16.26 × 9.14 cm @ 300 dpi", $document->size());

		$this->assertEqual("48.77 × 27.43 cm @ 100 dpi", $document->size(array('dpi' => 100)));

		$document = Documents::create();

		$this->assertEqual("No resolution is set on this document", $document->resolution());

		$this->assertEqual("No print size is set on this document", $document->size());


	}
	
}

?>
