<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;

class Archives extends \lithium\data\Model {

	public $validates = array(
		'title' => array(
			array('notEmpty', 'message' => 'Please enter a title.'),
		),
		'latest_date' => array(
			array('validDate',
			'skipEmpty' => true,
			'message' => 'Please enter a valid date.'),
		),
		'earliest_date' => array(
			array('validDate',
			'skipEmpty' => true,
			'message' => 'Please enter a valid date.'),
		)
    );
	
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
    
    public function start_date($entity) {
    
		return (
			$entity->earliest_date != '0000-00-00 00:00:00' &&
			$entity->earliest_date != '0000-00-00' &&
			$entity->earliest_date != NULL
		) ? $entity->earliest_date : '';
    	
    }
    
    public function end_date($entity) {
    
		return (
			$entity->latest_date != '0000-00-00 00:00:00' &&
			$entity->latest_date != '0000-00-00' &&
			$entity->latest_date != NULL
		) ? $entity->latest_date : '';
    	
    }
	
}

Validator::add('validDate', function($value) {
	
	return (
		Validator::isDate($value, 'dmy') ||
		Validator::isDate($value, 'mdy') ||
		Validator::isDate($value, 'ymd') ||
		Validator::isDate($value, 'dMy') ||
		Validator::isDate($value, 'Mdy') ||
		Validator::isDate($value, 'My')  ||
		Validator::isDate($value, 'my')
	);
});
