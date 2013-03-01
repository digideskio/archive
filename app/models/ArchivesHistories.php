<?php

namespace app\models;

class ArchivesHistories extends \app\models\Archives {

	public $belongsTo = array("Archives", "Users");

	public $hasOne = array(
		'WorksHistories' => array (
			'to' => 'app\models\WorksHistories',
			'key' => array(
				'start_date' => 'start_date',
				'archive_id' => 'work_id'
	)));

	public $validates = array();
}

?>
