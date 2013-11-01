<?php

namespace app\tests\integration;

use app\models\Components;
use app\models\ComponentsHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

use app\models\Albums;
use app\models\AlbumsHistories;

use app\models\Exhibitions;
use app\models\ExhibitionsHistories;

use app\models\Works;
use app\models\WorksHistories;

class ArchivesComponentsTest extends \lithium\test\Integration {

	public function setup() {

		//Create an archive and work pair for testing purposes
		$archive_data = array(
			'title' => 'The Artwork Title',
			'controller' => 'works'
		);
		$archive = Archives::create();
		$archive->save($archive_data);

		$work = Works::create(array(
			'id' => $archive->id,
			'materials' => 'The Materials'
		));

		$success = $work->save();

	}

	public function tearDown() {
		Components::find("all")->delete();
		ComponentsHistories::find("all")->delete();

		Albums::find("all")->delete();
		AlbumsHistories::find("all")->delete();

		Exhibitions::find("all")->delete();
		ExhibitionsHistories::find("all")->delete();

		Works::find("all")->delete();
		WorksHistories::find("all")->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();
	}

	public function testWorksComponents() {

		$work = Works::first();

		$archive_id1 = '123';
		$archive_id2 = $work->id;

		$component = Components::create();
		$comp_data = compact('archive_id1', 'archive_id2');

		$component->save($comp_data);

		$this->assertEqual(1, Components::count());

		$find_work = Works::find('first', array(
			'with' => 'Components'
		));

		$comp = $find_work->components->first();

		$this->assertEqual($archive_id1, $comp->archive_id1);
		$this->assertEqual($archive_id2, $comp->archive_id2);

		$work->delete();

		$this->assertEqual(0, Components::count());
	
	}

	public function testAlbumsComponents() {

		//Create an archive and album pair for testing purposes
		$archive_data = array(
			'title' => 'Album Title',
			'controller' => 'albums'
		);
		$archive = Archives::create();
		$archive->save($archive_data);

		$album = Albums::create(array(
			'id' => $archive->id,
			'remarks' => 'Album Description'
		));

		$album->save();

		$work = Works::first();

		$archive_id1 = $album->id;
		$archive_id2 = $work->id;

		$type = 'albums_works';

		$component = Components::create();
		$comp_data = compact('archive_id1', 'archive_id2', 'type');

		$component->save($comp_data);

		$find_album = Albums::find('first', array(
			'with' => 'Components'
		));

		$comp = $find_album->components->first();

		$this->assertEqual($album->id, $comp->archive_id1);
		$this->assertEqual($work->id, $comp->archive_id2);

		$album->delete();

		$this->assertEqual(0, Components::count());

	}

	public function testExhibitionsComponents() {

		//Create an archive and exhibition pair for testing purposes
		$archive_data = array(
			'title' => 'Exhibition Title',
			'controller' => 'exhibitions'
		);
		$archive = Archives::create();
		$archive->save($archive_data);

		$exhibition = Exhibitions::create();
		$ex_data = array(
			'id' => $archive->id,
			'remarks' => 'some words',
		);

		$exhibition->save($ex_data);

		$work = Works::first();

		$archive_id1 = $exhibition->id;
		$archive_id2 = $work->id;

		$type = 'exhibitions_works';

		$component = Components::create();
		$comp_data = compact('archive_id1', 'archive_id2', 'type');

		$component->save($comp_data);

		$find_exhibition = Exhibitions::find('first', array(
			'with' => 'Components'
		));

		$comp = $find_exhibition->components->first();

		$this->assertEqual($exhibition->id, $comp->archive_id1);
		$this->assertEqual($work->id, $comp->archive_id2);

		$exhibition->delete();

		$this->assertEqual(0, Components::count());
	}

}

?>
