<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\Artwork;

use app\models\Works;
use app\models\Archives;

class ArtworkTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}

	public function testCaptions() {

		$data = array(
			'title' => 'Artwork Title'
		);

		$work = Works::create($data);
		$archive = Archives::create($data);

		$helper = new Artwork();

		$caption = $helper->caption($archive, $work);

		$this->assertEqual('<em>Artwork Title</em>.', $caption);

		$data['artist'] = 'Artist Name';

		$work = Works::create($data);
		$caption = $helper->caption($archive, $work);

		$this->assertEqual('Artist Name, <em>Artwork Title</em>.', $caption);

		$data['earliest_date'] = '2004-05-15';

		$archive = Archives::create($data);
		$caption = $helper->caption($archive, $work);

		$this->assertEqual('Artist Name, <em>Artwork Title</em>, 2004.', $caption); 

		$data['latest_date'] = '2005-02-20';

		$archive = Archives::create($data);
		$caption = $helper->caption($archive, $work);

		$this->assertEqual('Artist Name, <em>Artwork Title</em>, 2004–2005.', $caption); 

		$data['height'] = 40;
		$data['width'] = 50;

		$work = Works::create($data);
		$caption = $helper->caption($archive, $work);

		$this->assertEqual('Artist Name, <em>Artwork Title</em>, 2004–2005, 40 × 50 cm.', $caption);

	}

}

?>
