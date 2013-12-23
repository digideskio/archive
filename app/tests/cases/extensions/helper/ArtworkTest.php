<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\Artwork;

use app\models\Works;
use app\models\Archives;

use \lithium\template\helper\Html;

class ArtworkTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}

	public function testCaptions() {

		$data = array(
			'name' => 'Artwork Title'
		);

		$work = Works::create($data);
		$work->archive = Archives::create($data);

		$helper = new Artwork();

		$caption = $helper->caption($work);

		$this->assertEqual('<em>Artwork Title</em>.', $caption);

		$data['artist'] = 'Artist Name';

		$work = Works::create($data);
		$work->archive = Archives::create($data);
		$caption = $helper->caption($work);

		$this->assertEqual('Artist Name, <em>Artwork Title</em>.', $caption);

		$data['earliest_date'] = '2004-05-15';

		$work = Works::create($data);
		$work->archive = Archives::create($data);
		$caption = $helper->caption($work);

		$this->assertEqual('Artist Name, <em>Artwork Title</em>, 2004.', $caption); 

		$data['latest_date'] = '2005-02-20';

		$work = Works::create($data);
		$work->archive = Archives::create($data);
		$caption = $helper->caption($work);

		$this->assertEqual('Artist Name, <em>Artwork Title</em>, 2004–2005.', $caption); 

		$data['height'] = 40;
		$data['width'] = 50;

		$work = Works::create($data);
		$work->archive = Archives::create($data);
		$caption = $helper->caption($work);

		$this->assertEqual('Artist Name, <em>Artwork Title</em>, 2004–2005, 40 × 50 cm.', $caption);

		$caption = $helper->caption($work, array('link' => true));

		$this->assertEqual('Artist Name, <em><a href="/works/view/">Artwork Title</a></em>, 2004–2005, 40 × 50 cm.', $caption);

	}

}

?>
