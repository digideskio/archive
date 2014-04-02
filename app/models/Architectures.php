<?php

namespace app\models;

use lithium\util\Inflector;

class Architectures extends \lithium\data\Model {

	public $hasMany = array(
		'ArchitecturesHistories',
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
		'id' => array(
			array('notEmpty', 'message' => 'This field may not be empty.')
		),
	);

	public function dimensions($entity) {
		return $entity->area ? $entity->area . " square meters" : '';
	}

	public function documents($entity,  $type = 'all') {

		$documents = Documents::find($type, array(
			'with' => array(
				'ArchivesDocuments',
				'Formats'
			),
			'conditions' => array('ArchivesDocuments.archive_id' => $entity->id),
		));

		return $documents;
	}

}

Architectures::applyFilter('delete', function($self, $params, $chain) {

	$architecture_id = $params['entity']->id;

	Archives::find('all', array(
		'conditions' => array('id' => $architecture_id)
	))->delete();

	//Delete any relationships
	ArchivesDocuments::find('all', array(
		'conditions' => array('archive_id' => $architecture_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});

?>
