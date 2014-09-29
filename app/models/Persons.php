<?php

namespace app\models;

class Persons extends \lithium\data\Model {

	public $hasMany = array(
		'PersonsHistories',
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

Persons::applyFilter('delete', function($self, $params, $chain) {

	$person_id = $params['entity']->id;

	Archives::find('all', array(
		'conditions' => array('id' => $person_id)
	))->delete();

	//Delete any relationships and artworks

	$components = Components::find('all', array(
		'conditions' => array('archive_id1' => $person_id)
	));

    $archive_ids = $components->map(function($c) {
        return $c->archive_id2;
    }, array('collect' => false));

    if (!empty($archive_ids)) {
        Works::find('all', array(
            'conditions' => array('Works.id' => $archive_ids),
        ))->delete();

        // TODO Delete any documents attached to these artworks
    }

    $components->delete();

	ArchivesDocuments::find('all', array(
		'conditions' => array('archive_id' => $person_id)
	))->delete();

	ArchivesLinks::find('all', array(
		'conditions' => array('archive_id' => $person_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});

?>
