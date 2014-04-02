<?php

namespace app\models;

class PublicationsHistories extends \app\models\Publications {

	public $belongsTo = array("Publications");

	public $hasOne = array(
		'ArchivesHistories' => array (
			'to' => 'app\models\ArchivesHistories',
			'key' => array(
				'start_date' => 'start_date',
				'publication_id' => 'archive_id'
	)));

}

?>
