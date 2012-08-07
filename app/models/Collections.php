<?php

namespace app\models;

use app\models\Dates;

use lithium\util\Inflector;

class Collections extends \lithium\data\Model {

	public $hasMany = array('CollectionsWorks');

	public $validates = array(
		'title' => array(
			array(
				'notEmpty',
				'required' => true,
				'message'=>'You must include a title.'
			)
		)
	);
}

Collections::applyFilter('delete', function($self, $params, $chain) {

	$collection_id = $params['entity']->id;
	$date_id = $params['entity']->date_id;
		
	//Delete any relationships
	CollectionsWorks::find('all', array(
		'conditions' => array('collection_id' => $collection_id)
	))->delete();
	
	Dates::find('first', array(
		'conditions' => array('id' => $date_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});

Collections::applyFilter('save', function($self, $params, $chain) {
	// Custom pre-dispatch logic goes here

	// Check if this is a new record
	if(!$params['entity']->exists()) {
	
		$date = Dates::create();
		$date->save();
		
		$params['data']['date_id'] = $date->id;
		
		$params['data']['class'] = 'collection';
	
		//create a slug based on the title
		$slug = Inflector::slug($params['data']['title']);
		
		//Check if the slug ends with an iterated number such as Slug-1
		if(preg_match_all("/.*?-(\d+)$/", $slug, $matches)) {
			//Get the base of the iterated slug
			$slug = substr($slug, 0, strripos($slug, '-'));
		}
		
		//Count the slugs that start with $slug
		$count = Collections::find('count', array(
		    'fields' => array('id'),
		    'conditions' => array('slug' => array('like' => "$slug%"))
		));
		
		/* Found the following in Lithium Filters – A Practical Example, but the regex does not work 
		//count of slugs which start with $slug
		$count = Collections::find('count', array(
		    'fields' => array('id'),
		    'conditions' => array('slug' => array('like' => '/^(?:' . $slug . ')(?:-?)(?:\d?)$/i')),
		));*/
		
		
		$params['data']['slug'] = $slug . ($count ? "-" . (++$count) : ''); //add slug-X only if $count > 0
	} else {
		$date_id = $params['entity']->date_id;
		Dates::find('first', array(
			'conditions' => array('id' => $date_id)
		))->save();
	}
  
	$response = $chain->next($self, $params, $chain);

	// $response now contains the return value of the dispatched request,
	// and can be modified as appropriate
	// ...
	return $response;
});

?>
