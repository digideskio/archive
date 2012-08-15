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

Architectures::applyFilter('save', function($self, $params, $chain) {
	// Custom pre-dispatch logic goes here

	// Check if this is a new record
	if(!$params['entity']->exists()) {
	
		//create a slug based on the title
		$slug = Inflector::slug($params['data']['title']);
		
		//Check if the slug ends with an iterated number such as Slug-1
		if(preg_match_all("/.*?-(\d+)$/", $slug, $matches)) {
			//Get the base of the iterated slug
			$slug = substr($slug, 0, strripos($slug, '-'));
		}
		
		//Count the slugs that start with $slug
		$count = Architectures::find('count', array(
		    'fields' => array('id'),
		    'conditions' => array('slug' => array('like' => "$slug%"))
		));
		
		$params['data']['slug'] = $slug . ($count ? "-" . (++$count) : ''); //add slug-X only if $count > 0
	}
	
	$response = $chain->next($self, $params, $chain);

	// $response now contains the return value of the dispatched request,
	// and can be modified as appropriate
	// ...
	return $response;
});

?>
