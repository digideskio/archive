<?php

namespace app\controllers;

use app\models\Publications;
use app\models\PublicationsHistories;
use app\models\PublicationsLinks;

use app\models\Archives;
use app\models\ArchivesHistories;
use app\models\ArchivesDocuments;
use app\models\Documents;
use app\models\Exhibitions;

use app\models\Languages;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class PublicationsController extends \lithium\action\Controller {

	public $rules = array(
		'index' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'search' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'languages' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'subjects' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'histories' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'view' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'add' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
		'edit' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
		'attachments' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
		'history' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'delete' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
	);

	public function index() {

		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 40;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$order = array('earliest_date' => 'DESC');

		$conditions = array();

		$options = $this->request->query;

		if (isset($options['classification'])) {
			$conditions = array('Archives.classification' => $options['classification']);
		}

		$total = Publications::count('all', array(
			'with' => 'Archives',
			'conditions' => $conditions
		));
		
		$publications = Publications::find('all', array(
			'with' => 'Archives',
			'limit' => $limit,
			'order' => $order,
			'conditions' => $conditions,
			'page' => $page
		));

		$pub_classifications = Publications::classifications();

		return compact('publications', 'pub_classifications', 'total', 'page', 'limit', 'options');
	}
	
	public function histories() {

		$limit = 50;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$order = array('start_date' => 'DESC');
		$total = PublicationsHistories::count();
		$archives_histories = ArchivesHistories::find('all', array(
			'with' => array('Users', 'Archives'),
			'conditions' => array('ArchivesHistories.controller' => 'publications'),
			'limit' => $limit,
			'order' => $order,
			'page' => $page
		));
		
		$pub_classifications = Publications::classifications();

		return compact('archives_histories', 'total', 'page', 'limit', 'pub_classifications');
	}

	public function search() {
		
		$publications = array();

		$order = array('earliest_date' => 'DESC');

		$data = $this->request->data;

		$query = '';
		$condition = '';

		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 40;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$total = 0;

		$data = $this->request->data ?: $this->request->query;

		if (isset($data['query']) && $data['query']) {
			$condition = isset($data['condition']) ? $data['condition'] : '';

			$query = trim($data['query']);

			if ($condition) {
				$conditions = array("$condition" => array('LIKE' => "%$query%"));
			} else {

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

				$conditions = $publication_ids ? array('Publications.id' => $publication_ids) : array('title' => $query);
			}

			$publications = Publications::find('all', array(
				'with' => 'Archives',
				'order' => $order,
				'conditions' => $conditions,
				'limit' => $limit,
				'page' => $page
			));

			$total = Publications::count('all', array(
				'with' => 'Archives',
				'conditions' => $conditions,
			));

		}

		$pub_classifications = Publications::classifications();

		return compact('publications', 'pub_classifications', 'condition', 'query', 'total', 'page', 'limit');
	}

	public function languages() {

		$pub_languages = Publications::find('all', array(
			'fields' => array('language', 'count(language) as langs'),
			'group' => 'language',
			'conditions' => array('language' => array('!=' => '')),
			'order' => array('language' => 'ASC')
		));

		$languages = $pub_languages->map(function($pl) {
			return array('name' => $pl->language, 'count' => $pl->langs);
		}, array('collect' => false));

		$pub_classifications = Publications::classifications();

		return compact('pub_classifications', 'languages');

	}

	public function subjects() {

		$pub_subjects = Publications::find('all', array(
			'fields' => array('subject', 'count(subject) as subjects'),
			'group' => 'subject',
			'conditions' => array('subject' => array('!=' => '')),
			'order' => array('subject' => 'ASC')
		));

		$subjects = $pub_subjects->map(function($ps) {
			return array('name' => $ps->subject, 'count' => $ps->subjects);
		}, array('collect' => false));

		$pub_classifications = Publications::classifications();

		return compact('pub_classifications', 'subjects');

	}

	public function view() {
	
		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {
		
			//Get single record from the database where the slug matches the URL
			$publication = Publications::first(array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));

			if($publication) {
		
				$archives_documents = ArchivesDocuments::find('all', array(
					'with' => array(
						'Documents',
						'Documents.Formats'
					),
					'conditions' => array('archive_id' => $publication->id),
					'order' => array('Documents.slug' => 'ASC')
				));

				$publication_links = PublicationsLinks::find('all', array(
					'with' => array(
						'Links'
					),
					'conditions' => array('publication_id' => $publication->id),
					'order' => array('date_modified' =>  'DESC')
				));

				$exhibitions = Exhibitions::find('all', array(
					'with' => array('Archives', 'Components'),
					'conditions' => array(
						'Components.archive_id2' => $publication->id,
					),
					'order' => array('title' =>  'ASC')
				));
			
				//Send the retrieved data to the view
				return compact('publication', 'archives_documents', 'publication_links', 'exhibitions');

			}
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Publications::index'));
	}

	public function add() {

		$publication = Publications::create();

		if (($this->request->data) && $publication->save($this->request->data)) {
			//The slug has been saved with the Archive object, so let's look it up
			$archive = Archives::find('first', array(
				'conditions' => array('id' => $publication->id)
			));
			return $this->redirect(array('Publications::view', 'args' => array($archive->slug)));
		}

		$pub_classifications = Publications::classifications();
		$pub_types = Publications::types();

		$publications_locations = Publications::find('all', array(
			'fields' => 'location',
			'group' => 'location',
			'conditions' => array('location' => array('!=' => '')),
			'order' => array('location' => 'ASC')
		));

		$locations = $publications_locations->map(function($loc) {
			return $loc->location;
		}, array('collect' => false));

		$languages = Languages::find('all', array(
			'fields' => 'name',
		));

		$language_names = $languages->map(function($lang) {
			return $lang->name;
		}, array('collect' => false));

		$pub_classifications = Publications::classifications();

		$documents = array();

		if (isset($this->request->data['documents']) && $this->request->data['documents']) {
			$document_ids = $this->request->data['documents'];

			$documents = Documents::find('all', array(
				'with' => 'Formats',
				'conditions' => array('Documents.id' => $document_ids),
			));

		}

		return compact('publication', 'pub_classifications', 'pub_types', 'locations', 'language_names', 'documents');
	}

	public function edit() {

		$publication = Publications::first(array(
			'with' => 'Archives',
			'conditions' => array('Archives.slug' => $this->request->params['slug'])
		));

		$pub_classifications = Publications::classifications();
		$pub_types = Publications::types();

		if (!$publication) {
			return $this->redirect('Publications::index');
		}

		if (($this->request->data) && $publication->save($this->request->data)) {
			return $this->redirect(array('Publications::view', 'args' => array($publication->archive->slug)));
		}

		$archives_documents = ArchivesDocuments::find('all', array(
			'with' => array(
				'Documents',
				'Documents.Formats'
			),
			'conditions' => array('archive_id' => $publication->id),
			'order' => array('Documents.slug' => 'ASC')
		));

		$publication_links = PublicationsLinks::find('all', array(
			'with' => array(
				'Links'
			),
			'conditions' => array('publication_id' => $publication->id),
			'order' => array('date_modified' =>  'DESC')
		));

		$publications_locations = Publications::find('all', array(
			'fields' => 'location',
			'group' => 'location',
			'conditions' => array('location' => array('!=' => '')),
			'order' => array('location' => 'ASC')
		));

		$locations = $publications_locations->map(function($loc) {
			return $loc->location;
		}, array('collect' => false));

		$languages = Languages::find('all', array(
			'fields' => 'name',
		));

		$language_names = $languages->map(function($lang) {
			return $lang->name;
		}, array('collect' => false));

		return compact(
			'publication',
			'pub_classifications',
			'pub_types',
			'archives_documents',
			'publication_links',
			'locations',
			'language_names'
		);
	}

	public function attachments() {

		$publication = Publications::first(array(
			'with' => 'Archives',
			'conditions' => array('Archives.slug' => $this->request->params['slug'])
		));

		if (!$publication) {
			return $this->redirect('Publications::index');
		}

		if (($this->request->data) && $publication->save($this->request->data)) {
			return $this->redirect(array('Publications::view', 'args' => array($publication->archive->slug)));
		}

		$archives_documents = ArchivesDocuments::find('all', array(
			'with' => array(
				'Documents',
				'Documents.Formats'
			),
			'conditions' => array('archive_id' => $publication->id),
			'order' => array('Documents.slug' => 'ASC')
		));

		$publication_links = PublicationsLinks::find('all', array(
			'with' => array(
				'Links'
			),
			'conditions' => array('publication_id' => $publication->id),
			'order' => array('date_modified' =>  'DESC')
		));

		$order = array('title' => 'ASC');

		$exhibitions = Exhibitions::find('all', array(
			'with' => array('Archives', 'Components'),
			'conditions' => array(
				'Components.archive_id2' => $publication->id,
			),
			'order' => $order
		));

		$exhibition_ids = array();

		foreach ($exhibitions as $exhibition) {
			array_push($exhibition_ids, $exhibition->id);
		}

		//Find the exhibitions the work is NOT in
		$other_exhibition_conditions = ($exhibition_ids) ? array('Exhibitions.id' => array('!=' => $exhibition_ids)) : '';

		$other_exhibitions = Exhibitions::find('all', array(
			'with' => 'Archives',
			'order' => $order,
			'conditions' => $other_exhibition_conditions
		));
		
		return compact(
			'publication',
			'archives_documents',
			'publication_links',
			'exhibitions',
			'other_exhibitions'
		);

	}

	public function history() {
	
		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {
		
			//Get single record from the database where the slug matches the URL
			$publication = Publications::first(array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));
			
			if($publication) {

				$archives_histories = ArchivesHistories::find('all', array(
					'conditions' => array('ArchivesHistories.archive_id' => $publication->id),
					'order' => 'ArchivesHistories.start_date DESC',
					'with' => array('Users', 'PublicationsHistories'),
				));

				//FIXME We can't actually guarantee that the start_date can be used as a foreign key for the histories,
				//so for now let's grab the subclass history table, then iterate through it as well
				$publications_histories = PublicationsHistories::find('all', array(
					'conditions' => array('publication_id' => $publication->id),
					'order' => array('start_date' => 'DESC')
				));
		
				//Send the retrieved data to the view
				return compact('publication', 'archives_histories', 'publications_histories');
			}
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Publications::index'));
	}

	public function delete() {
        
		$publication = Publications::first(array(
			'with' => 'Archives',
			'conditions' => array('slug' => $this->request->params['slug']),
		));
        
        // For the following to work, the delete form must have an explicit 'method' => 'post'
        // since the default method is PUT
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Publications::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		$publication->delete();
		return $this->redirect('Publications::index');
	}
}

?>
