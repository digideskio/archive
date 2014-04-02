<?php

namespace app\tests\integration;

use app\models\Packages;
use app\models\Archives;
use app\models\Albums;

use li3_filesystem\extensions\storage\FileSystem;

class PackagesTest extends \lithium\test\Unit {

	public function setUp() {
		//Create an archive and album pair for testing purposes
		$archive_data = array(
			'name' => 'Album Title',
			'controller' => 'albums'
		);
		$archive = Archives::create();
		$archive->save($archive_data);

		$album = Albums::create(array(
			'id' => $archive->id,
			'remarks' => 'Album Description'
		));

		$album->save();

	}

	public function tearDown() {

		Packages::find('all')->delete();
		Archives::find('all')->delete();
		Albums::find('all')->delete();

	}

	public function testAlbumsPackages() {

		$album = Albums::find('first', array(
			'with' => 'Archives'
		));

		$data = array(
			'album_id' => $album->id,
			'slug' => $album->archive->slug,
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
