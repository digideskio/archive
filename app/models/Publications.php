<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;

class Publications extends \lithium\data\Model {

	public $hasMany = array(
		'PublicationsLinks', 
		'PublicationsHistories',
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
		return array(
			"Interview",
		);
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



Publications::applyFilter('save', function($self, $params, $chain) {

	if (isset($params['data']['language'])) {
	
		$lang = $params['data']['language'];

		$language = Languages::find('first', array(
			'conditions' => "'$lang' LIKE CONCAT('%', name, '%')" 
		));

		if($language) {

			$params['data']['language_code'] = $language->code;

		}
	}

	if(!$params['entity']->exists()) {

		$params['data']['controller'] = 'publications';

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

//TODO We don't really want this code in Publications
//Some models allow a URL to be saved during an add, which creates a Link object and/or a link relation
//In order for the validation to work properly in the Archives model, the URL cannot be unset
Publications::applyFilter('save', function($self, $params, $chain) {

	if (!isset($params['data']['url'])) {
		$params['data']['url'] = $params['entity']->url ?: '';
	}

	return $chain->next($self, $params, $chain);

});

//FIXME validation for the dates is failing if they are NULL, which is true of many unit tests
//So let's make sure the value is at least empty if it is not set
Publications::applyFilter('save', function($self, $params, $chain) {

	if(!isset($params['data']['earliest_date'])) {
		$params['data']['earliest_date'] = '';
	}

	if(!isset($params['data']['latest_date'])) {
		$params['data']['latest_date'] = '';
	}

	return $chain->next($self, $params, $chain);

});

//TODO we don't want this code directly in Publications
Publications::applyFilter('save', function($self, $params, $chain) {

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
