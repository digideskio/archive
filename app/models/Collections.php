<?php

namespace app\models;

use app\models\Dates;

use lithium\util\Inflector;

class Collections extends \app\models\Archives {

	public $hasMany = array('CollectionsWorks');
	
}

Collections::applyFilter('delete', function($self, $params, $chain) {

	$collection_id = $params['entity']->id;
		
	//Delete any relationships
	CollectionsWorks::find('all', array(
		'conditions' => array('collection_id' => $collection_id)
	))->delete();
	
	return $chain->next($self, $params, $chain);

});

?>
