<?php

namespace app\controllers;

use app\models\Links;

use app\models\WorksLinks;

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

		$saved = isset($this->request->query['saved']) ? $this->request->query['saved'] : '';

		$limit = 50;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$order = array('date_modified' => 'DESC');

		$total = Links::count();

		$links = Links::all(array(
			'with' => array('WorksLinks'),
			'limit' => $limit,
			'order' => $order,
			'page' => $page
		));
		return compact('links', 'total', 'page', 'limit', 'auth', 'saved');
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

		$works_links = WorksLinks::find('all', array(
			'with' => array('Works'),
			'conditions' => array('link_id' => $link->id),
			'order' => array('earliest_date' => 'DESC')
		));

		return compact('link', 'works_links', 'auth');
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
			//return $this->redirect(array('Links::view', 'args' => array($link->id)));
        	return $this->redirect("/links?saved=$link->id");
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
		$redirect = isset($this->request->query['work']) ? '/works/edit/'.$this->request->query['work'] : '';

		if (!$link) {
			return $this->redirect('Links::index');
		}
		if (($this->request->data) && $link->save($this->request->data)) {
			//return $this->redirect(array('Links::view', 'args' => array($link->id)));

			if ($this->request->data['redirect']) {
				return $this->redirect($this->request->data['redirect']);
			} else {
	      		return $this->redirect("/links?saved=$link->id");
			}
		}
		return compact('link', 'auth', 'redirect');
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
