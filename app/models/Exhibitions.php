<?php

namespace app\models;

use lithium\util\Inflector;

class Exhibitions extends \app\models\Archives {

	public $hasMany = array('ExhibitionsWorks', 'ExhibitionsDocuments', 'ExhibitionsLinks');

	public function location($entity) {
	
		$venue = $entity->venue ? '<strong>' . $entity->venue . '</strong>' : '';
		$location = array_filter(array($venue, $entity->city, $entity->country));
		return implode(', ', $location);
	
	}
}

Exhibitions::applyFilter('delete', function($self, $params, $chain) {

	$exhibition_id = $params['entity']->id;
		
	//Delete any relationships
	ExhibitionsWorks::find('all', array(
		'conditions' => array('exhibition_id' => $exhibition_id)
	))->delete();
	
	ExhibitionsDocuments::find('all', array(
		'conditions' => array('exhibition_id' => $exhibition_id)
	))->delete();

	ExhibitionsLinks::find('all', array(
		'conditions' => array('exhibition_id' => $exhibition_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});

Exhibitions::applyFilter('save', function($self, $params, $chain) {

	$result = $chain->next($self, $params, $chain);

	$exhibition_id = $params['entity']->id;
	$url = isset($params['data']['url']) ? $params['data']['url'] : null;
	$title = isset($params['data']['title']) ? $params['data']['title'] : null;

	if ($exhibition_id && $url) {
		$exhibitions_links = ExhibitionsLinks::create();
		$data = compact('exhibition_id', 'url', 'title');
		$exhibitions_links->save($data);
	}

	return $result;
});


?>
