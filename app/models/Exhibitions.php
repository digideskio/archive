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

?>
