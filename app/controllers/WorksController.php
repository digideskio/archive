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
		
				$work_documents = WorksDocuments::find('all', array(
					'with' => array(
						'Documents',
						'Formats'
					),
					'conditions' => array('work_id' => $work->id),
				));
		
				$collections = Collections::find('all', array(
					'with' => 'CollectionsWorks',
					'conditions' => array(
						'work_id' => $work->id,
					),
				));
		
				$exhibitions = Exhibitions::find('all', array(
					'with' => 'ExhibitionsWorks',
					'conditions' => array(
						'work_id' => $work->id,
					),
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
			return $this->redirect(array('Works::view', 'args' => array($work->slug)));
		}
		return compact('work');
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
		
				$collections = Collections::find('all', array(
					'with' => 'CollectionsWorks',
					'conditions' => array(
						'work_id' => $work->id,
					),
				));
		
		
				$other_collections = Collections::find('all', array(
					'with' => 'CollectionsWorks',
				));
		
				$exhibitions = Exhibitions::find('all', array(
					'with' => 'ExhibitionsWorks',
					'conditions' => array(
						'work_id' => $work->id,
					),
				));
		
				$other_exhibitions = Exhibitions::find('all', array(
					'with' => array('ExhibitionsWorks'),
					'order' => 'earliest_date DESC',
					//'conditions' => array('work_id' => array('!=', array($work->id))) //FIXME why does this not work?
				));
		
				$work_documents = WorksDocuments::find('all', array(
					'with' => array(
						'Documents',
						'Formats'
					),
					'conditions' => array('work_id' => $work->id)
				));
			
				if (($this->request->data) && $work->save($this->request->data)) {
					return $this->redirect(array('Works::view', 'args' => array($work->slug)));
				}
		
				return compact('work', 'work_documents', 'collections', 'other_collections', 'exhibitions', 'other_exhibitions');
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
					'order' => 'start_date DESC'
				));
		
				$work_documents = WorksDocuments::find('all', array(
					'with' => array(
						'Documents',
						'Formats'
					),
					'conditions' => array('work_id' => $work->id),
				));
		
				$collections = Collections::find('all', array(
					'with' => 'CollectionsWorks',
					'conditions' => array(
						'work_id' => $work->id,
					),
				));
		
				$exhibitions = Exhibitions::find('all', array(
					'with' => 'ExhibitionsWorks',
					'conditions' => array(
						'work_id' => $work->id,
					),
				));
			
				//Send the retrieved data to the view
				return compact('work', 'works_histories', 'work_documents', 'collections', 'exhibitions', 'auth');
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
