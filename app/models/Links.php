<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;

class Links extends \lithium\data\Model {

	public $hasMany = array('ArchivesLinks');

	public $validates = array(
		'url' => array(
			array('notEmpty', 'message' => 'Please enter a URL.'),
			array('url', 'message' => 'The URL is not valid.'),
			array('urlUnique', 'message' => 'The URL is already in use.')
		),
	);

	public function elision($entity) {

		$url = $entity->url;
		$url = substr($url, 7);
		$len = strlen($url);

		if ($len > 30) {
			$start = substr($url, 0, 15);
			$end = substr($url, -10);

		 	$url = $start . '...' . $end;
		}
		return $url;

	}
}

Validator::add('urlUnique', function($value, $rule, $options) {

	// Check event, set during the call to Model::save()
	$event = $options["events"];  // 'update' OR 'create'

	// By default, check the URL for uniqueness
	$checkforConflicts = true;

	if ($event == 'create') {
		// Always check a URL for uniqueness on a new record
		$checkForConflicts = true;
	}

	if ($event == 'update') {
		// If the record is being updated, get its ID
		$id = isset($options["values"]["id"]) ? $options["values"]["id"] : NULL;

		// If the id is set, look up what it belongs to
		if(!empty($id)) {
			$link = Links::first($id);

			// If the submitted URL for this record hasn't changed, then we shouldn't
			// re-validate its uniqueness
			if ($link->url == $value) {
				$checkForConflicts = false;
			}
		}
	}

	if ($checkForConflicts) {
		$links = Links::find('all', array(
			'conditions' => array('url' => $value)
		));

		$conflicts = $links->count();

		if($conflicts) {
			return false;
		}
	}
	return true;
});

Links::applyFilter('save', function($self, $params, $chain) {
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

Links::applyFilter('delete', function($self, $params, $chain) {

	$link_id = $params['entity']->id;

	//Delete any relationships
	ArchivesLinks::find('all', array(
		'conditions' => array('link_id' => $link_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});

?>
