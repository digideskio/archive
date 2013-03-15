<?php

namespace app\tests\integration;

use app\models\Architectures;
use app\models\ArchitecturesHistories;

use app\models\Archives;
use app\models\ArchivesHistories;

class ArchitecturesHistoriesTest extends \lithium\test\Integration {

	public function setUp() {
		Architectures::find("all")->delete();
		ArchitecturesHistories::find("all")->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

		$architecture = Architectures::create();
		$data = array(
			'title' => 'Building Title',
			'architect' => 'The Architect',
			'creation_number' => 'A001',
			'client' => 'The Client',
			'project_lead' => 'Project Lead',
			'consultants' => 'The Consultants',
			'partners' => 'The Partners',
			'address' => 'The Address',
			'location' => 'Somewhere',
			'city' => 'Some City',
			'country' => 'Some Country',
			'status' => 'completed',
			'materials' => 'materials',
			'techniques' => 'techniques',
			'annotation' => 'annotation',
			'area' => '5000',
			'grounds' => '12000',
			'interior' => '3000',
			'height' => '20',
			'stories' => '2',
			'rooms' => '5',
			'measurement_remarks' => 'None',
			'attributes'=> '{"A":1,"B":2}',
			'remarks' => 'Some Remarks',
		);

		$architecture->save($data);
	}

	public function tearDown() {

		Architectures::find("all")->delete();
		ArchitecturesHistories::find("all")->delete();

		Archives::find("all")->delete();
		ArchivesHistories::find("all")->delete();

	}

	public function testInsertHistory() {
		
		$architecture = Architectures::first();

		$architecture_history = ArchitecturesHistories::first();

		$this->assertEqual($architecture->title, $architecture_history->title);
		$this->assertEqual($architecture->architect, $architecture_history->architect);
		$this->assertEqual($architecture->creation_number, $architecture_history->creation_number);
		$this->assertEqual($architecture->client, $architecture_history->client);
		$this->assertEqual($architecture->project_lead, $architecture_history->project_lead);
		$this->assertEqual($architecture->consultants, $architecture_history->consultants);
		$this->assertEqual($architecture->partners, $architecture_history->partners);
		$this->assertEqual($architecture->address, $architecture_history->address);
		$this->assertEqual($architecture->location, $architecture_history->location);
		$this->assertEqual($architecture->city, $architecture_history->city);
		$this->assertEqual($architecture->country, $architecture_history->country);
		$this->assertEqual($architecture->status, $architecture_history->status);
		$this->assertEqual($architecture->materials, $architecture_history->materials);
		$this->assertEqual($architecture->techniques, $architecture_history->techniques);
		$this->assertEqual($architecture->annotation, $architecture_history->annotation);
		$this->assertEqual($architecture->area, $architecture_history->area);
		$this->assertEqual($architecture->grounds, $architecture_history->grounds);
		$this->assertEqual($architecture->interior, $architecture_history->interior);
		$this->assertEqual($architecture->height, $architecture_history->height);
		$this->assertEqual($architecture->stories, $architecture_history->stories);
		$this->assertEqual($architecture->rooms, $architecture_history->rooms);
		$this->assertEqual($architecture->measurement_remarks, $architecture_history->measurement_remarks);
		$this->assertEqual($architecture->attributes, $architecture_history->attributes);
		$this->assertEqual($architecture->remarks, $architecture_history->remarks);

		$this->assertNull($architecture_history->end_date);

		$count = ArchitecturesHistories::count();

		$this->assertEqual(1, $count);
	
	}

	public function testUpdateHistory() {
	
		$architecture = Architectures::first();
		$data = array(
			'title' => 'New Title'
		);

		$architecture->save($data); 

		$count = ArchitecturesHistories::count();

		$this->assertEqual(2, $count);

		$architecture_history = ArchitecturesHistories::find('first', array (
			'conditions' => array(
				'end_date' => NULL
			)
		));

		$this->assertEqual($architecture->title, $architecture_history->title);

	}

	public function testDeleteHistory() {
		$architecture = Architectures::first();
		$architecture->delete();

		$count = ArchitecturesHistories::count();

		$this->assertEqual(1, $count);

		$architecture_history = ArchitecturesHistories::first();

		$this->assertTrue($architecture_history->end_date);

	}

}

?>
