<?php

namespace app\tests\cases\models;

use app\models\Formats;

class FormatsTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}

	public function testFormats() {
	
		$expected_count = '983';
		
		$format_data[0] = array('id' => '781', 'extension' => 'gif', 'mime_type' => 'image/gif');
		$format_data[1] = array('id' => '783', 'extension' => 'jpeg', 'mime_type' => 'image/jpeg');
		$format_data[2] = array('id' => '787', 'extension' => 'png', 'mime_type' => 'image/png');
		$format_data[3] = array('id' => '792', 'extension' => 'tiff', 'mime_type' => 'image/tiff');
		$format_data[4] = array('id' => '79', 'extension' => 'pdf', 'mime_type' => 'application/pdf');
		
		$actual_count = Formats::count();
		
		$this->assertNotEqual($actual_count, 0, "The Formats table is empty!");
		
		$this->assertEqual($expected_count, $actual_count, "The database does not contain the expected number of formats.\nexpected: $expected_count \nresult: $actual_count");
		
		foreach($format_data as $format) {
			$expected_format = Formats::create($format);
			
			$actual_format = Formats::find('first', array(
				'conditions' => array('id' => $expected_format->id)
			));
			
			$this->assertNotEqual($actual_format, NULL);
			
			if($actual_format) {
			
			$this->assertIdentical($actual_format->extension, $expected_format->extension);
			$this->assertIdentical($actual_format->mime_type, $expected_format->mime_type);
			
			}
			
		}
		
	}

}

?>
