<?php

namespace app\models;

use lithium\util\Inflector;

class Exhibitions extends \lithium\data\Model {

	public $belongsTo = array('Collections');
	
	public function location($entity) {
	
		$venue = $entity->venue ? '<strong>' . $entity->venue . '</strong>' : '';
		$location = array_filter(array($venue, $entity->city, $entity->country));
		return implode(', ', $location);
	
	}
}

?>
