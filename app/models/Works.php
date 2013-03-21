<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;
use lithium\security\Auth;

class Works extends \lithium\data\Model {

	public $hasMany = array(
		'WorksHistories',
		'WorksLinks',
		'Components' => array(
			'to' => 'app\models\Components',
			'key' => array(
				'id' => 'archive_id2',
		)),
		'ArchivesDocuments' => array(
			'to' => 'app\models\ArchivesDocuments',
			'key' => array(
				'id' => 'archive_id',
		)),
	);

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

	public static function classifications() {
		return array(
			"Audio",
			"Furniture",
			"Installation",
			"Object",
			"Painting",
			"Photography",
			"Porcelain",
			"Poster and Design",
			"Pottery",
			"Video",
			"Works on Paper",
		);
	}

    public function dimensions($entity) {
    	$hwd = array_filter(array($entity->height, $entity->width, $entity->depth));
    	$measures = $hwd ? implode(' × ', $hwd) . ' cm' : '';
    	$diameter = $entity->diameter ? 'Ø ' . $entity->diameter . ' cm' : '';
    	$running_time = $entity->running_time ? $entity->running_time : '';
    	$dimensions = array_filter(array($measures, $diameter, $running_time));
    	return implode(', ', $dimensions);
    }
    
    public function notes($entity) {
						
		$quantity = $entity->quantity ? 'Quantity: ' . $entity->quantity : '';
		$remarks =  $entity->remarks ? $entity->remarks : '';
		
		$info = array_filter(array(
			$quantity,
			$remarks,
		));
		
		return implode('<br/>', $info);
	}

	public function documents($entity,  $type = 'all', $conditions = null) {
		
		$conditions['archive_id'] = $entity->id;

		$documents = Documents::find($type, array(
			'with' => array(
				'ArchivesDocuments',
				'Formats'
			),
			'conditions' => $conditions,
		));

		return $documents;
	}

}

Works::applyFilter('save', function($self, $params, $chain) {

	if(!$params['entity']->exists()) {

		$params['data']['controller'] = 'works';

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

Works::applyFilter('delete', function($self, $params, $chain) {

	$work_id = $params['entity']->id;
	
	Archives::find('all', array(
		'conditions' => array('id' => $work_id)
	))->delete();

	//Delete any relationships
	Components::find('all', array(
		'conditions' => array('archive_id2' => $work_id)
	))->delete();
	
	ArchivesDocuments::find('all', array(
		'conditions' => array('archive_id' => $work_id)
	))->delete();

	WorksLinks::find('all', array(
		'conditions' => array('work_id' => $work_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});


//TODO We don't want this code in Works either, it should eventually be moved to Archives and Components
Works::applyFilter('save', function($self, $params, $chain) {

	$result = $chain->next($self, $params, $chain);

	if(!$params['entity']->exists()) {

		$work_id = $params['data']['id'];

	} else {
		$work_id = $params['entity']->id;
	}

	$url = isset($params['data']['url']) ? $params['data']['url'] : null;
	$title = isset($params['data']['title']) ? $params['data']['title'] : null;

	if ($work_id && $url) {
		$works_links = WorksLinks::create();
		$data = compact('work_id', 'url', 'title');
		$works_links->save($data);
	}

	return $result;
});

//TODO We don't really want this code in Works
//Some models allow a URL to be saved during an add, which creates a Link object and/or a link relation
//In order for the validation to work properly in the Archives model, the URL cannot be unset
Works::applyFilter('save', function($self, $params, $chain) {

	if (!isset($params['data']['url'])) {
		$params['data']['url'] = '';
	}

	return $chain->next($self, $params, $chain);

});

//FIXME validation for the dates is failing if they are NULL, which is true of many unit tests
//So let's make sure the value is at least empty if it is not set
Works::applyFilter('save', function($self, $params, $chain) {

	if(!isset($params['data']['earliest_date'])) {
		$params['data']['earliest_date'] = '';
	}

	if(!isset($params['data']['latest_date'])) {
		$params['data']['latest_date'] = '';
	}

	return $chain->next($self, $params, $chain);

});

//TODO we don't want this code directly in Works
Works::applyFilter('save', function($self, $params, $chain) {

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
