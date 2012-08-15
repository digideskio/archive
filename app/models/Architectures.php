<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;

class Architectures extends \app\models\Archives {

	public $hasMany = array('ArchitecturesDocuments');
    
    public function caption($entity) {
    
    	$years = Architectures::years($entity);
    	$status = $entity->status ? '(' . $entity->status . ')' : '';
    
    	$caption = array_filter(array(
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
	
	public function preview($entity) {
	
		$architecture_documents = ArchitecturesDocuments::first('all', array(
			'with' => array(
				'Documents',
				'Formats'
			),
			'conditions' => array('architecture_id' => $entity->id),
		));
		
		if($architecture_documents) {
		
			return $architecture_documents->document->thumbnail();
		}
	
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
