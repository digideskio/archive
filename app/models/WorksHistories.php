<?php

namespace app\models;

class WorksHistories extends \app\models\Works {

	public $belongsTo = array("Works");
	
	public $hasOne = array( 
		'ArchivesHistories' => array (
			'to' => 'app\models\ArchivesHistories',
			'key' => array(
				'start_date' => 'start_date',
				'work_id' => 'archive_id'
	)));

}

?>
