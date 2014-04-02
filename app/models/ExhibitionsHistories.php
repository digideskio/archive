<?php

namespace app\models;

class ExhibitionsHistories extends \app\models\Exhibitions {

	public $belongsTo = array("Exhibitions");

	public $hasOne = array(
		'ArchivesHistories' => array (
			'to' => 'app\models\ArchivesHistories',
			'key' => array(
				'start_date' => 'start_date',
				'exhibition_id' => 'archive_id'
	)));

}

?>
