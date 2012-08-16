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
	

	public static function __init(array $options = array()) {

		parent::__init($options);

		static::applyFilter('save', function($self, $params, $chain) {
			// Custom pre-dispatch logic goes here
			date_default_timezone_set('UTC');

			// Check if this is a new record
			if(!$params['entity']->exists()) {

				// Set the date created
				$params['data']['date_created'] = date("Y-m-d H:i:s");

			}

			// Set the date modified
			$params['data']['date_modified'] = date("Y-m-d H:i:s");

			return $chain->next($self, $params, $chain);

		});

		static::applyFilter('save', function($self, $params, $chain) {

			$params['data']['earliest_date'] = isset($params['data']['earliest_date']) ? Archives::filterDate($params['data']['earliest_date']) : '';
			$params['data']['latest_date'] = isset($params['data']['latest_date']) ? Archives::filterDate($params['data']['latest_date']) : '';

			return $chain->next($self, $params, $chain);

		});

		static::applyFilter('save', function($self, $params, $chain) {

			if(!$params['entity']->exists()) { 

				$title = $params['data']['title'];

				if( isset($params['data']['venue']) ) {
					$title = $title . " " . $params['data']['venue'];
				}

				$slug = Inflector::slug($title);

				$conditions = array('slug' => $slug);

				$conflicts = $self::count(compact('conditions'));

				if($conflicts){
					$i = 0;
					$newSlug = '';
					while($conflicts){
						$i++;
						$newSlug = "{$slug}-{$i}";
						$conditions = array('slug' => $newSlug);
						$conflicts = $self::count(compact('conditions'));
					}
					$slug = $newSlug;
				}

				$params['data']['slug'] = $slug;
				
			}

			return $chain->next($self, $params, $chain);

		});

	}

	public static function filterDate($date) {

		if(preg_match('/^\d{4}$/', $date)) {
			 $date .= '-01-01';
		}

		if( Validator::isValidDate($date) ) {
			$time = strtotime($date);
			$date = date("Y-m-d", $time);
		}

		return $date;

	}

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

	public function dates($entity) {

		$start_date = $entity->start_date();
		$end_date = $entity->end_date();

		// If the record has a start date, find the day, month, and year
		if($start_date) {
			$start_day = date('d', strtotime($start_date));
			$start_month = date('M', strtotime($start_date));
			$start_days = date('d M', strtotime($start_date));
			$start_year = date('Y', strtotime($start_date));
		}
		
		// If the record has a end, find the day, month and year
		if($end_date) {
			$end_days = date('d M', strtotime($end_date));
			$end_month = date('M', strtotime($end_date));
			$end_year = date('Y', strtotime($end_date));
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
