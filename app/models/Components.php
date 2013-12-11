<?php

namespace app\models;

class Components extends \lithium\data\Model {

	public $belongsTo = array(
		'Persons' => array(
			'to' => 'app\models\Persons',
			'key' => array(
				'archive_id1' => 'id'
		)),
		'Albums' => array(
			'to' => 'app\models\Albums',
			'key' => array(
				'archive_id1' => 'id'
		)),
		'Exhibitions' => array(
			'to' => 'app\models\Exhibitions',
			'key' => array(
				'archive_id1' => 'id'
		)),
		'Works' => array(
			'to' => 'app\models\Works',
			'key' => array(
				'archive_id2' => 'id'
		)),
	);

	public $validates = array();

}

?>
