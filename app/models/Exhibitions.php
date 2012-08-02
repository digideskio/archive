<?php

namespace app\models;

use lithium\util\Inflector;

class Exhibitions extends \lithium\data\Model {

	public $hasMany = array('ExhibitionsWorks');

	public $validates = array(
		'title' => array(
			array(
				'notEmpty',
				'required' => true,
				'message'=>'You must include a title.'
			)
		)
	);
	
	public function location($entity) {
	
		$venue = $entity->venue ? '<strong>' . $entity->venue . '</strong>' : '';
		$location = array_filter(array($venue, $entity->city, $entity->country));
		return implode(', ', $location);
	
	}
	
	public function dates($entity) {
		
		// If the record has an earliest_date, find the day, month and year
		if($entity->earliest_date != '0000-00-00 00:00:00') {
			$early_day = date('d', strtotime($entity->earliest_date));
			$early_month = date('M', strtotime($entity->earliest_date));
			$early_days = date('d M', strtotime($entity->earliest_date));
			$early_year = date('Y', strtotime($entity->earliest_date));
		}
		
		// If the record has a latest_date, find the day, month and year
		if($entity->latest_date != '0000-00-00 00:00:00') {
			$later_days = date('d M', strtotime($entity->latest_date));
			$later_month = date('M', strtotime($entity->latest_date));
			$later_year = date('Y', strtotime($entity->latest_date));
		}
		
		// If both earliest_date and latest_date are set for the record
		if(isset($early_year) && isset($later_year)) {
		
			// If the earliest year is equal to the latest year, don't output the earliest year
			$early_year = ($early_year != $later_year) ? $early_year : '';
			
			// If the earliest year is no longer set, check the month
			if(!$early_year) {
				// If the months are also equal, unset the earlier month
				$early_month = ($early_month != $later_month) ? $early_month : '';
				
				// Update the value of the earliest days variable
				$early_days = $early_month ? $early_days : $early_day;
			}		
		}
		
		$earlies = isset($early_year) ? implode(', ', array_filter(array($early_days, $early_year))) : '';
		$laters = isset($later_year) ? implode(', ', array_filter(array($later_days, $later_year))) : '';
		
		return implode(' – ', array_filter(array($earlies, $laters)));
	
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
    	
    	$years = array_unique(array_filter(array($earliest_year, $latest_year)));

		return implode('–', $years);
    }
}

Exhibitions::applyFilter('delete', function($self, $params, $chain) {

	$exhibition_id = $params['entity']->id;
		
	//Delete any relationships
	ExhibitionsWorks::find('all', array(
		'conditions' => array('exhibition_id' => $exhibition_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});

Exhibitions::applyFilter('save', function($self, $params, $chain) {
	// Custom pre-dispatch logic goes here

	// Check if this is a new record
	if(!$params['entity']->exists()) {
	
		// Set the date created
		$params['data']['date_created'] = date("Y-m-d H:i:s");
		
		$title = $params['data']['title'];
		
		$title_venue = isset($params['data']['venue']) ? $title . ' ' . $params['data']['venue'] : $title;
	
		//create a slug based on the title
		$slug = Inflector::slug($title_venue);
		
		//Check if the slug ends with an iterated number such as Slug-1
		if(preg_match_all("/.*?-(\d+)$/", $slug, $matches)) {
			//Get the base of the iterated slug
			$slug = substr($slug, 0, strripos($slug, '-'));
		}
		
		//Count the slugs that start with $slug
		$count = Exhibitions::find('count', array(
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
	
	// Set the date modified
	$params['data']['date_modified'] = date("Y-m-d H:i:s");
  
	$response = $chain->next($self, $params, $chain);

	// $response now contains the return value of the dispatched request,
	// and can be modified as appropriate
	// ...
	return $response;
});

?>
