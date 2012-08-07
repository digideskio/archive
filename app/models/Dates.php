<?php

namespace app\models;

class Dates extends \lithium\data\Model {

	public $hasOne = array('Collections', 'Works');

	public $validates = array();
    
    public function years($entity) {
    
    	$start_year = (
    		$entity->start != 
    		'0000-00-00 00:00:00'
    	) ? date_format(date_create($entity->start), 'Y') : '0';
    	$end_year = (
    		$entity->end != 
    		'0000-00-00 00:00:00'
    	) ? date_format(date_create($entity->end), 'Y') : 0;
    	
    	$years = array_unique(array_filter(array($start_year, $end_year)));

		return implode('–', $years);
    }
	
	public function dates($entity) {
		
		// If the record has an start, find the day, month and year
		if($entity->start != '0000-00-00 00:00:00') {
			$start_day = date('d', strtotime($entity->start));
			$start_month = date('M', strtotime($entity->start));
			$start_days = date('d M', strtotime($entity->start));
			$start_year = date('Y', strtotime($entity->start));
		}
		
		// If the record has a end, find the day, month and year
		if($entity->end != '0000-00-00 00:00:00') {
			$end_days = date('d M', strtotime($entity->end));
			$end_month = date('M', strtotime($entity->end));
			$end_year = date('Y', strtotime($entity->end));
		}
		
		// If both start and end are set for the record
		if(isset($start_year) && isset($end_year)) {
		
			// If the earliest year is equal to the latest year, don't output the earliest year
			$start_year = ($start_year != $end_year) ? $start_year : '';
			
			// If the earliest year is no longer set, check the month
			if(!$start_year) {
				// If the months are also equal, unset the earlier month
				$start_month = ($start_month != $end_month) ? $start_month : '';
				
				// Update the value of the earliest days variable
				$start_days = $start_month ? $start_days : $start_day;
			}		
		}
		
		$starts = isset($start_year) ? implode(', ', array_filter(array($start_days, $start_year))) : '';
		$ends = isset($end_year) ? implode(', ', array_filter(array($end_days, $end_year))) : '';
		
		return implode(' – ', array_filter(array($starts, $ends)));
	
	}
}

Dates::applyFilter('save', function($self, $params, $chain) {
	// Custom pre-dispatch logic goes here
	
	date_default_timezone_set('UTC');

	// Check if this is a new record
	if(!$params['entity']->exists()) {
	
		// Set the date created
		$params['data']['created'] = date("Y-m-d H:i:s");
	
	}
	
	if( isset($params['data']['start']) && 
		$params['data']['start'] &&
		strtotime($params['data']['start'])
	) {
		if(preg_match('/^\d{4}$/', $params['data']['start'])) {
			$params['data']['start'] .= '-01-01 00:00:00';
		}
		$time = strtotime($params['data']['start']);
		$params['data']['start'] = date("Y-m-d H:i:s", $time);
	}
	
	if( isset($params['data']['end']) && 
		$params['data']['end'] &&
		strtotime($params['data']['end'])
	) {
		if(preg_match('/^\d{4}$/', $params['data']['end'])) {
			$params['data']['end'] .= '-01-01 00:00:00';
		}
		$time = strtotime($params['data']['end']);
		$params['data']['end'] = date("Y-m-d H:i:s", $time);
	}
	
	// Set the date modified
	$params['data']['updated'] = date("Y-m-d H:i:s");
  
	$response = $chain->next($self, $params, $chain);

	// $response now contains the return value of the dispatched request,
	// and can be modified as appropriate
	// ...
	return $response;
});

?>
