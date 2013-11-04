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
		'id' => array(
			array('notEmpty', 'message' => 'This field may not be empty.')
		),
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

	$order = '(CASE WHEN artist = \'\' THEN 1 ELSE 0 END), artist ASC, earliest_date DESC, Archives.name, materials';

	$artworks = Environment::get('artworks');

	if ($artworks && isset($artworks['order'])) {
		$order = $artworks['order'];
	}

	$params['options']['order'] = $order;
	$params['options']['with'] = 'Archives';

	$data = $chain->next($self, $params, $chain);
	return $data ?: null;
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

?>
