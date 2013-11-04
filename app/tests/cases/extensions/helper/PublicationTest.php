<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\Publication;

use app\models\Publications;
use app\models\Archives;

class PublicationTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}

	public function testCitations() {

		$data = array(
			'name' => 'Book Title',
		);

		$publication = Publications::create($data);
		$archive = Archives::create($data);

		$helper = new Publication();

		$citation = $helper->citation($archive, $publication);

		$this->assertEqual('<em>Book Title</em>.', $citation);

		$data['author'] = 'Author Name';

		$publication = Publications::create($data);
		$citation = $helper->citation($archive, $publication);

		$this->assertEqual('Author Name. <em>Book Title</em>.', $citation);

		$data['earliest_date'] = "2010-01-01";
		$data['earliest_date_format'] = 'Y';

		$archive = Archives::create($data);
		$citation = $helper->citation($archive, $publication);

		$this->assertEqual('Author Name (2010). <em>Book Title</em>.', $citation);

		$data['publisher'] = 'The Publisher';

		$publication = Publications::create($data);
		$citation = $helper->citation($archive, $publication);

		$this->assertEqual('Author Name (2010). <em>Book Title</em>. The Publisher.', $citation);

		$data['pages'] = '21-98';

		$publication = Publications::create($data);
		$citation = $helper->citation($archive, $publication);

		$this->assertEqual('Author Name (2010). <em>Book Title</em>. The Publisher, 21-98.', $citation);

	}

}

?>
