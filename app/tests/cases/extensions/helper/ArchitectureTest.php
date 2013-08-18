<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\Architecture;

use app\models\Architectures;
use app\models\Archives;

class ArchitectureTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}

	public function testCaptions() {

		$data = array(
			'title' => 'Building Title'
		);

		$architecture = Architectures::create($data);
		$archive = Archives::create($data);

		$helper = new Architecture();

		$caption = $helper->caption($archive, $architecture);

		$this->assertEqual('<em>Building Title</em>.', $caption);

		$data['architect'] = 'Architect Name';

		$architecture = Architectures::create($data);
		$caption = $helper->caption($archive, $architecture);

		$this->assertEqual('Architect Name, <em>Building Title</em>.', $caption);

		$data['earliest_date'] = '2006-02-22';

		$archive = Archives::create($data);
		$caption = $helper->caption($archive, $architecture);

		$this->assertEqual('Architect Name, <em>Building Title</em>, 2006.', $caption); 

		$data['latest_date'] = '2007-06-05';

		$archive = Archives::create($data);
		$caption = $helper->caption($archive, $architecture);

		$this->assertEqual('Architect Name, <em>Building Title</em>, 2006–2007.', $caption); 

		$data['location'] = 'Somewhere';
		$data['city'] = 'City';
		$data['country'] = 'Country';

		$architecture = Architectures::create($data);
		$caption = $helper->caption($archive, $architecture);

		$this->assertEqual('Architect Name, <em>Building Title</em>, 2006–2007, Somewhere, City, Country.', $caption); 

		$data['status'] = 'completed';

		$architecture = Architectures::create($data);
		$caption = $helper->caption($archive, $architecture);

		$this->assertEqual('Architect Name, <em>Building Title</em>, 2006–2007, Somewhere, City, Country, (completed).', $caption); 

	}

}

?>
