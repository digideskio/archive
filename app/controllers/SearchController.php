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

use lithium\action\DispatchException;
use lithium\security\Auth;
use lithium\core\Environment;

class SearchController extends \lithium\action\Controller {

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

		$query = '';

		$limit = 40;

		if (Environment::get('search')) {
			$search = Environment::get('search');
			$limit = isset($search['limit']) ? $search['limit'] : $limit;
		}

		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : $limit;

		if (isset($data['query']) && $data['query']) {
			$query = trim($data['query']);
        
			$order = array('Archives.earliest_date' => 'DESC');

			$artwork_ids = array();

			$fields = array('title', 'artist', 'artist_native_name', 'classification', 'earliest_date', 'materials', 'remarks', 'creation_number', 'annotation');

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

			$work_conditions = $artwork_ids ? array('Works.id' => $artwork_ids) : array('title' => $query);

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

			$fields = array('title', 'architect', 'client', 'project_lead', 'earliest_date', 'status', 'location', 'city', 'country', 'remarks');

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

			$architecture_conditions = $architecture_ids ?  array('Architectures.id' => $architecture_ids) : array('title' => $query);

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

			$fields = array('title', 'venue', 'curator', 'earliest_date', 'city', 'country', 'remarks');

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

			$exhibition_conditions = $exhibition_ids ? array('Exhibitions.id' => $exhibition_ids) : array('title' => $query);

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

			$fields = array('title', 'author', 'publisher', 'editor', 'earliest_date', 'subject', 'language', 'storage_location', 'storage_number', 'publication_number');

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

			$publication_conditions = $publication_ids ? array('Publications.id' => $publication_ids) : array('title' => $query);

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
			'query',
			'limit',
			'architecture',
			'auth'
		);
        
	}
	

}
