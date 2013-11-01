<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;

class Exhibitions extends \lithium\data\Model {

	public $hasMany = array(
		'ExhibitionsHistories',
		'Components' => array(
			'to' => 'app\models\Components',
			'key' => array(
				'id' => 'archive_id1',
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

}

Exhibitions::applyFilter('delete', function($self, $params, $chain) {

	$exhibition_id = $params['entity']->id;

	Archives::find('all', array(
		'conditions' => array('id' => $exhibition_id)
	))->delete();
		
	//Delete any relationships
	Components::find('all', array(
		'conditions' => array('archive_id1' => $exhibition_id)
	))->delete();
	
	ArchivesDocuments::find('all', array(
		'conditions' => array('archive_id' => $exhibition_id)
	))->delete();

	ArchivesLinks::find('all', array(
		'conditions' => array('archive_id' => $exhibition_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});

?>
