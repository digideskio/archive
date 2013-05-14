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

	public function index() {
	
		// Check authorization
		$check = (Auth::check('default')) ?: null;
	
		// If the user is not authorized, redirect to the login screen
		if (!$check) {
			return $this->redirect('Sessions::add');
		}
		
	   	// Look up the current user with his or her role
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
		
		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 40;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$total = Works::count();

		$limit = ($limit == 'all') ? $total : $limit;

		$works = Works::find('artworks', array(
			'with' => 'Archives',
			'limit' => $limit,
			'page' => $page
		));

		$inventory = (Environment::get('inventory') && ($auth->role->name == 'Admin'));

		return compact('works', 'total', 'page', 'limit', 'inventory', 'auth');
	}

	public function search() {
		
		// Check authorization
		$check = (Auth::check('default')) ?: null;

		// If the user is not authorized, redirect to the login screen
		if (!$check) {
			return $this->redirect('Sessions::add');
		}

		// Look up the current user with his or her role
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));

		$works = array();

		$query = '';
		$condition = '';

		$limit = 50;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$total = 0;

		$data = $this->request->data ?: $this->request->query;

		if (isset($data['query']) && $data['query']) {
			$condition = isset($data['condition']) ? $data['condition'] : '';

			$query = $data['query'];
			$esc_query = mysql_escape_string($query);

			if ($condition) {
				$conditions = array("$condition" => array('LIKE' => "%$esc_query%"));
			} else {
				$conditions = "((`title` LIKE '%$esc_query%') OR (`artist` LIKE '%$esc_query%') OR (`classification` LIKE '%$esc_query%') OR (`earliest_date` LIKE '%$esc_query%') OR (`materials` LIKE '%$esc_query%') OR (`remarks` LIKE '%$esc_query%') OR (`creation_number` LIKE '%$esc_query%') OR (`annotation` LIKE '%$esc_query%'))";
			}

			$works = Works::find('artworks', array(
				'with' => 'Archives',
				'conditions' => $conditions,
				'limit' => $limit,
				'page' => $page
			));

			$total = Works::count('artworks', array(
				'with' => 'Archives',
				'conditions' => $conditions,
			));

		}

		$inventory = (Environment::get('inventory') && ($auth->role->name == 'Admin'));

		return compact('works', 'condition', 'query', 'total', 'page', 'limit', 'inventory', 'auth');

	}

	public function artists() {
		
		// Check authorization
		$check = (Auth::check('default')) ?: null;

		// If the user is not authorized, redirect to the login screen
		if (!$check) {
			return $this->redirect('Sessions::add');
		}

		// Look up the current user with his or her role
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));

		$works_artists = Works::find('all', array(
			'fields' => array('artist', 'count(artist) as works'),
			'group' => 'artist',
			'conditions' => array('artist' => array('!=' => '')),
			'order' => array('artist' => 'ASC')
		));

		$artists = $works_artists->map(function($wa) {
			return array('name' => $wa->artist, 'works' => $wa->works);
		}, array('collect' => false));

		$inventory = (Environment::get('inventory') && ($auth->role->name == 'Admin'));

		return compact('artists', 'inventory', 'auth');

	}

	public function classifications() {

		// Check authorization
		$check = (Auth::check('default')) ?: null;

		// If the user is not authorized, redirect to the login screen
		if (!$check) {
			return $this->redirect('Sessions::add');
		}

		// Look up the current user with his or her role
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));

		$works_classifications = Archives::find('all', array(
			'fields' => array('classification', 'count(classification) as works'),
			'group' => 'classification',
			'conditions' => array('classification' => array('!=' => ''), 'controller' => 'works'),
			'order' => array('classification' => 'ASC'),
		));

		$classifications = $works_classifications->map(function($wc) {
			return array('name' => $wc->classification, 'works' => $wc->works);
		}, array('collect' => false));

		$inventory = (Environment::get('inventory') && ($auth->role->name == 'Admin'));

		return compact('classifications', 'inventory', 'auth');
	}

	public function locations() {

		// Check authorization
		$check = (Auth::check('default')) ?: null;

		// If the user is not authorized, redirect to the login screen
		if (!$check) {
			return $this->redirect('Sessions::add');
		}

		// Look up the current user with his or her role
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));

		//Check that inventory is enabled
		if (!Environment::get('inventory')) {
			return $this->redirect('Works::index'); 
		}

		//Define the access rules for this action
		$rules = array(
			array('rule' => 'isAdminUser', 'message' => 'You are not authorized', 'redirect' => "/works"),
		);
		
	    $access = Access::check('rule_based', $check, $this->request, array('rules' => $rules));
	    
        if(!empty($access)){
        	return $this->redirect($access['redirect']);
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

		return compact('locations', 'auth');
	}

	public function histories() {

		$check = (Auth::check('default')) ?: null;
	
		if (!$check) {
			return $this->redirect('Sessions::add');
		}
		
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));

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
		
		$inventory = (Environment::get('inventory') && ($auth->role->name == 'Admin'));
		return compact('auth', 'archives_histories', 'total', 'page', 'limit', 'inventory', 'auth');
	}

	public function view() {
	
		$check = (Auth::check('default')) ?: null;
	
		if (!$check) {
			return $this->redirect('Sessions::add');
		}
		
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
	
		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {
		
			//Get single record from the database where the slug matches the URL
			$work = Works::find('first', array(
				'with' => 'Archives',
				'conditions' => array('slug' => $this->request->params['slug']),
			));

			if($work) {
	
				$order = array('title' => 'ASC');

				$archives_documents = ArchivesDocuments::find('all', array(
					'with' => array(
						'Documents',
						'Formats'
					),
					'conditions' => array('archive_id' => $work->id),
					'order' => array('slug' => 'ASC')
				));
		
				$albums = Albums::find('all', array(
					'with' => array('Archives', 'Components'),
					'conditions' => array(
						'archive_id2' => $work->id,
					),
					'order' => $order
				));
		
				$exhibitions = Exhibitions::find('all', array(
					'with' => array('Archives', 'Components'),
					'conditions' => array(
						'archive_id2' => $work->id,
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
			
				$inventory = (Environment::get('inventory') && ($auth->role->name == 'Admin'));

				//Send the retrieved data to the view
				return compact('work', 'archives_documents', 'work_links', 'albums', 'exhibitions', 'inventory', 'auth');
			}
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Works::index'));
	}

	public function add() {
	
		$check = (Auth::check('default')) ?: null;
	
		if (!$check) {
			return $this->redirect('Sessions::add');
		}
		
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
		
		// If the user is not an Admin or Editor, redirect to the index
		if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
			return $this->redirect('Works::index');
		}
		
		$work = Works::create();

		$works_artists = Works::find('all', array(
			'fields' => array('artist', 'count(artist) as works'),
			'group' => 'artist',
			'conditions' => array('artist' => array('!=' => '')),
			'order' => array('works' => 'DESC')
		));

		$artists = $works_artists->map(function($wa) {
			return $wa->artist;
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

		if (($this->request->data) && $work->save($this->request->data)) {
			//The slug has been saved with the Archive object, so let's look it up
			$archive = Archives::find('first', array(
				'conditions' => array('id' => $work->id)
			));
			return $this->redirect(array('Works::view', 'args' => array($archive->slug)));
		}

		$inventory = (Environment::get('inventory') && ($auth->role->name == 'Admin'));

		$documents = array();

		if (isset($this->request->data['documents']) && $this->request->data['documents']) {
			$document_ids = $this->request->data['documents'];

			$documents = Documents::find('all', array(
				'with' => 'Formats',
				'conditions' => array('Documents.id' => $document_ids),
			));

		}

		return compact('work', 'artists', 'classifications', 'locations', 'users', 'inventory', 'documents', 'auth');
	}

	public function edit() {
	
		$check = (Auth::check('default')) ?: null;
	
		if (!$check) {
			return $this->redirect('Sessions::add');
		}
		
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
		
		// If the user is not an Admin or Editor, redirect to the index
		if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
			return $this->redirect('Works::index');
		}
		
		if(isset($this->request->params['slug'])) {
		
			$work = Works::first(array(
				'with' => 'Archives',
				'conditions' => array('slug' => $this->request->params['slug']),
			));
		
			if($work) {

				$works_artists = Works::find('all', array(
					'fields' => array('artist', 'count(artist) as works'),
					'group' => 'artist',
					'conditions' => array('artist' => array('!=' => '')),
					'order' => array('works' => 'DESC')
				));

				$artists = $works_artists->map(function($wa) {
					return $wa->artist;
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
						'archive_id2' => $work->id,
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
						'archive_id2' => $work->id,
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
						'Formats'
					),
					'conditions' => array('archive_id' => $work->id),
					'order' => array('slug' => 'ASC')
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
		
				$inventory = (Environment::get('inventory') && ($auth->role->name == 'Admin'));

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
					'locations',
					'users',
					'inventory',
					'auth'
				);
			}	
		}																																		
		
		$this->redirect(array('Works::index'));
		
	}

	public function attachments() {

		$check = (Auth::check('default')) ?: null;
	
		if (!$check) {
			return $this->redirect('Sessions::add');
		}
		
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));

		// If the user is not an Admin or Editor, redirect to the index
		if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
			return $this->redirect('Works::index');
		}

		if(isset($this->request->params['slug'])) {
		
			$work = Works::first(array(
				'with' => 'Archives',
				'conditions' => array('slug' => $this->request->params['slug']),
			));
		
			if($work) {

				$order = array('title' => 'ASC');

				$albums = Albums::find('all', array(
					'with' => array('Archives', 'Components'),
					'conditions' => array(
						'archive_id2' => $work->id,
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
						'archive_id2' => $work->id,
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
						'Formats'
					),
					'conditions' => array('archive_id' => $work->id),
					'order' => array('slug' => 'ASC')
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
		
				$inventory = (Environment::get('inventory') && ($auth->role->name == 'Admin'));

				return compact(
					'work', 
					'archives_documents', 
					'albums', 
					'other_albums', 
					'exhibitions', 
					'other_exhibitions',
					'work_links',
					'auth'
				);
			}	
		}																																		
		
		$this->redirect(array('Works::index'));

	}

	public function history() {
	
		$check = (Auth::check('default')) ?: null;
	
		if (!$check) {
			return $this->redirect('Sessions::add');
		}
		
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
	
		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {
		
			//Get single record from the database where the slug matches the URL
			$work = Works::first(array(
				'with' => 'Archives',
				'conditions' => array('slug' => $this->request->params['slug']),
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
				return compact('auth', 'work', 'archives_histories', 'works_histories', 'auth');
			}
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Works::index'));
	}

	public function delete() {
	
		$check = (Auth::check('default')) ?: null;
	
		if (!$check) {
			return $this->redirect('Sessions::add');
		}
		
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
		
		$work = Works::find('first', array(
			'with' => 'Archives',
			'conditions' => array('slug' => $this->request->params['slug']),
		));
		
		// If the user is not an Admin or Editor, redirect to the record view
		if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
			return $this->redirect(array(
				'Works::view', 'args' => array($this->request->params['slug']))
			);
		}
		
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
