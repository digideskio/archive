<?php

namespace app\tests\integration;

use app\models\Archives;
use app\models\ArchivesHistories;

use app\models\Documents;
use app\models\ArchivesDocuments;

use app\models\Albums;
use app\models\AlbumsHistories;

use app\models\Exhibitions;
use app\models\ExhibitionsHistories;

use app\models\Works;
use app\models\WorksHistories;

use app\models\Persons;
use app\models\PersonsHistories;

use app\models\Architectures;
use app\models\ArchitecturesHistories;

use app\models\Publications;
use app\models\PublicationsHistories;

use lithium\data\Model;

class ArchivesDocumentsTest extends \lithium\test\Integration {

	public function setup() {

		$document = Documents::create();
		$data = array(
			'title' => 'The Title',
			'hash' => '1234',
			'format_id' => '783',
			'slug' => 'the-title',
		);

		Documents::connection()->create("INSERT INTO documents (title, hash, format_id, slug) VALUES ('The Title', '1234', '783', 'the-title')");
	}

	public function tearDown() {
		Documents::find("all")->delete();
		ArchivesDocuments::find("all")->delete();

		Albums::find("all")->delete();
		AlbumsHistories::find("all")->delete();

		Exhibitions::find("all")->delete();
		ExhibitionsHistories::find("all")->delete();

		Works::find("all")->delete();
		WorksHistories::find("all")->delete();

		Persons::find("all")->delete();
		PersonsHistories::find("all")->delete();

		Architectures::find("all")->delete();
		ArchitecturesHistories::find("all")->delete();

		Publications::find("all")->delete();
		PublicationsHistories::find("all")->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();
	}

	public function testArchivesDocuments() {

		$document= Documents::first();

		$archive_document = ArchivesDocuments::create();
		$ad_data = array(
			'archive_id' => '12345',
			'document_id' => $document->id,
		);

		$archive_document->save($ad_data);

		$this->assertEqual(1, ArchivesDocuments::count());

		$document->delete();

		$this->assertEqual(0, ArchivesDocuments::count());


	}

	public function testWorksDocuments() {

		$document= Documents::first();

		// Create an archive and architecture pair for testing purposes
		$archive_data = array(
			'name' => 'Artwork Title',
			'controller' => 'works'
		);
		$archive = Archives::create();
		$archive->save($archive_data);

		$work = Works::create();
		$work_data = array(
			'id' => $archive->id,
			'architect' => 'The Architect',
		);

		$work->save($work_data);

		$archive_document = ArchivesDocuments::create();
		$ad_data = array(
			'archive_id' => $work->id,
			'document_id' => $document->id,
		);

		$archive_document->save($ad_data);

		$this->assertEqual(1, ArchivesDocuments::count());

		$work->delete();

		$this->assertEqual(0, ArchivesDocuments::count());

	}

	public function testArchitecturesDocuments() {

		$document = Documents::first();

		// Create an archive and architecture pair for testing purposes
		$archive_data = array(
			'name' => 'Architecture Title',
			'controller' => 'architectures'
		);
		$archive = Archives::create();
		$archive->save($archive_data);

		$architecture = Architectures::create();
		$arc_data = array(
			'id' => $archive->id,
			'architect' => 'The Architect',
		);

		$architecture->save($arc_data);

		$archive_document = ArchivesDocuments::create();
		$ad_data = array(
			'archive_id' => $architecture->id,
			'document_id' => $document->id,
		);

		$archive_document->save($ad_data);

		$this->assertEqual(1, ArchivesDocuments::count());

		$architecture->delete();

		$this->assertEqual(0, ArchivesDocuments::count());

	}

	public function testPersonsDocuments() {

		$document = Documents::first();

		//Create an archive and person pair for testing purposes
		$archive_data = array(
			'name' => 'Person Name',
			'controller' => 'artists'
		);
		$archive = Archives::create();
		$archive->save($archive_data);

		$person = Persons::create(array(
			'id' => $archive->id,
			'biography' => 'Person Biography'
		));

		$person->save();

		$archive_document = ArchivesDocuments::create();
		$ad_data = array(
			'archive_id' => $person->id,
			'document_id' => $document->id,
		);

		$archive_document->save($ad_data);

		$this->assertEqual(1, ArchivesDocuments::count());

		$person->delete();

		$this->assertEqual(0, ArchivesDocuments::count());

	}

	public function testAlbumsDocuments() {

		$document = Documents::first();

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

		$archive_document = ArchivesDocuments::create();
		$ad_data = array(
			'archive_id' => $album->id,
			'document_id' => $document->id,
		);

		$archive_document->save($ad_data);

		$this->assertEqual(1, ArchivesDocuments::count());

		$album->delete();

		$this->assertEqual(0, ArchivesDocuments::count());

	}

	public function testExhibitionsDocuments() {

		$document = Documents::first();

		// Create an archive and architecture pair for testing purposes
		$archive_data = array(
			'name' => 'Exhibition Title',
			'controller' => 'exhibitions'
		);
		$archive = Archives::create();
		$archive->save($archive_data);

		$exhibit = Exhibitions::create();
		$ex_data = array(
			'id' => $archive->id
		);

		$exhibit->save($ex_data);

		$archive_document = ArchivesDocuments::create();
		$ad_data = array(
			'archive_id' => $exhibit->id,
			'document_id' => $document->id,
		);

		$archive_document->save($ad_data);

		$this->assertEqual(1, ArchivesDocuments::count());

		$exhibit->delete();

		$this->assertEqual(0, ArchivesDocuments::count());

	}

	public function testPublicationsDocuments() {

		$document = Documents::first();

		// Create an archive and architecture pair for testing purposes
		$archive_data = array(
			'name' => 'Publication Title',
			'controller' => 'publications'
		);
		$archive = Archives::create();
		$archive->save($archive_data);

		$pub = Publications::create();
		$pub_data = array(
			'id' => $archive->id,
		);

		$pub->save($pub_data);

		$archive_document = ArchivesDocuments::create();
		$ad_data = array(
			'archive_id' => $pub->id,
			'document_id' => $document->id,
		);

		$archive_document->save($ad_data);

		$this->assertEqual(1, ArchivesDocuments::count());

		$pub->delete();

		$this->assertEqual(0, ArchivesDocuments::count());

	}
}

?>
