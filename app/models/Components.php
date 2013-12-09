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

/**
 * Date Modified Filter
 */

Components::applyFilter('save', function($self, $params, $chain) {
	// Custom pre-dispatch logic goes here
	date_default_timezone_set('UTC');

	// Check if this is a new record
	if(!$params['entity']->exists()) {

		// Set the date created
		$params['data']['date_created'] = date("Y-m-d H:i:s");

	}

	// Set the date modified
	$params['data']['date_modified'] = date("Y-m-d H:i:s");

	return $chain->next($self, $params, $chain);

});

?>
