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

		$caption = $helper->caption($archive, $work, array('link' => true));

		$this->assertEqual('Artist Name, <em><a href="/works/view/">Artwork Title</a></em>, 2004–2005, 40 × 50 cm.', $caption);

	}

	public function testArtists() {

		$helper = new Artwork();

		$data = array(
			'artist' => 'The Artist'
		);

		$work = Works::create($data);

		$artists = $helper->artists($work->archive, $work);

		$this->assertEqual($data['artist'], $artists);

		$data = array(
			'artist' => 'The Artist',
			'artist_native_name' => '艺术家'
		);

		$work = Works::create($data);

		$artists = $helper->artists($work->archive, $work);

		$this->assertEqual("{$data['artist']} ({$data['artist_native_name']})", $artists);

	}

}

?>
