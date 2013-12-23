<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\Artwork;

use app\models\Works;
use app\models\WorksHistories;
use app\models\Archives;
use app\models\ArchivesHistories;
use app\models\Persons;
use app\models\PersonsHistories;
use app\models\Components;
use app\models\ComponentsHistories;

use \lithium\template\helper\Html;

class ArtworkTest extends \lithium\test\Unit {

	public function setUp() {
		// Create an archive and work pair for testing purposes
		$archive_data = array(
			'name' => 'First Artwork Title',
			'controller' => 'works',
			'earliest_date' => '2004-05-15',
			'latest_date' => '2005-02-20',
			'earliest_date_format' => 'YYYY-MM-DD',
			'latest_date_format' => 'YYYY-MM-DD'
		);
		$archive = Archives::create();
		$archive->save($archive_data);

		$work = Works::create(array(
			'id' => $archive->id,
			'materials' => 'The Materials',
			'height' => 40,
			'width' => 50
		));

		$success = $work->save();

		// Create an artist for testing purposes
		$first_artist = Archives::create();
		$first_artist->save(array(
			'name' => 'First Artist Name',
			'controller' => 'artists',
			'category' => 'Artist'
		));
		$first_person = Persons::create();
		$first_person->save(array(
			'id' => $first_artist->id
		));

		// Associate the artwork with the artist
		$persons_works = Components::create();
		$persons_works->save(array(
			'archive_id1' => $first_artist->id,
			'archive_id2' => $work->id,
			'category' => 'persons_works',
			'role' => 'artist'
		));

	}

	public function tearDown() {
	
		Works::all()->delete();
		WorksHistories::all()->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		Persons::find("all")->delete();
		PersonsHistories::find("all")->delete();

		Components::find("all")->delete();
		ComponentsHistories::find("all")->delete();
	
	}

	public function testWorksCaption() {
		$work = Works::first();

		$helper = new Artwork();

		$caption = $helper->caption($work);

		$this->assertEqual('40 × 50 cm.', $caption);
	}

	public function testWorksArchivesCaption() {
		$work = Works::find('first', array(
			'with' => 'Archives',
		));

		$helper = new Artwork();

		$caption = $helper->caption($work);

		$this->assertEqual('<em>First Artwork Title</em>, 2004–2005, 40 × 50 cm.', $caption);

	}

	public function testArtworksCaption() {
		$works = Works::find('artworks');

		$work = $works->first();

		$helper = new Artwork();

		$caption = $helper->caption($work);

		$this->assertEqual('<em>First Artwork Title</em>, 2004–2005, 40 × 50 cm.', $caption);

	}
}

?>
