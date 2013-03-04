<?php

namespace app\models;

class AlbumsHistories extends \app\models\Albums {

	public $belongsTo = array("Albums");
	
	public $hasOne = array( 
		'ArchivesHistories' => array (
			'to' => 'app\models\ArchivesHistories',
			'key' => array(
				'start_date' => 'start_date',
				'album_id' => 'archive_id'
	)));

}

?>
