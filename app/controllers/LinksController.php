<?php

namespace app\controllers;

use app\models\Links;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class LinksController extends \lithium\action\Controller {

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

		$order = array('date_modified' => 'DESC');

		$links = Links::all(array(
			'order' => $order
		));
		return compact('links', 'auth');
	}

	public function view() {
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

		$link = Links::first($this->request->id);
		return compact('link', 'auth');
	}

	public function add() {
    	// Check authorization
	    $check = (Auth::check('default')) ?: null;
	
		// If the user is not authorized, redirect to the login screen
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
        
        // If the user is not an Admin or Editor, redirect to the index
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
        	return $this->redirect('Links::index');
        }

		$link = Links::create();

		if (($this->request->data) && $link->save($this->request->data)) {
			return $this->redirect(array('Links::view', 'args' => array($link->id)));
		}
		return compact('link');
	}

	public function edit() {
    
	    $check = (Auth::check('default')) ?: null;
	
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
		// If the user is not authorized, redirect to the login screen
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
		
        // If the user is not an Admin or Editor, redirect to the record view
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
        	return $this->redirect(array(
        		'Links::view', 'args' => array($this->request->id))
        	);
        }

		$link = Links::find($this->request->id);

		if (!$link) {
			return $this->redirect('Links::index');
		}
		if (($this->request->data) && $link->save($this->request->data)) {
			return $this->redirect(array('Links::view', 'args' => array($link->id)));
		}
		return compact('link', 'auth');
	}

	public function delete() {
	    $check = (Auth::check('default')) ?: null;
	
		// If the user is not authorized, redirect to the login screen
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));

		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Links::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}

        // If the user is not an Admin or Editor, redirect to the record view
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
        	return $this->redirect(array(
        		'Links::view', 'args' => array($this->request->id))
        	);
        }

		Links::find($this->request->id)->delete();
		return $this->redirect('Links::index');
	}
}

?>
