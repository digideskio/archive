<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;

class Exhibitions extends \lithium\data\Model {

	public $hasMany = array('ExhibitionsWorks', 'ExhibitionsDocuments', 'ExhibitionsLinks', 'ExhibitionsHistories');

	public $belongsTo = array(
		'Archives' => array (
			'to' => 'app\models\Archives',
			'key' => 'id'
	));

	public $validates = array(
		'title' => array(
			array('notEmpty', 'message' => 'Please enter a title.')
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

	public function location($entity) {
	
		$venue = $entity->venue ? '<strong>' . $entity->venue . '</strong>' : '';
		$location = array_filter(array($venue, $entity->city, $entity->country));
		return implode(', ', $location);
	
	}
}

Exhibitions::applyFilter('save', function($self, $params, $chain) {

	if(!$params['entity']->exists()) {

		$params['data']['controller'] = 'exhibitions';

		$archive = Archives::create();
		$success = $archive->save($params['data']);

		$params['data']['id'] = $archive->id;

	} else {
		$archive = Archives::find('first', array(
			'conditions' => array('id' => $params['entity']->id)
		));

		$success = $archive->save($params['data']);
	}

	return $chain->next($self, $params, $chain);
	
});

Exhibitions::applyFilter('delete', function($self, $params, $chain) {

	$exhibition_id = $params['entity']->id;

	Archives::find('all', array(
		'conditions' => array('id' => $exhibition_id)
	))->delete();
		
	//Delete any relationships
	ExhibitionsWorks::find('all', array(
		'conditions' => array('exhibition_id' => $exhibition_id)
	))->delete();
	
	ExhibitionsDocuments::find('all', array(
		'conditions' => array('exhibition_id' => $exhibition_id)
	))->delete();

	ExhibitionsLinks::find('all', array(
		'conditions' => array('exhibition_id' => $exhibition_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});

//TODO We don't want this code in Exhibitions either, it should eventually be moved to Archives and Components
Exhibitions::applyFilter('save', function($self, $params, $chain) {

	$result = $chain->next($self, $params, $chain);

	if(!$params['entity']->exists()) {

		$exhibition_id = $params['data']['id'];

	} else {
		$exhibition_id = $params['entity']->id;
	}

	$url = isset($params['data']['url']) ? $params['data']['url'] : null;
	$title = isset($params['data']['title']) ? $params['data']['title'] : null;

	if ($exhibition_id && $url) {
		$exhibitions_links = ExhibitionsLinks::create();
		$data = compact('exhibition_id', 'url', 'title');
		$exhibitions_links->save($data);
	}

	return $result;
});

//TODO We don't really want this code in Exhibitions
//Some models allow a URL to be saved during an add, which creates a Link object and/or a link relation
//In order for the validation to work properly in the Archives model, the URL cannot be unset
Exhibitions::applyFilter('save', function($self, $params, $chain) {

	if (!isset($params['data']['url'])) {
		$params['data']['url'] = '';
	}

	return $chain->next($self, $params, $chain);

});

//FIXME validation for the dates is failing if they are NULL, which is true of many unit tests
//So let's make sure the value is at least empty if it is not set
Exhibitions::applyFilter('save', function($self, $params, $chain) {

	if(!isset($params['data']['earliest_date'])) {
		$params['data']['earliest_date'] = '';
	}

	if(!isset($params['data']['latest_date'])) {
		$params['data']['latest_date'] = '';
	}

	return $chain->next($self, $params, $chain);

});

//TODO we don't want this code directly in Exhibitions
Exhibitions::applyFilter('save', function($self, $params, $chain) {

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


?>
