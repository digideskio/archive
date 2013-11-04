<?php

namespace app\controllers;

use app\models\Users;
use app\models\Roles;

use app\models\Works;
use app\models\Architectures;
use app\models\Documents;
use app\models\WorksDocuments;
use app\models\Albums;
use app\models\Exhibitions;
use app\models\Publications;
use app\models\Links;

use lithium\action\DispatchException;
use lithium\security\Auth;
use lithium\core\Environment;
use lithium\storage\Session;

class SearchController extends \lithium\action\Controller {

	public $rules = array(
		'index' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
	);

	public function index() {
    
    	// Check authorization
	    $check = (Auth::check('default')) ?: null;
	
		// If the user is not authorized, redirect to the login screen
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
		$data = $this->request->data ?: $this->request->query;

		$works = array();
		$architectures = array();
		$exhibitions = array();
		$publications = array();
		$documents = array();
		$links = array();

		$query = '';

		$limit = 40;

		if (Environment::get('search')) {
			$search = Environment::get('search');
			$limit = isset($search['limit']) ? $search['limit'] : $limit;
		}

		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : $limit;

		if (isset($data['query']) && $data['query']) {
			$query = trim($data['query']);

			// Save the query term as a session variable in the custom storage
			Session::write('query', $query, array('name' => 'custom'));
        
			$order = array('Archives.earliest_date' => 'DESC');

			$artwork_ids = array();

			$fields = array('Archives.name', 'artist', 'artist_native_name', 'classification', 'earliest_date', 'materials', 'remarks', 'creation_number', 'annotation');

			foreach ($fields as $field) {
				$matching_works = Works::find('artworks', array(
					'with' => 'Archives',
					'fields' => 'Works.id',
					'conditions' => array("$field" => array('LIKE' => "%$query%")),
				));

				if ($matching_works) {
					$matching_ids = $matching_works->map(function($mw) {
						return $mw->id;
					}, array('collect' => false));

					$artwork_ids = array_unique(array_merge($artwork_ids, $matching_ids));
				}
			}

			$work_conditions = $artwork_ids ? array('Works.id' => $artwork_ids) : array('Archives.name' => $query);

			$filter = '';

			if (Environment::get('artworks')) {
				$artworks = Environment::get('artworks');
				$filter = isset($artworks['filter']) ? $artworks['filter'] : '';
			}

			$work_conditions = $filter ? array_merge($filter, $work_conditions) : $work_conditions;

			$works_total = Works::count('artworks', array(
				'with' => 'Archives',
				'conditions' => $work_conditions,
			));

			//Interpret any non-integer limit to mean 'All' results
			$work_limit = !(intval($limit)) ? $works_total : $limit;

			$works = Works::find('artworks', array(
				'with' => 'Archives',
				'conditions' => $work_conditions,
				'limit' => $work_limit,
			));

			$architecture_ids = array();

			$fields = array('Archives.name', 'architect', 'client', 'project_lead', 'earliest_date', 'status', 'location', 'city', 'country', 'remarks');

			foreach ($fields as $field) {
				$matching_works = Architectures::find('all', array(
					'with' => 'Archives',
					'fields' => 'Architectures.id',
					'conditions' => array($field => array('LIKE' => "%$query%")),
				));

				if ($matching_works) {
					$matching_ids = $matching_works->map(function($mw) {
						return $mw->id;
					}, array('collect' => false));

					$architecture_ids = array_unique(array_merge($architecture_ids, $matching_ids));
				}
			}

			$architecture_conditions = $architecture_ids ?  array('Architectures.id' => $architecture_ids) : array('Archives.name' => $query);

			$architectures_total = Architectures::count('all', array(
				'with' => 'Archives',
				'conditions' => $architecture_conditions,
			));

			$architecture_limit = !(intval($limit)) ? $architectures_total : $limit;

			$architectures = Architectures::find('all', array(
				'with' => 'Archives',
				'order' => $order,
				'conditions' => $architecture_conditions,
				'limit' => $architecture_limit,
			));

			$exhibition_ids = array();

			$fields = array('Archives.name', 'venue', 'curator', 'earliest_date', 'city', 'country', 'remarks');

			foreach ($fields as $field) {
				$matching_exhibits = Exhibitions::find('all', array(
					'with' => 'Archives',
					'fields' => 'Exhibitions.id',
					'conditions' => array("$field" => array('LIKE' => "%$query%")),
				));

				if ($matching_exhibits) {
					$matching_ids = $matching_exhibits->map(function($me) {
						return $me->id;
					}, array('collect' => false));

					$exhibition_ids = array_unique(array_merge($exhibition_ids, $matching_ids));
				}
			}

			$exhibition_conditions = $exhibition_ids ? array('Exhibitions.id' => $exhibition_ids) : array('Archives.name' => $query);

			$exhibitions_total = Exhibitions::count('all', array(
				'with' => array('Archives', 'Components'),
				'conditions' => $exhibition_conditions,
			));

			$exhibition_limit = !(intval($limit)) ? $exhibitions_total : $limit;

			$exhibitions = Exhibitions::find('all', array(
				'with' => array('Archives', 'Components'),
				'order' => $order,
				'conditions' => $exhibition_conditions,
				'limit' => $exhibition_limit,
			));

			$publication_ids = array();

			$fields = array('Archives.name', 'author', 'publisher', 'editor', 'earliest_date', 'subject', 'language', 'storage_location', 'storage_number', 'publication_number');

			foreach ($fields as $field) {
				$matching_pubs = Publications::find('all', array(
					'with' => 'Archives',
					'fields' => 'Publications.id',
					'conditions' => array("$field" => array('LIKE' => "%$query%")),
				));

				if ($matching_pubs) {
					$matching_ids = $matching_pubs->map(function($mp) {
						return $mp->id;
					}, array('collect' => false));

					$publication_ids = array_unique(array_merge($publication_ids, $matching_ids));
				}
			}

			$publication_conditions = $publication_ids ? array('Publications.id' => $publication_ids) : array('Archives.name' => $query);

			$publications_total = Publications::count('all', array(
				'with' => 'Archives',
				'conditions' => $publication_conditions,
			));

			$publication_limit = !(intval($limit)) ? $publications_total : $limit;

			$publications = Publications::find('all', array(
				'with' => 'Archives',
				'order' => $order,
				'conditions' => $publication_conditions,
				'limit' => $publication_limit,
			));

			$document_ids = array();

			$fields = array('title', 'date_created', 'repository', 'credit', 'remarks');

			foreach ($fields as $field) {
				$matching_docs = Documents::find('all', array(
					'fields' => 'Documents.id',
					'conditions' => array($field => array('LIKE' => "%$query%")),
				));

				if ($matching_docs) {
					$matching_ids = $matching_docs->map(function($md) {
						return $md->id;
					}, array('collect' => false));

					$document_ids = array_unique(array_merge($document_ids, $matching_ids));
				}
			}

			$doc_conditions = $document_ids ?  array('Documents.id' => $document_ids) : array('title' => $query);

			$documents_total = Documents::count('all', array(
				'conditions' => $doc_conditions,
			));

			$document_limit = !(intval($limit)) ? $documents_total : $limit;

			$documents = Documents::find('all', array(
				'conditions' => $doc_conditions,
				'limit' => $document_limit,
			));

			$link_ids = array();

			$link_fields = array('title', 'url', 'description');

			foreach ($link_fields as $field) {
				$matching_links = Links::find('all', array(
					'fields' => 'Links.id',
					'conditions' => array("$field" => array('LIKE' => "%$query%")),
				));

				if ($matching_links) {
					$matching_ids = $matching_links->map(function($match) {
						return $match->id;
					}, array('collect' => false));

					$link_ids = array_unique(array_merge($link_ids, $matching_ids));
				}
			}

			$link_conditions = $link_ids ? array('Links.id' => $link_ids) : array('title' => $query);

			$links_total = Links::count(array(
				'conditions' => $link_conditions,
			));

			$link_limit = !(intval($limit)) ? $links_total : $limit;

			$links = Links::find('all', array(
				'with' => array('ArchivesLinks', 'ArchivesLinks.Archives'),
				'conditions' => $link_conditions,
				'order' => array('date_modified' => 'DESC'),
				'limit' => $limit,
			));

			$limit = !(intval($limit)) ? max(array($works_total, $architectures_total, $exhibitions_total, $publications_total, $documents_total)) : $limit;
		}

		$architecture = Environment::get('architecture');		
        
        return compact(
			'works',
			'works_total',
			'architectures',
			'architectures_total',
			'exhibitions',
			'exhibitions_total',
			'publications',
			'publications_total',
			'documents',
			'documents_total',
			'links',
			'links_total',
			'query',
			'limit',
			'architecture',
			'auth'
		);
        
	}
	

}
