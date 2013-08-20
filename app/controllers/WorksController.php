<?php

namespace app\controllers;

use app\models\Works;
use app\models\WorksHistories;
use app\models\Archives;
use app\models\ArchivesHistories;

use app\models\Users;
use app\models\Roles;
use app\models\Documents;
use app\models\ArchivesDocuments;
use app\models\Albums;
use app\models\Exhibitions;
use app\models\Components;
use app\models\Links;
use app\models\WorksLinks;

use lithium\action\DispatchException;
use lithium\security\Auth;
use lithium\core\Environment;

use li3_access\security\Access;

class WorksController extends \lithium\action\Controller {

	public $rules = array(
		'index' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'search' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'artists' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'classifications' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'locations' => array(
			array('rule' => 'allowAdminUser', 'redirect' => "Works::index"),
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

		$filter = '';

		if (Environment::get('artworks')) {
			$artworks = Environment::get('artworks');
			$filter = isset($artworks['filter']) ? $artworks['filter'] : '';
		}
		
		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 40;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;

		$total = Works::find('count', array(
			'conditions' => $filter,
		));

		//Interpret any non-integer limit to mean 'All' results
		$limit = !(intval($limit)) ? $total : $limit;

		$works = Works::find('artworks', array(
			'with' => 'Archives',
			'limit' => $limit,
			'conditions' => $filter,
			'page' => $page
		));

		return compact('works', 'total', 'page', 'limit');
	}

	public function search() {

		$works = array();

		$query = '';
		$condition = '';

		$limit = 40;

		if (Environment::get('artworks')) {
			$artworks = Environment::get('artworks');
			$filter = isset($artworks['filter']) ? $artworks['filter'] : '';
			
			if (isset($artworks['search'])) {
				$search = $artworks['search'];
				$limit = isset($search['limit']) ? $search['limit'] : $limit;
			}
		}
		
		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : $limit;

		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$total = 0;

		$data = $this->request->data ?: $this->request->query;

		if (isset($data['query']) && $data['query']) {
			$condition = isset($data['condition']) ? $data['condition'] : '';

			$query = $data['query'];

			if ($condition && $condition != 'artist') {
				$conditions = array("$condition" => array('LIKE' => "%$query%"));
			} else {

				$artwork_ids = array();

				if ($condition == 'artist') {
					$fields = array('artist', 'artist_native_name');
				} else {
					$fields = array('title', 'artist', 'artist_native_name', 'classification', 'earliest_date', 'materials', 'remarks', 'creation_number', 'annotation');
				}

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

				$conditions = $artwork_ids ? array('Works.id' => $artwork_ids) : array('artist' => $query);
			}

			$filter = '';

			if (Environment::get('artworks')) {
				$artworks = Environment::get('artworks');
				$filter = isset($artworks['filter']) ? $artworks['filter'] : '';
			}

			$conditions = $filter ? array_merge($filter, $conditions) : $conditions;

			$total = Works::count('artworks', array(
				'with' => 'Archives',
				'conditions' => $conditions,
			));

			//Interpret any non-integer limit to mean 'All' results
			$limit = !(intval($limit)) ? $total : $limit;

			$works = Works::find('artworks', array(
				'with' => 'Archives',
				'conditions' => $conditions,
				'limit' => $limit,
				'page' => $page
			));

		}

		return compact('works', 'condition', 'query', 'total', 'page', 'limit');

	}

	public function artists() {

		$works_artists = Works::find('all', array(
			'fields' => array('artist', 'artist_native_name', 'count(artist) as works'),
			'group' => array('artist', 'artist_native_name'),
			'order' => array('artist' => 'ASC', 'artist_native_name' => 'ASC')
		));

		$artists = $works_artists->map(function($wa) {
			if ($wa->artist || $wa->artist_native_name) {
				return array('name' => $wa->artist, 'native_name' => $wa->artist_native_name, 'works' => $wa->works);
			}
		}, array('collect' => false));

		$artists = array_filter($artists);

		return compact('artists');

	}

	public function classifications() {

		$works_classifications = Archives::find('all', array(
			'fields' => array('classification', 'count(classification) as works'),
			'group' => 'classification',
			'conditions' => array('classification' => array('!=' => ''), 'controller' => 'works'),
			'order' => array('classification' => 'ASC'),
		));

		$classifications = $works_classifications->map(function($wc) {
			$archives = Archives::find('all', array(
				'fields' => 'id',
				'conditions' => array('classification' => $wc->classification, 'controller' => 'works'),
				'order' => array('date_created' => 'DESC'),
			));

			$archives_ids = $archives->map(function($ai) {
				return $ai->id;
			}, array('collect' => false));

			$document = Documents::find('first', array(
				'with' => array('Formats', 'ArchivesDocuments'),
				'conditions' => array('ArchivesDocuments.archive_id' => $archives_ids),
				'order' => array('date_modified' => 'DESC')
			));

			$document_slug = $document ? $document->slug : '';

			return array('name' => $wc->classification, 'works' => $wc->works, 'document' => $document_slug);
		}, array('collect' => false));

		return compact('classifications');
	}

	public function locations() {

		//Check that inventory is enabled
		if (!Environment::get('inventory')) {
			return $this->redirect('Works::index'); 
		}
    
		$works_locations = Works::find('all', array(
			'fields' => array('location', 'count(location) as works'),
			'group' => 'location',
			'conditions' => array('location' => array('!=' => '')),
			'order' => array('works' => 'DESC'),
		));

		$locations = $works_locations->map(function($wc) {
			return array('name' => $wc->location, 'works' => $wc->works);
		}, array('collect' => false));

		return compact('locations');
	}

	public function histories() {

		$limit = 50;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$order = array('start_date' => 'DESC');
		$total = WorksHistories::count();
		$archives_histories = ArchivesHistories::find('all', array(
			'with' => array('Users', 'Archives'),
			'conditions' => array('ArchivesHistories.controller' => 'works'),
			'limit' => $limit,
			'order' => $order,
			'page' => $page
		));
		
		return compact('archives_histories', 'total', 'page', 'limit');
	}

	public function view() {
	
		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {
		
			//Get single record from the database where the slug matches the URL
			$work = Works::find('first', array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));

			if($work) {
	
				$order = array('title' => 'ASC');

				$archives_documents = ArchivesDocuments::find('all', array(
					'with' => array(
						'Documents',
						'Documents.Formats'
					),
					'conditions' => array('ArchivesDocuments.archive_id' => $work->id),
					'order' => array('Documents.slug' => 'ASC')
				));
		
				$albums = Albums::find('all', array(
					'with' => array('Archives', 'Components'),
					'conditions' => array(
						'Components.archive_id2' => $work->id,
					),
					'order' => $order
				));
		
				$exhibitions = Exhibitions::find('all', array(
					'with' => array('Archives', 'Components'),
					'conditions' => array(
						'Components.archive_id2' => $work->id,
					),
					'order' => $order
				));

				$work_links = WorksLinks::find('all', array(
					'with' => array(
						'Links'
					),
					'conditions' => array('work_id' => $work->id),
					'order' => array('date_modified' =>  'DESC')
				));

				return compact('work', 'archives_documents', 'work_links', 'albums', 'exhibitions');
			}
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Works::index'));
	}

	public function add() {
		
		$work = Works::create();

		if (($this->request->data) && $work->save($this->request->data)) {
			//The slug has been saved with the Archive object, so let's look it up
			$archive = Archives::find('first', array(
				'conditions' => array('id' => $work->id)
			));
			return $this->redirect(array('Works::view', 'args' => array($archive->slug)));
		}

		$works_artists = Works::find('all', array(
			'fields' => array('artist', 'artist_native_name', 'count(artist) as works'),
			'group' => array('artist', 'artist_native_name'),
			'order' => array('works' => 'DESC')
		));

		$artists = $works_artists->map(function($wa) {
			if ($wa->artist || $wa->artist_native_name) {
				return array('name' => $wa->artist, 'native_name' => $wa->artist_native_name, 'works' => $wa->works);
			}
		}, array('collect' => false));

		$artists = array_filter($artists);

		$works_materials = Works::find('all', array(
			'fields' => array('materials', 'count(materials) as works'),
			'group' => 'materials',
			'conditions' => array('materials' => array('!=' => '')),
			'order' => array('works' => 'DESC', 'materials' => 'ASC')
		));

		$materials = $works_materials->map(function($wm) {
			return $wm->materials;
		}, array('collect' => false));

		$works_locations = Works::find('all', array(
			'fields' => array('location'),
			'group' => 'location',
			'conditions' => array('location' => array('!=' => '')),
			'order' => array('location' => 'ASC')
		));

		$locations = $works_locations->map(function($wl) {
			return $wl->location;
		}, array('collect' => false));

		$users = Users::find('all', array(
			'order' => array('name' => 'ASC'),
		));

		$classifications = Works::classifications();

		$documents = array();

		if (isset($this->request->data['documents']) && $this->request->data['documents']) {
			$document_ids = $this->request->data['documents'];

			$documents = Documents::find('all', array(
				'with' => 'Formats',
				'conditions' => array('Documents.id' => $document_ids),
			));

		}

		return compact('work', 'artists', 'classifications', 'materials', 'locations', 'users', 'documents');
	}

	public function edit() {
		
		if(isset($this->request->params['slug'])) {
		
			$work = Works::first(array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));
		
			if($work) {

				$works_artists = Works::find('all', array(
					'fields' => array('artist', 'artist_native_name', 'count(artist) as works'),
					'group' => array('artist', 'artist_native_name'),
					'order' => array('works' => 'DESC')
				));

				$artists = $works_artists->map(function($wa) {
					if ($wa->artist || $wa->artist_native_name) {
						return array('name' => $wa->artist, 'native_name' => $wa->artist_native_name, 'works' => $wa->works);
					}
				}, array('collect' => false));

				$artists = array_filter($artists);

				$works_materials = Works::find('all', array(
					'fields' => array('materials', 'count(materials) as works'),
					'group' => 'materials',
					'conditions' => array('materials' => array('!=' => '')),
					'order' => array('works' => 'DESC', 'materials' => 'ASC')
				));

				$materials = $works_materials->map(function($wm) {
					return $wm->materials;
				}, array('collect' => false));

				$classifications = Works::classifications();
		
				$works_locations = Works::find('all', array(
					'fields' => array('location'),
					'group' => 'location',
					'conditions' => array('location' => array('!=' => '')),
					'order' => array('location' => 'ASC')
				));

				$locations = $works_locations->map(function($wl) {
					return $wl->location;
				}, array('collect' => false));

				$users = Users::find('all', array(
					'order' => array('name' => 'ASC'),
				));

				$order = array('title' => 'ASC');

				$albums = Albums::find('all', array(
					'with' => array('Archives', 'Components'),
					'conditions' => array(
						'Components.archive_id2' => $work->id,
					),
					'order' => $order
				));

				$album_ids = array();

				foreach ($albums as $album) {
					array_push($album_ids, $album->id);
				}

				//Find the albums the work is NOT in
				$other_album_conditions = ($album_ids) ? array('Albums.id' => array('!=' => $album_ids)) : '';

				$other_albums = Albums::find('all', array(
					'with' => 'Archives',
					'order' => $order,
					'conditions' => $other_album_conditions
				));
	
				$exhibitions = Exhibitions::find('all', array(
					'with' => array('Archives', 'Components'),
					'conditions' => array(
						'Components.archive_id2' => $work->id,
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
					'order' => array('earliest_date' => 'DESC'),
					'conditions' => $other_exhibition_conditions
				));
		
				$archives_documents = ArchivesDocuments::find('all', array(
					'with' => array(
						'Documents',
						'Documents.Formats'
					),
					'conditions' => array('archive_id' => $work->id),
					'order' => array('Documents.slug' => 'ASC')
				));

				$work_links = WorksLinks::find('all', array(
					'with' => array(
						'Links'
					),
					'conditions' => array('work_id' => $work->id),
					'order' => array('date_modified' =>  'DESC')
				));

				if (($this->request->data) && $work->save($this->request->data)) {
					return $this->redirect(array('Works::view', 'args' => array($this->request->params['slug'])));
				}

				return compact(
					'work', 
					'archives_documents', 
					'albums', 
					'other_albums', 
					'exhibitions', 
					'other_exhibitions',
					'work_links',
					'artists',
					'classifications',
					'materials',
					'locations',
					'users'
				);
			}	
		}																																		
		
		$this->redirect(array('Works::index'));
		
	}

	public function attachments() {

		if(isset($this->request->params['slug'])) {
		
			$work = Works::first(array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));
		
			if($work) {

				$order = array('title' => 'ASC');

				$albums = Albums::find('all', array(
					'with' => array('Archives', 'Components'),
					'conditions' => array(
						'Components.archive_id2' => $work->id,
					),
					'order' => $order
				));

				$album_ids = array();

				foreach ($albums as $album) {
					array_push($album_ids, $album->id);
				}

				//Find the albums the work is NOT in
				$other_album_conditions = ($album_ids) ? array('Albums.id' => array('!=' => $album_ids)) : '';

				$other_albums = Albums::find('all', array(
					'with' => 'Archives',
					'order' => $order,
					'conditions' => $other_album_conditions
				));
	
				$exhibitions = Exhibitions::find('all', array(
					'with' => array('Archives', 'Components'),
					'conditions' => array(
						'Components.archive_id2' => $work->id,
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
		
				$archives_documents = ArchivesDocuments::find('all', array(
					'with' => array(
						'Documents',
						'Documents.Formats'
					),
					'conditions' => array('archive_id' => $work->id),
					'order' => array('Documents.slug' => 'ASC')
				));

				$work_links = WorksLinks::find('all', array(
					'with' => array(
						'Links'
					),
					'conditions' => array('work_id' => $work->id),
					'order' => array('date_modified' =>  'DESC')
				));

				if (($this->request->data) && $work->save($this->request->data)) {
					return $this->redirect(array('Works::view', 'args' => array($this->request->params['slug'])));
				}

				return compact(
					'work', 
					'archives_documents', 
					'albums', 
					'other_albums', 
					'exhibitions', 
					'other_exhibitions',
					'work_links'
				);
			}	
		}																																		
		
		$this->redirect(array('Works::index'));

	}

	public function history() {
	
		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {
		
			//Get single record from the database where the slug matches the URL
			$work = Works::first(array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));
			
			if($work) {

				$archives_histories = ArchivesHistories::find('all', array(
					'conditions' => array('ArchivesHistories.archive_id' => $work->id),
					'order' => 'ArchivesHistories.start_date DESC',
					'with' => array('Users', 'WorksHistories'),
				));

				//FIXME We can't actually guarantee that the start_date can be used as a foreign key for the histories,
				//so for now let's grab the subclass history table, then iterate through it as well
				$works_histories = WorksHistories::find('all', array(
					'conditions' => array('work_id' => $work->id),
					'order' => array('start_date' => 'DESC')
				));
		
				//Send the retrieved data to the view
				return compact('work', 'archives_histories', 'works_histories');
			}
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Works::index'));
	}

	public function delete() {
		
		$work = Works::find('first', array(
			'with' => 'Archives',
			'conditions' => array('Archives.slug' => $this->request->params['slug']),
		));
		
		// For the following to work, the delete form must have an explicit 'method' => 'post'
		// since the default method is PUT
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Works::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		$work->delete();
		return $this->redirect('Works::index');
	}
}

?>
