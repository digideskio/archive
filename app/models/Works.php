<?php

namespace app\models;

use lithium\util\Inflector;

class Works extends \lithium\data\Model {

	public $validates = array(
		'title' => array(
			array('notEmpty', 'message' => 'Please enter a title.'),
		),
		//Date checks don't work for values just listing the year, like '2007'
		//TODO preprocess the date from form submit
        /*'earliest_date' => array(
            array('date', 'skipEmpty' => true, 'message'=>'Please enter a valid date.')
        ),
        'latest_date' => array(
            array('date', 'skipEmpty' => true, 'message'=>'Please enter a valid date.')
        ),*/
    );
    
    public function dimensions($entity) {
    	$hwd = array_filter(array($entity->height, $entity->width, $entity->depth));
    	$measures = $hwd ? implode(' × ', $hwd) . ' cm' : '';
    	$running_time = $entity->running_time ? $entity->running_time : '';
    	$dimensions = array_filter(array($measures, $running_time));
    	return implode(', ', $dimensions);
    }
    
    public function years($entity) {
    
    	$earliest_year = (
    		$entity->earliest_date != 
    		'0000-00-00 00:00:00'
    	) ? date_format(date_create($entity->earliest_date), 'Y') : '0';
    	$latest_year = (
    		$entity->latest_date != 
    		'0000-00-00 00:00:00'
    	) ? date_format(date_create($entity->latest_date), 'Y') : 0;
    	
    	$years = array_filter(array($earliest_year, $latest_year));

		return implode('–', $years);
    }
    
    public function caption($entity) {
    
    	$years = Works::years($entity);
    
    	$caption = array_filter(array(
    		$entity->artist,
    		'<em>'.$entity->title.'</em>',
    		$years,
    		$entity->dimensions()
    	));
    	
    	return implode(', ', $caption) . '.';
    
    }
    
    public function notes($entity) {
						
		$dimensions = $entity->dimensions();
		
		$materials = $entity->materials ? $entity->materials : '';
		$dimensions = $dimensions ? $dimensions : '';
		$measurement_remarks = $entity->measurement_remarks ? $entity->measurement_remarks : '';
		$quantity = $entity->quantity ? 'Quantity: ' . $entity->quantity : '';
		$location = $entity->location ? 'Location: ' . $entity->location : '';
		$lender = $entity->lender ? 'Lender: ' . $entity->lender : '';
		$remarks =  $entity->remarks ? $entity->remarks : '';
		
		$info = array_filter(array(
			$materials,
			$dimensions,
			$measurement_remarks,
			$quantity,
			$location,
			$lender,
			$remarks,
		));
		
		return implode('<br/>', $info);
	}
    
}

//TODO
//If earliest date is entered as YYYY, save it as YYYY-01-01
//If latest date is entered as YYYY, save it as YYYY-12-31

Works::applyFilter('save', function($self, $params, $chain) {
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
		$count = Works::find('count', array(
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
