<?php

namespace app\models;

class ArchitecturesHistories extends \app\models\Architectures {

	public $belongsTo = array("Architectures");
	
	public $hasOne = array( 
		'ArchivesHistories' => array (
			'to' => 'app\models\ArchivesHistories',
			'key' => array(
				'start_date' => 'start_date',
				'architecture_id' => 'archive_id'
	)));

}

?>
