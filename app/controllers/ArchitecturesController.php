<?php

namespace app\controllers;

use app\models\Architectures;
use app\models\ArchitecturesHistories;

use app\models\Users;
use app\models\Roles;
use app\models\Documents;
use app\models\ArchitecturesDocuments;

use app\models\Archives;
use app\models\ArchivesHistories;

use lithium\action\DispatchException;
use lithium\security\Auth;

class ArchitecturesController extends \lithium\action\Controller {

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
		
		$architectures = Architectures::find('all', array(
			'with' => 'Archives',
			'order' => array('earliest_date' => 'DESC')
		));
		return compact('architectures', 'auth');
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
		$total = ArchitecturesHistories::count();
		$archives_histories = ArchivesHistories::find('all', array(
			'with' => array('Users', 'Archives'),
			'conditions' => array('ArchivesHistories.controller' => 'architectures'),
			'limit' => $limit,
			'order' => $order,
			'page' => $page
		));

		return compact('auth', 'archives_histories', 'total', 'page', 'limit');
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

		$architectures = array();

		$order = array('earliest_date' => 'DESC');

		$data = $this->request->data;

		$query = '';
		$condition = '';

		if (isset($data['conditions'])) {
			$condition = $data['conditions'];

			if ($condition == 'year') {
				$condition = 'earliest_date';
			}

			$query = $data['query'];
			$conditions = array("$condition" => array('LIKE' => "%$query%"));

			$architectures = Architectures::find('all', array(
				'with' => 'Archives',
				'order' => $order,
				'conditions' => $conditions
			));

			if ($condition == 'earliest_date') {
				$condition = 'year';
			}
		}
		return compact('architectures', 'condition', 'query', 'auth');
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
			$architecture = Architectures::first(array(
				'with' => 'Archives',
				'conditions' => array('slug' => $this->request->params['slug']),
			));
			
			if(!$architecture) {
				$this->redirect(array('Architectures::index'));
			} else {
		
				$architecture_documents = ArchitecturesDocuments::find('all', array(
					'with' => array(
						'Documents',
						'Formats'
					),
					'conditions' => array('architecture_id' => $architecture->id),
					'order' => array('slug' => 'ASC')
				));
			
				//Send the retrieved data to the view
				return compact('architecture', 'architecture_documents', 'auth');
			
			}
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Architectures::index'));
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
        	return $this->redirect('Architectures::index');
        }
        
		$architecture = Architectures::create();

		if (($this->request->data) && $architecture->save($this->request->data)) {
			//The slug has been saved with the Archive object, so let's look it up
			$archive = Archives::find('first', array(
				'conditions' => array('id' => $architecture->id)
			));
			return $this->redirect(array('Architectures::view', 'args' => array($archive->slug)));
		}
		return compact('architecture');
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
		
		$architecture = Architectures::first(array(
			'with' => 'Archives',
			'conditions' => array('slug' => $this->request->params['slug']),
		));
		
		$architecture_documents = ArchitecturesDocuments::find('all', array(
			'with' => array(
				'Documents',
				'Formats'
			),
			'conditions' => array('architecture_id' => $architecture->id),
			'order' => array('slug' => 'ASC')
		));

		if (!$architecture) {
			return $this->redirect('Architectures::index');
		}
		if (($this->request->data) && $architecture->save($this->request->data)) {
			return $this->redirect(array('Architectures::view', 'args' => array($architecture->archive->slug)));
		}
		
		return compact('architecture', 'architecture_documents');
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
			$architecture = Architectures::first(array(
				'with' => 'Archives',
				'conditions' => array('slug' => $this->request->params['slug']),
			));
			
			if($architecture) {

				$archives_histories = ArchivesHistories::find('all', array(
					'conditions' => array('ArchivesHistories.archive_id' => $architecture->id),
					'order' => 'ArchivesHistories.start_date DESC',
					'with' => array('Users', 'ArchitecturesHistories'),
				));

				//FIXME We can't actually guarantee that the start_date can be used as a foreign key for the histories,
				//so for now let's grab the subclass history table, then iterate through it as well
				$architectures_histories = ArchitecturesHistories::find('all', array(
					'conditions' => array('architecture_id' => $architecture->id),
					'order' => array('start_date' => 'DESC')
				));
		
				//Send the retrieved data to the view
				return compact('auth', 'architecture', 'archives_histories', 'architectures_histories');
			}
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Architectures::index'));

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
        
		$architecture = Architectures::first(array(
			'with' => 'Archives',
			'conditions' => array('slug' => $this->request->params['slug']),
		));
        
        // If the user is not an Admin or Editor, redirect to the record view
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
        	return $this->redirect(array(
        		'Architectures::view', 'args' => array($this->request->params['slug']))
        	);
        }
        
        // For the following to work, the delete form must have an explicit 'method' => 'post'
        // since the default method is PUT
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Architectures::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		$architecture->delete();
		return $this->redirect('Architectures::index');
	}
}

?>
