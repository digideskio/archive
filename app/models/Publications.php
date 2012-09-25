<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;

class Publications extends \app\models\Archives {

	public $hasMany = array('PublicationsDocuments');

    public function citation($entity) {
    	$years = Publications::years($entity);
    	
    	$author = $entity->author;
    	$author_years = $years ? $author . ' (' . $years . ')' : $author;
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
		
		$conditions['publication_id'] = $entity->id;

		$documents = Documents::find($type, array(
			'with' => array(
				'PublicationsDocuments',
				'Formats'
			),
			'conditions' => $conditions,
		));

		return $documents;
	}
    
}

?>
