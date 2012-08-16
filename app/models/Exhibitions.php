<?php

namespace app\models;

use lithium\util\Inflector;

class Exhibitions extends \app\models\Archives {

	public $hasMany = array('ExhibitionsWorks');

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
	
	return $chain->next($self, $params, $chain);

});

?>
