<?php

namespace app\tests\integration;

use app\models\Archives;
use app\models\ArchivesHistories;

use app\models\Publications;
use app\models\PublicationsHistories;

use app\models\Exhibitions;
use app\models\ExhibitionsHistories;

class ArchivesSubclassesTest extends \lithium\test\Integration {

	public function setUp() {

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		Publications::find("all")->delete();
		PublicationsHistories::find("all")->delete();

		Exhibitions::find("all")->delete();
		ExhibitionsHistories::find("all")->delete();

	}

	public function tearDown() {

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		Publications::find("all")->delete();
		PublicationsHistories::find("all")->delete();

		Exhibitions::find("all")->delete();
		ExhibitionsHistories::find("all")->delete();

	}

	public function testPublicationsSubclass() {

		$publication = Publications::create();
		$data = array(
			'title' => 'The Title',
			'author' => 'The Author',
			'classification' => 'Monograph',
			'catalog_level' => 'series',
			'earliest_date' => 'February 2002'
		);

		$slug = 'The-Title';

		$this->assertTrue($publication->save($data));

		$archive = Archives::first();

		$this->assertEqual($publication->id, $archive->id);

		$this->assertEqual($data['classification'], $archive->classification);
		$this->assertEqual('publications', $archive->controller);

		$archive = Archives::find('first', array(
			'with' => 'Publications'	
		));

		$this->assertEqual($archive->id, $archive->publication->id);
		$this->assertEqual($slug, $archive->slug);

		$publication = Publications::find('first', array(
			'with' => 'Archives'
		));

		$this->assertEqual($publication->id, $publication->archive->id);
		$this->assertEqual($slug, $publication->archive->slug);

		$publication = Publications::find('first', array(
			'with' => 'Archives',
			'conditions' => array('Archives.slug' => $slug)
		));

		$this->assertTrue(!empty($publication));

		$this->assertEqual('series', $publication->archive->catalog_level);
		$this->assertEqual('2002', $publication->archive->years());

		$data['catalog_level'] = 'item';

		$publication->save($data);

		$publication = Publications::find('first', array(
			'with' => 'Archives'
		));

		$this->assertEqual('item', $publication->archive->catalog_level);

		$count = Publications::count();

		$this->assertEqual('1', $count);

		$count = Archives::count();

		$this->assertEqual('1', $count);

		$publication->delete();

		$count = Archives::count();

		$this->assertEqual('0', $count);

	}

	public function testExhibitionsSubclass() {

		$exhibition = Exhibitions::create();
		$data = array(
			'title' => 'The Title',
			'curator' => 'The Curator',
			'type' => 'Group Show',
			'catalog_level' => 'item',
			'earliest_date' => 'March 2012'
		);

		$slug = 'The-Title';

		$this->assertTrue($exhibition->save($data));

		$archive = Archives::first();

		$this->assertEqual($exhibition->id, $archive->id);

		$this->assertEqual($data['type'], $archive->type);
		$this->assertEqual('exhibitions', $archive->controller);

		$archive = Archives::find('first', array(
			'with' => 'Exhibitions'	
		));

		$this->assertEqual($archive->id, $archive->exhibition->id);
		$this->assertEqual($slug, $archive->slug);

		$exhibition = Exhibitions::find('first', array(
			'with' => 'Archives'
		));

		$this->assertEqual($exhibition->id, $exhibition->archive->id);
		$this->assertEqual($slug, $exhibition->archive->slug);

		$exhibition = Exhibitions::find('first', array(
			'with' => 'Archives',
			'conditions' => array('Archives.slug' => $slug)
		));

		$this->assertTrue(!empty($exhibition));

		$this->assertEqual('item', $exhibition->archive->catalog_level);
		$this->assertEqual('2012', $exhibition->archive->years());

		$data['catalog_level'] = 'travelling exhibition';

		$exhibition->save($data);

		$exhibition = Exhibitions::find('first', array(
			'with' => 'Archives'
		));

		$this->assertEqual('travelling exhibition', $exhibition->archive->catalog_level);

		$count = Exhibitions::count();

		$this->assertEqual('1', $count);

		$count = Archives::count();

		$this->assertEqual('1', $count);

		$exhibition->delete();

		$count = Archives::count();

		$this->assertEqual('0', $count);

	}
}

?>
