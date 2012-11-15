<?php

namespace app\controllers;

use app\models\Works;

use app\models\Users;
use app\models\Roles;
use app\models\Documents;
use app\models\WorksDocuments;
use app\models\Collections;
use app\models\CollectionsWorks;
use app\models\Exhibitions;
use app\models\ExhibitionsWorks;
use app\models\WorksHistories;
use app\models\Links;
use app\models\WorksLinks;

use lithium\action\DispatchException;
use lithium\security\Auth;

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
		
		$limit = 50;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$order = array('earliest_date' => 'DESC');
		$total = Works::count();
		$works = Works::find('all', array(
			'with' => 'WorksDocuments',
			'limit' => $limit,
			'order' => $order,
			'page' => $page
		));
		
		return compact('works', 'total', 'page', 'limit', 'auth');
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

		$order = array('earliest_date' => 'DESC');

		$query = '';
		$condition = '';

		$data = $this->request->data;

		if (isset($data['conditions'])) {
			$condition = $data['conditions'];

			if ($condition == 'year') {
				$condition = 'earliest_date';
			}

			$query = $data['query'];
			$conditions = array("$condition" => array('LIKE' => "%$query%"));

			$works = Works::find('all', array(
				'with' => 'WorksDocuments',
				'order' => $order,
				'conditions' => $conditions
			));

			if ($condition == 'earliest_date') {
				$condition = 'year';
			}
		}

		return compact('works', 'condition', 'query', 'auth');

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
		$works_histories = WorksHistories::find('all', array(
			'with' => 'Users',
			'limit' => $limit,
			'order' => $order,
			'page' => $page
		));
		
		return compact('works_histories', 'total', 'page', 'limit', 'auth');
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
			$work = Works::first(array(
				'conditions' => array('slug' => $this->request->params['slug']),
			));
			
			if($work) {
	
				$order = array('title' => 'ASC');

				$work_documents = WorksDocuments::find('all', array(
					'with' => array(
						'Documents',
						'Formats'
					),
					'conditions' => array('work_id' => $work->id),
					'order' => array('slug' => 'ASC')
				));
		
				$collections = Collections::find('all', array(
					'with' => 'CollectionsWorks',
					'conditions' => array(
						'work_id' => $work->id,
					),
					'order' => $order
				));
		
				$exhibitions = Exhibitions::find('all', array(
					'with' => 'ExhibitionsWorks',
					'conditions' => array(
						'work_id' => $work->id,
					),
					'order' => $order
				));
			
				//Send the retrieved data to the view
				return compact('work', 'work_documents', 'collections', 'exhibitions', 'auth');
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

		if (($this->request->data) && $work->save($this->request->data)) {

			//If we are adding a link to this record
			if (isset($this->request->data['url'])) {
				$url = $this->request->data['url'];

				$link = Links::first(array(
					'conditions' => array('url' => $url)
				));

				if ($link) {
					$sucesss = true;
				}

				if (!$link) {
					$link = Links::create();
					$success = $link->save($this->request->data);
				}

				if ($success) {

					$work_id = $work->id;
					$link_id = $link->id;

					$works_links = WorksLinks::create();
					$data = compact('work_id', 'link_id');
					$works_links->save($data);
				}
			}

			return $this->redirect(array('Works::view', 'args' => array($work->slug)));
		}
		return compact('work', 'auth');
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
				'conditions' => array('slug' => $this->request->params['slug']),
			));
		
			if($work) {
		
				$order = array('title' => 'ASC');

				$collections = Collections::find('all', array(
					'with' => 'CollectionsWorks',
					'conditions' => array(
						'work_id' => $work->id,
					),
					'order' => $order
				));
		
		
				$other_collections = Collections::find('all', array(
					'with' => 'CollectionsWorks',
					'order' => $order
				));
		
				$exhibitions = Exhibitions::find('all', array(
					'with' => 'ExhibitionsWorks',
					'conditions' => array(
						'work_id' => $work->id,
					),
					'order' => $order
				));
		
				$other_exhibitions = Exhibitions::find('all', array(
					'with' => array('ExhibitionsWorks'),
					'order' => array('earliest_date' => 'DESC'),
					//'conditions' => array('work_id' => array('!=', array($work->id))) //FIXME why does this not work?
				));
		
				$work_documents = WorksDocuments::find('all', array(
					'with' => array(
						'Documents',
						'Formats'
					),
					'conditions' => array('work_id' => $work->id),
					'order' => array('slug' => 'ASC')
				));

				$work_links = WorksLinks::find('all', array(
					'with' => array(
						'Links'
					),
					'conditions' => array('work_id' => $work->id),
					'order' => array('date_modified' =>  'ASC')
				));

				if (($this->request->data) && $work->save($this->request->data)) {
					return $this->redirect(array('Works::view', 'args' => array($work->slug)));
				}
		
				return compact(
					'work', 
					'work_documents', 
					'collections', 
					'other_collections', 
					'exhibitions', 
					'other_exhibitions',
					'work_links'
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
				'conditions' => array('slug' => $this->request->params['slug']),
			));
			
			if($work) {

				$works_histories = WorksHistories::find('all', array(
					'conditions' => array('work_id' => $work->id),
					'order' => 'start_date DESC',
					'with' => 'Users'
				));
		
				//Send the retrieved data to the view
				return compact('work', 'works_histories', 'auth');
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
		
		$work = Works::first(array(
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
