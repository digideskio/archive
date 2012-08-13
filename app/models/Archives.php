<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;

class Archives extends \lithium\data\Model {
	
    public function years($entity) {
    
    	$earliest_year = (
    		$entity->earliest_date != '0000-00-00 00:00:00' &&
    		$entity->earliest_date != '0000-00-00' &&
    		$entity->earliest_date != NULL
    		
    	) ? date_format(date_create($entity->earliest_date), 'Y') : '0';
    	$latest_year = (
    		$entity->latest_date != '0000-00-00 00:00:00' &&
    		$entity->latest_date != '0000-00-00' &&
    		$entity->latest_date != NULL
    	) ? date_format(date_create($entity->latest_date), 'Y') : 0;
    	
    	$years = array_unique(array_filter(array($earliest_year, $latest_year)));

		return implode('–', $years);
    }	
	
}
