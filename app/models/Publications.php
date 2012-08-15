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
		$count = Publications::find('count', array(
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
