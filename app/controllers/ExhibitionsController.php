<?php

namespace app\controllers;

use app\models\Exhibitions;
use app\models\ExhibitionsWorks;
use app\models\Works;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class ExhibitionsController extends \lithium\action\Controller {

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
		
	 	$order = 'earliest_date DESC';
		
		$exhibitions = Exhibitions::find('all', array(
			'with' => 'ExhibitionsWorks',
			'order' => $order
		));
		return compact('exhibitions', 'auth');
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
			$exhibition = Exhibitions::first(array(
				'conditions' => array('slug' => $this->request->params['slug'])
			));
		
			$total = ExhibitionsWorks::find('count', array(
				'conditions' => array('exhibition_id' => $exhibition->id)
			));
			$order = 'earliest_date DESC';
			
			$exhibition_works = ExhibitionsWorks::find('all', array(
				'with' => 'Works',
				'conditions' => array('exhibition_id' => $exhibition->id),
				'order' => $order
			));
			
			//Send the retrieved data to the view
			return compact('exhibition', 'exhibition_works', 'total', 'auth');
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Exhibitions::index'));
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
        	return $this->redirect('Exhibitions::index');
        }
        
		$exhibition = Exhibitions::create();

		if (($this->request->data) && $exhibition->save($this->request->data)) {
			return $this->redirect(array('Exhibitions::view', 'args' => array($exhibition->slug)));
		}
		return compact('exhibition');
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
		
		$exhibition = Exhibitions::first(array(
			'conditions' => array('slug' => $this->request->params['slug'])
		));

		if (!$exhibition) {
			return $this->redirect('Exhibitions::index');
		}
		if (($this->request->data) && $exhibition->save($this->request->data)) {
			return $this->redirect(array('Exhibitions::view', 'args' => array($exhibition->slug)));
		}
		
		// If the database times are zero, just show an empty string
		if($exhibition->earliest_date == '0000-00-00 00:00:00') {
			$exhibition->earliest_date = '';
		}
		
		if($exhibition->latest_date == '0000-00-00 00:00:00') {
			$exhibition->latest_date = '';
		}
		
		return compact('exhibition');
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
        
		$exhibition = Exhibitions::first(array(
			'conditions' => array('slug' => $this->request->params['slug']),
		));
        
        // If the user is not an Admin or Editor, redirect to the record view
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
        	return $this->redirect(array(
        		'Exhibitions::view', 'args' => array($this->request->params['slug']))
        	);
        }
        
        // For the following to work, the delete form must have an explicit 'method' => 'post'
        // since the default method is PUT
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Exhibitions::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		$exhibition->delete();
		return $this->redirect('Exhibitions::index');
	}
}

?>
