<?php

namespace app\models;

use lithium\util\Inflector;

class Albums extends \lithium\data\Model {

	public $belongsTo = array(
		'Archives' => array (
			'to' => 'app\models\Archives',
			'key' => 'id'
	));

	public $hasMany = array(
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
	);

	public $validates = array(
		'id' => array(
			array('notEmpty', 'message' => 'This field may not be empty.')
		),
	);
}

Albums::applyFilter('delete', function($self, $params, $chain) {

	$album_id = $params['entity']->id;

	Archives::find('all', array(
		'conditions' => array('id' => $album_id)
	))->delete();

	//Delete any relationships
	Components::find('all', array(
		'conditions' => array('archive_id1' => $album_id)
	))->delete();

	ArchivesDocuments::find('all', array(
		'conditions' => array('archive_id' => $album_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});

?>
