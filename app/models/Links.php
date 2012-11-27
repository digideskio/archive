<?php

namespace app\models;

class Links extends \lithium\data\Model {

	public $hasMany = array('WorksLinks', 'PublicationsLinks');

	public $validates = array(
		'url' => array(
			array('notEmpty', 'message' => 'Please enter a URL.'),
			array('url', 'message' => 'The URL is not valid.')
		),
	);

	public static function __init(array $options = array()) {

		parent::__init($options);

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

Links::applyFilter('delete', function($self, $params, $chain) {

	$link_id = $params['entity']->id;

	//Delete any relationships
	WorksLinks::find('all', array(
		'conditions' => array('link_id' => $link_id)
	))->delete();

	PublicationsLinks::find('all', array(
		'conditions' => array('link_id' => $link_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});

?>
