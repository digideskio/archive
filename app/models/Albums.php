<?php

namespace app\models;

use app\models\Dates;

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
		'title' => array(
			array('notEmpty', 'message' => 'Please enter a title.')
		),
	);		
}

Albums::applyFilter('save', function($self, $params, $chain) {

	if(!$params['entity']->exists()) {

		$params['data']['controller'] = 'albums';

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
