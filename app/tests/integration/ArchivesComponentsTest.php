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

		$work = Works::create();
		$data = array(
			'title' => 'The Title',
			'artist' => 'The Artist',
		);

		$work->save($data);

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

	public function testAlbumsComponents() {

		$album = Albums::create();
		$data = array(
			'title' => 'Album Title',
			'description' => 'some words',
		);

		$album->save($data);

		$work = Works::first();

		$archive_id1 = $album->id;
		$archive_id2 = $work->id;

		$type = 'albums_works';

		$component = Components::create();
		$data = compact('archive_id1', 'archive_id2', 'type');

		$component->save($data);

		$find_album = Albums::find('first', array(
			'with' => 'Components'
		));

		$comp = $find_album->components[0];

		$this->assertEqual($album->id, $comp->archive_id1);
		$this->assertEqual($work->id, $comp->archive_id2);
	}

}

?>
