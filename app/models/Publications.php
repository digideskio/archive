<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;

class Publications extends \app\models\Archives {

	public $hasMany = array('PublicationsDocuments', 'PublicationsLinks');

	public static function types() {
		return array("Newspaper", "Magazine", "Catalogue", "Monograph", "Book", "Website");
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

Publications::applyFilter('delete', function($self, $params, $chain) {

	$publication_id = $params['entity']->id;
		
	//Delete any relationships
	PublicationsDocuments::find('all', array(
		'conditions' => array('publication_id' => $publication_id)
	))->delete();

	PublicationsLinks::find('all', array(
		'conditions' => array('publication_id' => $publication_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});

Publications::applyFilter('save', function($self, $params, $chain) {

	$result = $chain->next($self, $params, $chain);

	$publication_id = $params['entity']->id;
	$url = isset($params['data']['url']) ? $params['data']['url'] : null;
	$title = isset($params['data']['title']) ? $params['data']['title'] : null;

	if ($publication_id && $url) {
		$publications_links = PublicationsLinks::create();
		$data = compact('publication_id', 'url', 'title');
		$publications_links->save($data);
	}

	return $result;
});

?>
