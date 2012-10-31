<?php

namespace app\controllers;

use app\models\Notices;

use app\models\Users;

use lithium\action\DispatchException;
use lithium\security\Auth;

class NoticesController extends \lithium\action\Controller {

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

		$notices = Notices::all(array(
			'order' => $order
		));
		return compact('notices', 'auth');
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

		$notice = Notices::first($this->request->id);
		return compact('notice');
	}

	public function add() {
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

        // If the user is not an Admin, redirect to the index
        if($auth->role->name != 'Admin') {
        	return $this->redirect('Notices::index');
        }

		$notice = Notices::create();

		if (($this->request->data) && $notice->save($this->request->data)) {
			return $this->redirect(array('Notices::index'));
		}
		return compact('notice');
	}

	public function edit() {
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

        // If the user is not an Admin, redirect to the index
        if($auth->role->name != 'Admin') {
        	return $this->redirect('Notices::index');
        }

		$notice = Notices::find($this->request->id);

		if (!$notice) {
			return $this->redirect('Notices::index');
		}
		if (($this->request->data) && $notice->save($this->request->data)) {
			return $this->redirect(array('Notices::index'));
		}
		return compact('notice', 'auth');
	}

	public function delete() {
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

        // If the user is not an Admin, redirect to the index
        if($auth->role->name != 'Admin') {
        	return $this->redirect('Notices::index');
        }

		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Notices::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		Notices::find($this->request->id)->delete();
		return $this->redirect('Notices::index');
	}
}

?>
