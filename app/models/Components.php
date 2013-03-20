<?php

namespace app\models;

class Components extends \lithium\data\Model {

	public $belongsTo = array(
		'Albums' => array(
			'to' => 'app\models\Albums',
			'key' => array(
				'archive_id1' => 'id'
		)),
		'Works' => array(
			'key' => 'archive_id2'
		)
	);

	public $validates = array();

}

?>
