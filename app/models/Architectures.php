<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;

class Architectures extends \lithium\data\Model {

	public $hasMany = array('ArchitecturesDocuments');

	public $validates = array(
		'title' => array(
			array('notEmpty', 'message' => 'Please enter a title.'),
		)
    );
    
    public function years($entity) {
    
    	$earliest_year = (
    		$entity->earliest_date != 
    		'0000-00-00 00:00:00'
    	) ? date_format(date_create($entity->earliest_date), 'Y') : '0';
    	$latest_year = (
    		$entity->latest_date != 
    		'0000-00-00 00:00:00'
    	) ? date_format(date_create($entity->latest_date), 'Y') : 0;
    	
    	$years = array_unique(array_filter(array($earliest_year, $latest_year)));

		return implode('–', $years);
    }
    
    public function caption($entity) {
    
    	$years = Works::years($entity);
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
	
	date_default_timezone_set('UTC');
	
	if( isset($params['data']['earliest_date']) && 
		$params['data']['earliest_date'] &&
		strtotime($params['data']['earliest_date'])
	) {
		if(preg_match('/^\d{4}$/', $params['data']['earliest_date'])) {
			$params['data']['earliest_date'] .= '-01-01 00:00:00';
		}
		$time = strtotime($params['data']['earliest_date']);
		$params['data']['earliest_date'] = date("Y-m-d H:i:s", $time);
	}
	
	if( isset($params['data']['latest_date']) && 
		$params['data']['latest_date'] &&
		strtotime($params['data']['latest_date'])
	) {
		if(preg_match('/^\d{4}$/', $params['data']['latest_date'])) {
			$params['data']['latest_date'] .= '-01-01 00:00:00';
		}
		$time = strtotime($params['data']['latest_date']);
		$params['data']['latest_date'] = date("Y-m-d H:i:s", $time);
	}
  
	$response = $chain->next($self, $params, $chain);

	// $response now contains the return value of the dispatched request,
	// and can be modified as appropriate
	// ...
	return $response;
});

?>
