<?php

namespace app\tests\integration;

use app\models\Packages;
use app\models\Archives;
use app\models\Albums;

use li3_filesystem\extensions\storage\FileSystem; 

class PackagesTest extends \lithium\test\Unit {

	public function setUp() {

		$data = array(
			'title' => 'Album Title',
			'filesystem' => 'secure'
		);

		$album = Albums::create();
		$album->save($data);

	}
	
	public function tearDown() {

		Packages::find('all')->delete();
		Archives::find('all')->delete();
		Albums::find('all')->delete();

	}

	public function testAlbumsPackages() {

		/*$album = Albums::find('first', array(
			'with' => 'Archives',
			'conditions' => array('slug' => 'Album-Title')
		));

		$this->assertTrue($album);*/

		$data = array(
			'album_id' => '1',
			'slug' => 'Album-Title',
			'filesystem' => 'secure'
		);

		$package = Packages::create();

		$success = $package->save($data);

		$this->assertTrue($success);

		$filename = 'Album-Title' . '_' . date("Y-m-d_His") . ".zip";

		$this->assertEqual($filename, $package->name);

		//TODO Test what is inside the package	

	}

}

?>
