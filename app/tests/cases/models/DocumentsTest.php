<?php

namespace app\tests\cases\models;

use app\models\Documents;
use app\tests\mocks\data\MockDocuments;

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

	public function testView() {
		
		$thumb = 'thumb/Title-One.jpeg';
		$small = 'small/Title-One.jpeg';
		$download = 'download/Title-One.tiff';

		$data = array(
			'id' => 1,
			'title' => 'Title One',
			'hash' => 'c4ca4238a0b923820dcc509a6f75849b',
			'slug' => 'Title-One',
			'format_id' => 792, //tiff
		);

		$document = Documents::create($data);

		$this->assertEqual($thumb, $document->view());

		$this->assertEqual($thumb, $document->view(array(
			'action' => 'thumb'
		)));

		$this->assertEqual($small, $document->view(array(
			'action' => 'small'
		)));

		$this->assertEqual($download, $document->view(array(
			'action' => 'download'
		)));
	}

	public function testFile() {

		$thumb = 'thumb/c4ca4238a0b923820dcc509a6f75849b.jpeg';
		$small = 'small/c4ca4238a0b923820dcc509a6f75849b.jpeg';
		$original = 'c4ca4238a0b923820dcc509a6f75849b.tiff';
		
		$data = array(
			'id' => 1,
			'title' => 'Title One',
			'hash' => 'c4ca4238a0b923820dcc509a6f75849b',
			'slug' => 'Title-One',
			'format_id' => 792, //tiff
		);

		$document = Documents::create($data);

		$this->assertEqual($original, $document->file());

		$this->assertEqual($thumb, $document->file(array(
			'size' => 'thumb'
		)));

		$this->assertEqual($small, $document->file(array(
			'size' => 'small'
		)));

		$this->assertEqual($thumb, $document->file(array(
			'size' => 'INVALID'
		)));

	}
	
}

?>
