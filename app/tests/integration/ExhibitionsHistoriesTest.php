<?php

namespace app\tests\integration;

use app\models\Exhibitions;
use app\models\ExhibitionsHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

class ExhibitionsHistoriesTest extends \lithium\test\Integration {

	public function setUp() {

		Exhibitions::find("all")->delete();
		ExhibitionsHistories::find("all")->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		$exhibition = Exhibitions::create();
		$data = array(
			'id' => '1',
			'remarks' => 'Some Remarks',
			'annotation' => 'AnAnnotation',
			'attributes'=> '{"x":5,"y":6}',
			'address' => '123 Somewhere',
			'location' => 'A Location',
			'city'=> 'The City',
			'country' => 'The Country',
			'venue' => 'The Venue',
			'organizer' => 'The Organizer',
			'curator' => 'The Curator',
			'sponsor' => 'The Sponsor',
		);

		$exhibition->save($data);

	}

	public function tearDown() {

		Exhibitions::find("all")->delete();
		ExhibitionsHistories::find("all")->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

	}

	public function testInsertHistory() {

		$exhibition = Exhibitions::first();

		$this->assertTrue(!empty($exhibition));

		$exhibition_history = ExhibitionsHistories::first();

		$this->assertEqual($exhibition->remarks, $exhibition_history->remarks);
		$this->assertEqual($exhibition->annotation, $exhibition_history->annotation);
		$this->assertEqual($exhibition->attributes, $exhibition_history->attributes);
		$this->assertEqual($exhibition->address, $exhibition_history->address);
		$this->assertEqual($exhibition->location, $exhibition_history->location);
		$this->assertEqual($exhibition->city, $exhibition_history->city);
		$this->assertEqual($exhibition->country, $exhibition_history->country);
		$this->assertEqual($exhibition->venue, $exhibition_history->venue);
		$this->assertEqual($exhibition->organizer, $exhibition_history->organizer);
		$this->assertEqual($exhibition->curator, $exhibition_history->curator);
		$this->assertEqual($exhibition->sponsor, $exhibition_history->sponsor);

		$this->assertNull($exhibition_history->end_date);

		$count = ExhibitionsHistories::count();

		$this->assertEqual(1, $count);

	}

	public function testUpdateHistory() {
	
		$exhibition = Exhibitions::first();
		$data = array(
			'venue' => 'New Venue'
		);

		$exhibition->save($data); 

		$count = ExhibitionsHistories::count();

		$this->assertEqual(2, $count);

		$exhibition_history = ExhibitionsHistories::find('first', array (
			'conditions' => array(
				'end_date' => NULL
			)
		));

		$this->assertEqual($exhibition->venue, $exhibition_history->venue);

	}

	public function testDeleteHistory() {
		$exhibition = Exhibitions::first();
		$exhibition->delete();

		$count = ExhibitionsHistories::count();

		$this->assertEqual(1, $count);

		$exhibition_history = ExhibitionsHistories::first();

		$this->assertTrue(!empty($exhibition_history->end_date));

	}
}

?>
