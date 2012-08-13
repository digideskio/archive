<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;

class Publications extends \app\models\Archives {
    
    public function citation($entity) {
    	$years = Publications::years($entity);
    	
    	$author = $entity->author;
    	$author_years = $years ? $author . ' (' . $years . ')' : $author;
    	$title = '<em>' . $entity->title . '</em>';
    	$title = $entity->url ? "<a href='$entity->url'>$title</a>" : $title;
		$publication = $entity->pages ? $entity->publisher . ', ' . $entity->pages : $entity->publisher;
		
		$citation = array_filter(array(
			$author_years,
			$title,
			$publication
		));
    	
    	return implode('. ', $citation) . '.';
    }
    
}

Publications::applyFilter('save', function($self, $params, $chain) {
	// Custom pre-dispatch logic goes here
	date_default_timezone_set('UTC');

	// Check if this is a new record
	if(!$params['entity']->exists()) {
	
		// Set the date created
		$params['data']['date_created'] = date("Y-m-d H:i:s");
	
		//create a slug based on the title
		$slug = Inflector::slug($params['data']['title']);
		
		//Check if the slug ends with an iterated number such as Slug-1
		if(preg_match_all("/.*?-(\d+)$/", $slug, $matches)) {
			//Get the base of the iterated slug
			$slug = substr($slug, 0, strripos($slug, '-'));
		}
		
		//Count the slugs that start with $slug
		$count = Publications::find('count', array(
		    'fields' => array('id'),
		    'conditions' => array('slug' => array('like' => "$slug%"))
		));
		
		$params['data']['slug'] = $slug . ($count ? "-" . (++$count) : ''); //add slug-X only if $count > 0
	}
	
	if( isset($params['data']['earliest_date']) && 
		$params['data']['earliest_date'] &&
		strtotime($params['data']['earliest_date'])
	) {
		if(preg_match('/^\d{4}$/', $params['data']['earliest_date'])) {
			$params['data']['earliest_date'] .= '-01-01';
		}
		$time = strtotime($params['data']['earliest_date']);
		$params['data']['earliest_date'] = date("Y-m-d", $time);
	}
	
	if( isset($params['data']['latest_date']) && 
		$params['data']['latest_date'] &&
		strtotime($params['data']['latest_date'])
	) {
		if(preg_match('/^\d{4}$/', $params['data']['latest_date'])) {
			$params['data']['latest_date'] .= '-01-01';
		}
		$time = strtotime($params['data']['latest_date']);
		$params['data']['latest_date'] = date("Y-m-d", $time);
	}
	
	// Set the date modified
	$params['data']['date_modified'] = date("Y-m-d H:i:s");
  
	$response = $chain->next($self, $params, $chain);

	// $response now contains the return value of the dispatched request,
	// and can be modified as appropriate
	// ...
	return $response;
});

?>
