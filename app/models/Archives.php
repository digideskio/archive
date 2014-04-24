<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;
use lithium\security\Auth;

class Archives extends \lithium\data\Model {

	public $belongsTo = array("Users");

	public $hasMany = array("ArchivesDocuments", "ArchivesLinks");

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
		'name' => array(
			array('notEmpty', 'message' => "You can't leave this blank."),
		),
		'latest_date' => array(
			array('date',
				'skipEmpty' => true,
				'message' => 'Please enter a valid date.',
				'format' => 'ymd'
			)
		),
		'earliest_date' => array(
			array('date',
				'skipEmpty' => true,
				'message' => 'Please enter a valid date.',
				'format' => 'ymd'
			)
		)
    );

	/**
	 * Sample schema and query. For now we rely on the schema being lazy-loaded
	 */

//	protected $_schema = array(
//		'id'                   => array('type' => 'integer', 'null' => 'false'),
//		'name'                 => array('type' => 'string', 'null' => false),
//		'native_name'          => array('type' => 'string', 'null' => false),
//		'language_code'        => array('type' => 'string', 'null' => false),
//		'controller'           => array('type' => 'string', 'null' => false),
//		'classification'       => array('type' => 'string', 'null' => false),
//		'type'                 => array('type' => 'string', 'null' => false),
//		'catalog_level'        => array('type' => 'string', 'null' => false),
//		'description'          => array('type' => 'string', 'null' => false),
//		'slug'                 => array('type' => 'string', 'null' => false),
//		'earliest_date'        => array('type' => 'date', 'null' => false, 'default' => ''),
//		'earliest_date_format' => array('type' => 'string', 'null' => false),
//		'latest_date'          => array('type' => 'date', 'null' => false, 'default' => ''),
//		'latest_date_format'   => array('type' => 'string', 'null' => false),
//		'date_created'         => array('type' => 'date', 'null' => false),
//		'date_modified'        => array('type' => 'date', 'null' => false),
//		'user_id'              => array('type' => 'integer', 'null' => 'false'),
//		'parent_id'            => array('type' => 'integer', 'null' => 'false'),
//	);

//	protected $_query = array(
//		'fields' => array(
//			'id',
//			'name',
//			'native_name',
//			'language_code',
//			'controller',
//			'classification',
//			'type',
//			'catalog_level',
//			'description',
//			'slug',
//			'earliest_date',
//			'earliest_date_format',
//			'latest_date',
//			'latest_date_format',
//			'date_created',
//			'date_modified',
//			'user_id',
//			'parent_id',
//		),
//	);

	/**
	 * This function takes a string and, if the string is a date, returns it in
	 * the form 'Y-m-d', along with a representation of the original input's
	 * format as either 'Y-m-d', 'M Y', or 'Y'
	 *
	 * In addition to the standard date validation formats, this function also
	 * accepts dates in the form of 'Y' or 'Y-m', and fills in any missing
	 * month or day as '01';
	 */
	public static function filterDate($date) {

		$format = '';

		if (preg_match('/^\d{4}$/', $date)) {
			$date .= '-01-01';
			$format = 'Y';
		} elseif (preg_match('/^\d{4}-\d{2}$/', $date)) {
			$date .= "-01";
			$format = 'M Y';
		} else {

			if (Validator::isDate($date, array('dmy', 'mdy', 'ymd', 'dMy', 'Mdy'))) {
				$format = 'Y-m-d';
			}

			if (Validator::isDate($date, array('My', 'my'))) {
				$format = 'M Y'; // The format needs to be 'M Y' since 'Y-m' cannot be parsed as a valid date
			}
		}

		if (Validator::isDate($date, array('dmy', 'mdy', 'ymd', 'dMy', 'Mdy', 'My', 'my'))) {
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

/**
 * Date Modified Filter
 */

Archives::applyFilter('save', function($self, $params, $chain) {
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

/**
 * Date Filters
 *
 * Significant dates may be input as a year, as a month and year, or as a valid
 * day, month, and year. Either date may also be left blank. The filter runs
 * a similar procedure for data supplied to the create and save methods. For
 * the create method, do not parse the date if a date format is already
 * specified, since the data is being supplied as-is (this scenario only arises
 * in the testing suite). If a date is not set, it must in the final instance be
 * set to a blank string, otherwise the validation will not pass (the skipEmpty
 * directive does not mean "skip NULL", unfortunately).
 */

Archives::applyFilter('create', function($self, $params, $chain) {

	if (isset($params['data']['earliest_date']) && $params['data']['earliest_date'] != '') {

		// If a date format has not been specified, attempt to parse the date
		if (!isset($params['data']['earliest_date_format'])) {
			$earliest_date = $params['data']['earliest_date'];
			$earliest_date_filtered = Archives::filterDate($earliest_date);
			$params['data']['earliest_date'] = $earliest_date_filtered['date'];
			$params['data']['earliest_date_format'] = $earliest_date_filtered['format'];
		}
	} else {
		//FIXME validation for the dates is failing if they are NULL, which is true of many unit tests
		//So let's make sure the value is at least empty if it is not set
		//This has only been necessary since the extra date format code was added
		$params['data']['earliest_date'] = '';
	}

	if (isset($params['data']['latest_date']) && $params['data']['latest_date'] != '') {

		// If a date format has not been specified, attempt to parse the date
		if (!isset($params['data']['latest_date_format'])) {
			$latest_date = $params['data']['latest_date'];
			$latest_date_filtered = Archives::filterDate($latest_date);
			$params['data']['latest_date'] = $latest_date_filtered['date'];
			$params['data']['latest_date_format'] = $latest_date_filtered['format'];
		}
	} else {
		$params['data']['latest_date'] = '';
	}

	return $chain->next($self, $params, $chain);

});

Archives::applyFilter('save', function($self, $params, $chain) {

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

/**
 * Slug Filter
 */

Archives::applyFilter('save', function($self, $params, $chain) {

	if(!$params['entity']->exists() && isset($params['data']['name'])) {

		$name = $params['data']['name'];

		if( isset($params['data']['venue']) ) {
			$name = $name . " " . $params['data']['venue'];
		}

		/* FIXME we should respect that slugs are varchar(255). Limit slugs to 200 characters? */
		$slugged = Inflector::slug($name);

        // Set the slug to something generic if one cannot be generated from the name
        $slug = !empty($slugged) ? $slugged : 'Archive';

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


/**
 * Language Filter
 *
 * Transform a langauge string to a language code.
 */

Archives::applyFilter('save', function($self, $params, $chain) {

	if (isset($params['data']['language'])) {

		$lang = $params['data']['language'];

		$language = Languages::find('first', array(
			'conditions' => "'$lang' LIKE CONCAT('%', name, '%')"
		));

		if($language) {

			$params['data']['language_code'] = $language->code;

		}
	}

	return $chain->next($self, $params, $chain);

});

/**
 * User Filter
 *
 * Save the identity of the user who is creating or updating this record.
 */

Archives::applyFilter('save', function($self, $params, $chain) {

	$check = (Auth::check('default')) ?: null;

	if($check && isset($check['username'])) {

		$user = Users::find('first', array(
			'conditions' => array('username' => $check['username'])
		));

		if ($user) {
			$params['data']['user_id'] = $user->id;
		}

	}

	return $chain->next($self, $params, $chain);
});
