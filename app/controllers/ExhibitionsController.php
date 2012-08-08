<?php

namespace app\controllers;

use app\models\Collections;
use app\models\CollectionsWorks;
use app\models\Exhibitions;
use app\models\Works;
use app\models\Dates;

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
		
	 	$order = 'start DESC';
		
		$collections = Collections::find('all', array(
			'with' => array('CollectionsWorks', 'Dates', 'Exhibitions'),
			'order' => $order,
			'conditions' => array ('class' => 'exhibition')
		));
		
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
			$collection = Collections::find('first', array(
				'with' => array('Dates', 'Exhibitions'),
				'conditions' => array(
				'slug' => $this->request->params['slug'],
				'class' => 'exhibition'
			)));
		
			$total = CollectionsWorks::find('count', array(
				'conditions' => array('collection_id' => $collection->id)
			));
			$order = 'earliest_date DESC';
			
			$collections_works = CollectionsWorks::find('all', array(
				'with' => 'Works',
				'conditions' => array('collection_id' => $collection->id),
				'order' => $order
			));
			
			//Send the retrieved data to the view
			return compact('collection', 'collections_works', 'total', 'auth');
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
        
		$collection = Collections::create(array('class' => 'exhibition'));

		if (($this->request->data) && $collection->save($this->request->data)) {
		
			$this->request->data['collection_id'] = $collection->id;
			
			$exhibition = Exhibitions::create();
			$exhibition->save($this->request->data);
			
			$date = Dates::create();
			$date->save($this->request->data);
			
			return $this->redirect(array('Exhibitions::view', 'args' => array($collection->slug)));
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
		
		$collection = Collections::find('first', array(
			'with' => array('Dates', 'Exhibitions'),
			'conditions' => array(
			'slug' => $this->request->params['slug'],
			'class' => 'exhibition'
		)));

		if (!$collection) {
			return $this->redirect('Exhibitions::index');
		}
		if (($this->request->data) && $collection->save($this->request->data)) {
		
			$collection->exhibition->save($this->request->data);
			$collection->date->save($this->request->data);
		
			return $this->redirect(array('Exhibitions::view', 'args' => array($collection->slug)));
		}
		
		// If the database times are zero, just show an empty string
		if($collection->date->start == '0000-00-00 00:00:00') {
			$collection->date->start = '';
		}
		
		if($collection->date->end == '0000-00-00 00:00:00') {
			$collection->date->end = '';
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
		
		$collection = Collections::find('first', array(
			'with' => array('Dates', 'Exhibitions'),
			'conditions' => array(
			'slug' => $this->request->params['slug'],
			'class' => 'exhibition'
		)));
        
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
		
		$collection->delete();
		$collection->exhibition->delete();
		return $this->redirect('Exhibitions::index');
	}
}

?>
