<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;
use lithium\security\Auth;
use lithium\core\Environment;

class Works extends \lithium\data\Model {

	public $hasMany = array(
		'WorksHistories',
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
		'ArchivesLinks' => array(
			'to' => 'app\models\ArchivesLinks',
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

		$classifications = array(
			'Audio' => array(
				'class' => 'four-d'
			),
			'Installation' => array(
				'class' => 'two-d three-d' 
			),
			'Object' => array(
				'class' => 'two-d three-d'
			),
			'Painting' => array(
				'class' => 'two-d'
			),
			'Photography' => array(
				'class' => 'two-d'
			),
			'Sculpture' => array(
				'class' => 'two-d three-d'
			),
			'Video' => array(
				'class' => 'four-d'
			),
			'Works on Paper' => array(
				'class' => 'two-d'
			),
			'Other' => array(
				'class' => 'two-d three-d four-d'
			),
		);

		$artworks = Environment::get('artworks');

		if ($artworks) {
			$classifications = isset($artworks['classifications']) ? $artworks['classifications'] : $classifications;
		}

		return $classifications;
	}

	public static function attributes() {
		$attributes = array(
			'signed',
			'framed',
			'certification',
			'edition',
			'packing_type',
			'pack_price',
			'pack_price_per',
			'in_time',
			'in_from',
			'in_operator',
			'buy_price',
			'buy_price_per',
			'sell_price',
			'sell_price_per',
			'sell_date',
		);

		return $attributes;
	}

	public function attribute($entity, $attribute) {

		$attributes_json = $entity->attributes;

		$attributes = $attributes_json ? json_decode($attributes_json, true) : array();

		return isset($attributes[$attribute]) ? $attributes[$attribute] : '';

	}

	public function artists($entity) {
		$artist = $entity->artist;
		$artist_native_name = $entity->artist_native_name ? "($entity->artist_native_name)" : '';

		$artists = array_filter(array(
			$artist,
			$artist_native_name
		));

		return implode(' ', $artists);
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
						
		$annotation = $entity->annotation ? '<em>' . $entity->annotation . '</em>' : '';
		$quantity = $entity->quantity ? 'Quantity: ' . $entity->quantity : '';
		$remarks =  $entity->remarks ? $entity->remarks : '';

		$edition = $entity->attribute('edition') ? 'Edition: ' . $entity->attribute('edition') : '';
		$signed = $entity->attribute('signed') ? '<span class="label label-info">Signed</span>' : '';
		$framed = $entity->attribute('framed') ? '<span class="label label-inverse">Framed</span>' : '';
		$certification = $entity->attribute('certification') ? '<span class="label label-success">Certification</span>' : '';
		
		$info = array_filter(array(
			$annotation,
			$edition,
			$quantity,
			$remarks,
			$signed,
			$framed,
			$certification
		));
		
		return implode('<br/>', $info);
	}

	public function inventory($entity) {

		$buy_price = $entity->attribute('buy_price') ? 'Purchase Price: ' . $entity->attribute('buy_price') . ' <small>' . $entity->attribute('buy_price_per'). '</small>' : '';
		$sell_price = $entity->attribute('sell_price') ? 'Sale Price: ' . $entity->attribute('sell_price') . ' <small>' . $entity->attribute('sell_price_per'). '</small>' : '';

		$sell_date = $entity->attribute('sell_date') ? 'Date of Sale: ' . $entity->attribute('sell_date')  : '';

		$packing_type = $entity->attribute('packing_type') ? 'Packing Type: ' . $entity->attribute('packing_type')  : '';
		$pack_price = $entity->attribute('pack_price') ? 'Packing Cost: ' . $entity->attribute('pack_price') . ' <small>' . $entity->attribute('pack_price_per'). '</small>' : '';
		$in_time = $entity->attribute('in_time') ? 'Received Time: ' . $entity->attribute('in_time')  : '';
		$in_from = $entity->attribute('in_from') ? 'Sent From: ' . $entity->attribute('in_from')  : '';
		$in_operator = $entity->attribute('in_operator') ? 'Received By: ' . $entity->attribute('in_operator')  : '';

		$info = array_filter(array(
			$buy_price,
			$sell_price,
			$sell_date,
			$packing_type,
			$pack_price,
			$in_time,
			$in_from,
			$in_operator
		));

		return implode('<br/>', $info);

	}

	public function documents($entity,  $type = 'all', $conditions = null) {
		
		$conditions['ArchivesDocuments.archive_id'] = $entity->id;

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

Works::finder('artworks', function($self, $params, $chain) {

	$order = '(CASE WHEN artist = \'\' THEN 1 ELSE 0 END), artist ASC, earliest_date DESC, title, materials';

	$artworks = Environment::get('artworks');

	if ($artworks && isset($artworks['order'])) {
		$order = $artworks['order'];
	}

	$params['options']['order'] = $order;
	$data = $chain->next($self, $params, $chain);
	return $data ?: null;
});

Works::applyFilter('save', function($self, $params, $chain) {

	if(!$params['entity']->exists()) {

		$params['data']['controller'] = 'works';

		$archive = Archives::create();
		$success = $archive->save($params['data']);

		$params['data']['id'] = $archive->id;

		if (isset($params['data']['documents']) && $params['data']['documents']) {
			$document_ids = $params['data']['documents'];

			$documents = Documents::find('all', array(
				'fields' => array('id'),
				'conditions' => array('Documents.id' => $document_ids),
			));

			$archive_id = $archive->id;

			foreach ($documents as $doc) {
				$document_id = $doc->id;
				$doc_data = compact('archive_id', 'document_id');
				$ad = ArchivesDocuments::create();
				$ad->save($doc_data);
			}

		}

	} else {
		$archive = Archives::find('first', array(
			'conditions' => array('id' => $params['entity']->id)
		));

		$success = $archive->save($params['data']);
	}

	return $chain->next($self, $params, $chain);
	
});

Works::applyFilter('save', function($self, $params, $chain) {

	$attributes_json = $params['entity']->attributes;

	$attributes = $attributes_json ? json_decode($attributes_json, true) : array();

	$work_attrs = Works::attributes();

	foreach ($work_attrs as $att) {

		if (isset($params['data'][$att]) && $params['data'][$att]) {
			$attributes[$att] = $params['data'][$att];
		} else {
			unset($attributes[$att]);
		}

	}

	$params['data']['attributes'] = json_encode($attributes);

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

	ArchivesLinks::find('all', array(
		'conditions' => array('archive_id' => $work_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});


//TODO We don't want this code in Works either, it should eventually be moved to Archives and Components
Works::applyFilter('save', function($self, $params, $chain) {

	$result = $chain->next($self, $params, $chain);

	if(!$params['entity']->exists()) {

		$archive_id = $params['data']['id'];

	} else {
		$archive_id = $params['entity']->id;
	}

	$url = isset($params['data']['url']) ? $params['data']['url'] : null;
	$title = isset($params['data']['title']) ? $params['data']['title'] : null;

	if ($archive_id && $url) {
		$archives_link = ArchivesLinks::create();
		$data = compact('archive_id', 'url', 'title');
		$archives_link->save($data);
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
