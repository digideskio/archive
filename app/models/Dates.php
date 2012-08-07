<?php

namespace app\models;

class Dates extends \lithium\data\Model {

	public $belongsTo = array('Collections', 'Works');

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
