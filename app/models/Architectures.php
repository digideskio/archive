<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;

class Architectures extends \app\models\Archives {

	public $hasMany = array('ArchitecturesDocuments');

	public function dimensions($entity) {
		return $entity->area ? $entity->area . " square meters" : '';
	}
    
    public function caption($entity) {
    
    	$years = Architectures::years($entity);
    	$status = $entity->status ? '(' . $entity->status . ')' : '';
    
    	$caption = array_filter(array(
			$entity->architects,
    		'<em>'.$entity->title.'</em>',
    		$entity->remarks,
    		$years,
    		$entity->location,
    		$entity->city,
    		$entity->country,
    		$status
    	));
    	
    	return implode(', ', $caption) . '.';
    
    }

	public function documents($entity,  $type = 'all') {
		
		$documents = Documents::find($type, array(
			'with' => array(
				'ArchitecturesDocuments',
				'Formats'
			),
			'conditions' => array('architecture_id' => $entity->id),
		));

		return $documents;
	}
	
}

Architectures::applyFilter('delete', function($self, $params, $chain) {

	$architecture_id = $params['entity']->id;
		
	//Delete any relationships
	ArchitecturesDocuments::find('all', array(
		'conditions' => array('architecture_id' => $architecture_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});

?>
