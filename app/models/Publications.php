<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;
use lithium\core\Environment;

class Publications extends \lithium\data\Model {

	public $hasMany = array(
		'PublicationsHistories',
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
		return array(
			"Monograph",
			"Catalogue",
			"Artist's Book",
			"Newspaper",
			"Magazine",
			"Journal",
			"Essay in Book",
			"Website",
			"Other"
		);
	}

	public static function types() {
		$types = array(
			"Interview",
		);

		$publications = Environment::get('publications');

		if ($publications) {
			$types = isset($publications['types']) ? $publications['types'] : $types;
		}

		return $types;
	}

	public function byline($entity) {

    	$author = $entity->author;
		$editor = $entity->editor ? $entity->editor . ' (ed.)' : '';

		$byline = array_filter(array($author, $editor));

		return implode(', ', $byline);
	}

    public function citation($entity) {
    	$years = Publications::dates($entity);

		$byline = $entity->byline();
    	$author_years = $years ? $byline . ' (' . $years . ')' : $byline;
    	$title = '<em>' . $entity->title . '</em>';
    	$title = $entity->url ? "<a href='$entity->url'>$title</a>" : $title;
		$publication = $entity->pages ? $entity->publisher . ', ' . $entity->pages : $entity->publisher;

		$citation = array_filter(array(
			$author_years,
			$title,
			$publication
		));

    	return implode('. ', $citation) . '.';
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

Publications::applyFilter('delete', function($self, $params, $chain) {

	$publication_id = $params['entity']->id;

	Archives::find('all', array(
		'conditions' => array('id' => $publication_id)
	))->delete();

	//Delete any relationships
	Components::find('all', array(
		'conditions' => array('archive_id2' => $publication_id)
	))->delete();

	//Delete any relationships
	ArchivesDocuments::find('all', array(
		'conditions' => array('archive_id' => $publication_id)
	))->delete();

	ArchivesLinks::find('all', array(
		'conditions' => array('archive_id' => $publication_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});

?>
