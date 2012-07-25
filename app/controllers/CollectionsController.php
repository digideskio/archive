<?php

namespace app\controllers;

use app\models\Collections;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class CollectionsController extends \lithium\action\Controller {

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
		
		$collections = Collections::all();
		return compact('collections', 'auth');
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
			$collection = Collections::first(array(
				'conditions' => array('slug' => $this->request->params['slug']),
			));
			
			//Send the retrieved data to the view
			return compact('collection', 'auth');
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Collections::index'));
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
        	return $this->redirect('Collections::index');
        }
        
		$collection = Collections::create();

		if (($this->request->data) && $collection->save($this->request->data)) {
			return $this->redirect(array('Collections::view', 'args' => array($collection->slug)));
		}
		return compact('collection');
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
		
		$collection = Collections::first(array(
			'conditions' => array('slug' => $this->request->params['slug'])
		));

		if (!$collection) {
			return $this->redirect('Collections::index');
		}
		if (($this->request->data) && $collection->save($this->request->data)) {
			return $this->redirect(array('Collections::view', 'args' => array($collection->slug)));
		}
		return compact('collection');
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
        
		$collection = Collections::first(array(
			'conditions' => array('slug' => $this->request->params['slug']),
		));
        
        // If the user is not an Admin or Editor, redirect to the record view
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
        	return $this->redirect(array(
        		'Collections::view', 'args' => array($this->request->params['slug']))
        	);
        }
        
        // For the following to work, the delete form must have an explicit 'method' => 'post'
        // since the default method is PUT
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Collections::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		$collection->delete();
		return $this->redirect('Collections::index');
	}
}

?>