<?php

namespace app\models;

use app\models\Dates;

use lithium\util\Inflector;

class Albums extends \app\models\Archives {

	public $hasMany = array('AlbumsWorks');
	
}

Albums::applyFilter('delete', function($self, $params, $chain) {

	$album_id = $params['entity']->id;
		
	//Delete any relationships
	AlbumsWorks::find('all', array(
		'conditions' => array('album_id' => $album_id)
	))->delete();
	
	return $chain->next($self, $params, $chain);

});

?>
