<?php

namespace app\tests\cases\models;

use app\models\Packages;

use li3_filesystem\extensions\storage\FileSystem; 

class PackagesTest extends \lithium\test\Unit {

	public function setUp() {}
	
	public function tearDown() {}

	public function testValidation() {
	
		$data = array(
			'album_id' => '',
			'filesystem' => 'secure',
			'slug' => 'slug'
		);

		$package = Packages::create($data);

		$this->assertFalse($package->validates());

		$this->assertFalse($package->save($data));

		$data = array(
			'album_id' => '1',
			'filesystem' => '',
			'slug' => 'slug'
		);

		$package = Packages::create($data);

		$this->assertFalse($package->validates());

		$this->assertFalse($package->save($data));
		
		$data = array(
			'album_id' => '1',
			'filesystem' => 'secure',
			'slug' => ''
		);

		$package = Packages::create($data);

		$this->assertFalse($package->validates());

		$this->assertFalse($package->save($data));
		
	}

	public function testBasics() {

		$filename = 'Album-Name_2013-03-04_152800.zip';

		$data = array(
			'id' => '0',
			'album_id' => '10',
			'name' => $filename,
			'filesystem' => 'secure',
			'date_created' => '2013-03-04 15:28:00',
			'date_modified' => '2013-03-04 15:28:00'
		);

		$package = Packages::create($data);

		$config = FileSystem::config('secure');

		$url = $config['url'];

		$this->assertEqual("$url/Album-Name_2013-03-04_152800.zip", $package->url());

		$this->assertEqual($config['path'] . DIRECTORY_SEPARATOR . $filename, $package->path());

		$this->assertEqual($config['path'], $package->directory());
	}

}

?>
