<?php

namespace app\tests\integration;

use app\models\Links;
use app\models\Works;
use app\models\Exhibitions;
use app\models\Publications;
use app\models\WorksLinks;
use app\models\ExhibitionsLinks;
use app\Models\PublicationsLinks;

class ModelsLinksTest extends \lithium\test\Integration {

	public function setUp() {}

	public function tearDown() {
		Links::find("all")->delete();
		Works::find("all")->delete();
		Exhibitions::find("all")->delete();
		Publications::find("all")->delete();
		WorksLinks::find("all")->delete();
		ExhibitionsLinks::find("all")->delete();
		PublicationsLinks::find("all")->delete();
	}

	public function testDuplicateLinksFromModels() {

		$data = array(
			'title' => 'Title',
			'url' => 'http://example.org'
		);

		$work = Works::create();

		$this->assertTrue($work->save($data));
		
		$link_count = Links::count();
		$this->assertEqual(1, $link_count);

		$exhibition = Exhibitions::create();

		$this->assertTrue($exhibition->save($data));
		
		$link_count = Links::count();
		$this->assertEqual(1, $link_count);

		$publication = Publications::create();

		$this->assertTrue($publication->save($data));
		
		$link_count = Links::count();
		$this->assertEqual(1, $link_count);

		$work = Works::create();

		$this->assertTrue($work->save($data));

		$link_count = Links::count();
		$this->assertEqual(1, $link_count);
	}
}

?>
