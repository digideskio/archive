<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;

class Links extends \lithium\data\Model {

	public $hasMany = array('WorksLinks', 'PublicationsLinks', 'ExhibitionsLinks');

	public $validates = array(
		'url' => array(
			array('notEmpty', 'message' => 'Please enter a URL.'),
			array('url', 'message' => 'The URL is not valid.'),
			array('urlUnique', 'message' => 'The URL is already in use.')
		),
	);

	public function _init() {

		parent::_init($options);

		static::applyFilter('save', function($self, $params, $chain) {
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
	}

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

	// Get the submitted values
	$id = isset($options["values"]["id"]) ? $options["values"]["id"] : NULL;
	
	// Check event, set during the call to Model::save()
	$event = $options["events"];  // 'update' OR 'create'
	
	// If the id is set, look up what it belongs to
	if($id) {
		$links = Links::first($id);
	}
	
	// Check for conflicts if the record is new, or if the stored value is
	// different from the submitted one
	if
	(
		($event == 'create') ||
		($event == 'update' && $id && $links->url != $value)
	) {
		$conflicts = Links::count(array('url' => $value));
		if($conflicts) return false;
	}
	return true;
});

Links::applyFilter('delete', function($self, $params, $chain) {

	$link_id = $params['entity']->id;

	//Delete any relationships
	WorksLinks::find('all', array(
		'conditions' => array('link_id' => $link_id)
	))->delete();

	ExhibitionsLinks::find('all', array(
		'conditions' => array('link_id' => $link_id)
	))->delete();

	PublicationsLinks::find('all', array(
		'conditions' => array('link_id' => $link_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});

?>
