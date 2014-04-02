<?php

namespace app\tests\cases\models;

use app\models\Documents;
use app\tests\mocks\data\MockDocuments;

use lithium\core\Libraries;

class DocumentsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {
		Documents::all()->delete();
	}

	public function testCreateDocument() {
		// Get the path to a sample PDF
		$file_name = 'Sample-Document.pdf';
		$test_path = Libraries::get(true, 'path') . '/tests/resources/' . $file_name;

		$tmp_documents = Libraries::get(true, 'resources') . '/tmp/documents/';

		if (!file_exists($tmp_documents)) {
			@mkdir($tmp_documents, 0777, true);
		}

		$file_path = $tmp_documents . $file_name;

		// Copy the file into the resources directory
		copy($test_path, $file_path);

		$doc = Documents::create();
		$doc->save(compact('file_name', 'file_path'));

		$this->assertEqual('Sample-Document', $doc->title);
		$this->assertEqual('Sample-Document', $doc->slug);

		// Look up the document with the format
		$document = Documents::find('first', array(
			'with' => 'Formats',
			'conditions' => array('slug' => $doc->slug)
		));

		$this->assertEqual('pdf', $document->format->extension);
		$this->assertNotEqual('0000-00-00 00:00:00', $document->date_created, 'The document date created is not set');
		$this->assertNotEqual('0000-00-00 00:00:00', $document->date_modified, 'The document date modified is not set');

		// Given a file path, findDocumentIdByFile() should be able to match a physical file
		// on disk with its record in the database. This means our original test file should
		// match the record that was created for this document.
		$compare_doc_id = Documents::findDocumentIdByFile($test_path);
		$this->assertEqual($compare_doc_id, $document->id);

		$file = $document->file();
		$small = $document->file(array('size' => 'small'));
		$thumb = $document->file(array('size' => 'thumb'));

		$this->assertTrue(file_exists($tmp_documents . $file));
		$this->assertTrue(file_exists($tmp_documents . $small));
		$this->assertTrue(file_exists($tmp_documents . $thumb));

		unlink($tmp_documents . $file);
		unlink($tmp_documents . $small);
		unlink($tmp_documents . $thumb);

		rmdir($tmp_documents . 'small');
		rmdir($tmp_documents . 'thumb');
		rmdir($tmp_documents);
	}
	public function testCreateImage() {
		// Get the path to a sample image
		$file_name = 'Sample-Image.jpg';
		$width = '540';
		$height = '696';
		$test_path = Libraries::get(true, 'path') . '/tests/resources/' . $file_name;

		$tmp_documents = Libraries::get(true, 'resources') . '/tmp/documents/';

		if (!file_exists($tmp_documents)) {
			@mkdir($tmp_documents, 0777, true);
		}

		$file_path = $tmp_documents . $file_name;

		// Copy the image into the resources directory
		copy($test_path, $file_path);

		$doc = Documents::create();
		$doc->save(compact('file_name', 'file_path'));

		$this->assertEqual('Sample-Image', $doc->title);
		$this->assertEqual('Sample-Image', $doc->slug);
		$this->assertEqual($height, $doc->height);
		$this->assertEqual($width, $doc->width);

		// Look up the document with the format
		$document = Documents::find('first', array(
			'with' => 'Formats',
			'conditions' => array('slug' => $doc->slug)
		));

		$this->assertEqual('jpeg', $document->format->extension);
		$this->assertNotEqual('0000-00-00 00:00:00', $document->date_created, 'The document date created is not set');
		$this->assertNotEqual('0000-00-00 00:00:00', $document->date_modified, 'The document date modified is not set');

		// Given a file path, findDocumentIdByFile() should be able to match a physical file
		// on disk with its record in the database. This means our original test file should
		// match the record that was created for this document.
		$compare_doc_id = Documents::findDocumentIdByFile($test_path);
		$this->assertEqual($compare_doc_id, $document->id);

		$file = $document->file();
		$small = $document->file(array('size' => 'small'));
		$thumb = $document->file(array('size' => 'thumb'));

		$this->assertTrue(file_exists($tmp_documents . $file));
		$this->assertTrue(file_exists($tmp_documents . $small));
		$this->assertTrue(file_exists($tmp_documents . $thumb));

		unlink($tmp_documents . $file);
		unlink($tmp_documents . $small);
		unlink($tmp_documents . $thumb);

		rmdir($tmp_documents . 'small');
		rmdir($tmp_documents . 'thumb');
		rmdir($tmp_documents);
	}

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
