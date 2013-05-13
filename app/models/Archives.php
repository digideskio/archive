<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;
use lithium\security\Auth;

class Archives extends \lithium\data\Model {

	public $belongsTo = array("Users");

	public $hasMany = array("ArchivesDocuments");

	public $hasOne = array(
		'Works' => array (
			'key' => 'id'
		),
		'Architectures' => array (
			'key' => 'id'
		),
		'Albums' => array (
			'key' => 'id'
		),
		'Exhibitions' => array (
			'key' => 'id'
		),
		'Publications' => array (
			'key' => 'id'
		),
	);

	public $validates = array(
		'title' => array(
			array('notEmpty', 'message' => "You can't leave this blank."),
		),
		'url' => array(
			array('url', 'skipEmpty' => true, 'message' => 'The URL is not valid.'),
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

			if (isset($params['data']['earliest_date']) && $params['data']['earliest_date'] != '') {
				$earliest_date = $params['data']['earliest_date'];
				$earliest_date_filtered = Archives::filterDate($earliest_date);
				$params['data']['earliest_date'] = $earliest_date_filtered['date'];
				$params['data']['earliest_date_format'] = $earliest_date_filtered['format']; 
			} else {
				//FIXME validation for the dates is failing if they are NULL, which is true of many unit tests
				//So let's make sure the value is at least empty if it is not set
				//This has only been necessary since the extra date format code was added
				$params['data']['earliest_date'] = '';
			}

			if (isset($params['data']['latest_date']) && $params['data']['latest_date'] != '') {
				$latest_date = $params['data']['latest_date'];
				$latest_date_filtered = Archives::filterDate($latest_date);
				$params['data']['latest_date'] = $latest_date_filtered['date'];
				$params['data']['latest_date_format'] = $latest_date_filtered['format']; 
			} else {
				$params['data']['latest_date'] = '';
			}

			return $chain->next($self, $params, $chain);

		});

		//Some models allow a URL to be saved during an add, which creates a Link object and/or a link relation
		//In order for the validation to work properly in the Archives model, the URL cannot be unset
		static::applyFilter('save', function($self, $params, $chain) {

			if (!isset($params['data']['url'])) {
				$params['data']['url'] = '';
			}

			return $chain->next($self, $params, $chain);

		});

		//TODO	The generic Archive will use name instead of title. However, a lot of models which extend Archives
		//		rely on title to validate and for tests. When those models are migrated, we can remove the following filter

		static::applyFilter('save', function($self, $params, $chain) {
			if(isset($params['data']['name'])) {
				$params['data']['title'] = $params['data']['name'];
			} else {
				$params['data']['name'] = isset($params['data']['title']) ? $params['data']['title'] : '';
			}
			return $chain->next($self, $params, $chain);
		});

		static::applyFilter('save', function($self, $params, $chain) {

			if(!$params['entity']->exists()) { 

				$name = $params['data']['name'];

				if( isset($params['data']['venue']) ) {
					$name = $name . " " . $params['data']['venue'];
				}

				/* FIXME we should respect that slugs are varchar(255). Limit slugs to 200 characters? */
				$slug = Inflector::slug($name);

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

		$format = '';

		if (preg_match('/^\d{4}$/', $date)) {
			$date .= '-01-01';
			$format = 'Y';
		} elseif (preg_match('/^\d{4}-\d{2}$/', $date)) { 
			$date .= "-01";
			$format = 'M Y';
		} else {

			if (
				Validator::isDate($date, 'dmy') ||
				Validator::isDate($date, 'mdy') ||
				Validator::isDate($date, 'ymd') ||
				Validator::isDate($date, 'dMy') ||
				Validator::isDate($date, 'Mdy')
			) {
				$format = 'Y-m-d';
			}

			if (
				Validator::isDate($date, 'My')  ||
				Validator::isDate($date, 'my')
			) {
				$format = 'M Y'; // The format needs to be 'M Y' since 'Y-m' cannot be parsed as a valid date
			}
		}

		if( Validator::isValidDate($date) ) {
			$time = strtotime($date);
			$date = date("Y-m-d", $time);
		}

		return compact('date', 'format');

	}

	public function names($entity) {
		$name = $entity->name;

		$native_name = $entity->native_name ? '(' . $entity->native_name . ')' : '';

		$names = array_filter(array(
			$name,
			$native_name
		));

		return implode(' ', $names);
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
		$start_format = $entity->earliest_date_format;

		$end_date = $entity->end_date();
		$end_format = $entity->latest_date_format;

		// If the record has a start date, find the day, month, and year, but respect the precision of the date
		if($start_date) {
			$start['day'] = stripos($start_format, 'd') === false ? '' : date('d', strtotime($start_date));
			$start['month'] = stripos($start_format, 'm') === false ? '' : date('M', strtotime($start_date));
			$start['year'] = date('Y', strtotime($start_date));
		}

		// If the record has an end date, find the day, month and year, but respect the precision of the date 
		if($end_date) {
			$end['day'] = stripos($end_format, 'd') === false ? '' : date('d', strtotime($end_date));
			$end['month'] = stripos($end_format, 'm') === false ? '' : date('M', strtotime($end_date));
			$end['year'] = date('Y', strtotime($end_date));
		}

		//remove parts of the start date that are the same as the end date
		if ($start_date && $end_date) {
			if ($start['year'] == $end['year']) {

				$start['year'] = '';

				if ($start['month'] == $end['month']) {

					$start['month'] = '';

					if ($start['day'] == $end['day']) {

						$start['day'] = '';
					}
				}
			}
		}
		
		$starts = $start_date ? implode(' ', array_filter($start)) : '';
		$ends = $end_date ? implode(' ', array_filter($end)) : '';

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

	public function start_date_formatted($entity) {
		$start_date = $entity->start_date();
		$format = $entity->earliest_date_format ?: 'Y-m-d';

		return $start_date ? date($format, strtotime($start_date)) : '';
	}

	public function end_date_formatted($entity) {
		$end_date = $entity->end_date();
		$format = $entity->latest_date_format ?: 'Y-m-d';

		return $end_date ? date($format, strtotime($end_date)) : '';
	}
	
}

Archives::applyFilter('save', function($self, $params, $chain) {

	$check = (Auth::check('default')) ?: null;

	if($check) {

		$user_id = $check['id'];
		$params['data']['user_id'] = $user_id;
		
	}

	return $chain->next($self, $params, $chain);
});


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
