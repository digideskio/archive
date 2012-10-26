<?php

namespace app\controllers;

use app\models\Architectures;

use app\models\Users;
use app\models\Roles;
use app\models\Documents;
use app\models\ArchitecturesDocuments;

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
			'with' => 'ArchitecturesDocuments',
			'order' => array('earliest_date' => 'DESC')
		));
		return compact('architectures', 'auth');
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

		$conditions = array();

		$data = $this->request->data;

		if (isset($data['conditions'])) {
			$condition = $data['conditions'];

			if ($condition == 'year') {
				$condition = 'earliest_date';
			}

			$query = $data['query'];
			$conditions = array("$condition" => array('LIKE' => "%$query%"));

			$architectures = Architectures::find('all', array(
				'with' => 'ArchitecturesDocuments',
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
			return $this->redirect(array('Architectures::view', 'args' => array($architecture->slug)));
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
			'conditions' => array('slug' => $this->request->params['slug']),
		));
		
		$architecture_documents = ArchitecturesDocuments::find('all', array(
			'with' => array(
				'Documents',
				'Formats'
			),
			'conditions' => array('architecture_id' => $architecture->id),
		));

		if (!$architecture) {
			return $this->redirect('Architectures::index');
		}
		if (($this->request->data) && $architecture->save($this->request->data)) {
			return $this->redirect(array('Architectures::view', 'args' => array($architecture->slug)));
		}
		
		return compact('architecture', 'architecture_documents');
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
