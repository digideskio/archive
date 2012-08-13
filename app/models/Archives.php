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
				'message' => 'Please enter a valid date.',
				'format' => 'any'
			)
		),
		'earliest_date' => array(
			array('validDate',
				'skipEmpty' => true,
				'message' => 'Please enter a valid date.',
				'format' => 'any'
			)
		)
    );
	
    public function years($entity) {
    
    	$start_date = $entity->start_date();
    	$end_date = $entity->end_date();
    
    	$start_year = (
    		$start_date
    	) ? date_format(date_create($start_date), 'Y') : '0';
    	$end_year = (
    		$end_date
    	) ? date_format(date_create($end_date), 'Y') : 0;
    	
    	$years = array_unique(array_filter(array($start_year, $end_year)));

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
