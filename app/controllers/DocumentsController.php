<?php

namespace app\controllers;

use app\models\Documents;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class DocumentsController extends \lithium\action\Controller {

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
		
		$documents = Documents::all();
		return compact('documents', 'auth');
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
			$document = Documents::first(array(
				'conditions' => array('slug' => $this->request->params['slug']),
			));
			
			//Send the retrieved data to the view
			return compact('document', 'auth');
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Documents::index'));
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
        	return $this->redirect('Documents::index');
        }
        
		$document = Documents::create();

		if (($this->request->data) && $document->save($this->request->data)) {
			return $this->redirect(array('Documents::view', 'args' => array($document->slug)));
		}
		return compact('document');
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
		
		$document = Documents::first(array(
			'conditions' => array('slug' => $this->request->params['slug'])
		));

		if (!$document) {
			return $this->redirect('Documents::index');
		}
		if (($this->request->data) && $document->save($this->request->data)) {
			return $this->redirect(array('Documents::view', 'args' => array($document->slug)));
		}
		
		// If the database times are zero, just show an empty string in the form
		if($document->earliest_date == '0000-00-00 00:00:00') {
			$document->earliest_date = '';
		}
		
		if($document->latest_date == '0000-00-00 00:00:00') {
			$document->latest_date = '';
		}
		
		return compact('document');
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
        
		$document = Documents::first(array(
			'conditions' => array('slug' => $this->request->params['slug']),
		));
        
        // If the user is not an Admin or Editor, redirect to the record view
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
        	return $this->redirect(array(
        		'Documents::view', 'args' => array($this->request->params['slug']))
        	);
        }
        
        // For the following to work, the delete form must have an explicit 'method' => 'post'
        // since the default method is PUT
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Documents::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		$document->delete();
		return $this->redirect('Documents::index');
	}
}

?>
