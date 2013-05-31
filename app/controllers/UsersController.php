<?php

namespace app\controllers;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

use li3_access\security\Access;

class UsersController extends \lithium\action\Controller {

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
		
		//Define the access rules for this action
		$rules = array(
			array('rule' => 'allowAdminUser', 'message' => 'You cannot see these users!', 'redirect' => "/users/view/$auth->username"),
		);
		
	    $access = Access::check('rule_based', $check, $this->request, array('rules' => $rules));
	    
        if(!empty($access)){
        	return $this->redirect($access['redirect']);
        }
    
		// Look up all users
		$users = Users::find('all', array(
			'with' => array('Roles')
		));
		
		// Send a list of users, plus the current user, to the index view
		return compact('users', 'auth');
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
	
		//Dont run the query if no username is provided
		if(isset($this->request->params['username'])) {
		
			//Get single record from the database where the username matches the URL
			$user = Users::first(array(
				'conditions' => array('username' => $this->request->params['username']),
				'with' => array('Roles')
			));
			
			//Send the retrieved data to the view
			return compact('user', 'auth');
		}
		
		//since no username was specified, redirect to the index page
		$this->redirect(array('Users::index'));
	}

	public function add() {
		$rules = array(
			array('rule' => 'allowAdminUser', 'message' => 'You cannot add a user!', 'redirect' => '/users'),
		);
		
	    $check = Auth::check('default');

		if($check) {
			$auth = Users::first(array(
				'conditions' => array('username' => $check['username']),
				'with' => array('Roles')
			));
		} else {
			return $this->redirect('Sessions::add');
		}
	    
	    $access = Access::check('rule_based', $check, $this->request, array('rules' => $rules));
	    
        if(!empty($access)){
        	return $this->redirect($access['redirect']);
        }
		
		$user = Users::create();
		$roles = Roles::all();
		$role_list = array();

		foreach($roles as $role) {
			$role_list[$role->id] = $role->name;
		}

		if (($this->request->data) && $user->save($this->request->data)) {
			return $this->redirect(array('Users::view', 'args' => array($user->username)));
		}
		return compact('user', 'role_list');
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
        
		$user = Users::first(array(
			'conditions' => array('username' => $this->request->params['username']),
			'with' => array('Roles')
		));
		
		$roles = Roles::all();
		$role_list = array();

		foreach($roles as $role) {
			$role_list[$role->id] = $role->name;
		}

		if (!$user) {
			return $this->redirect('Users::index');
		}
        
        // If the user is not an Admin, or not editing his or her own profile,
        // redirect to the user's view
        if($auth->role->name != 'Admin' && $auth->username != $user->username) {
        	return $this->redirect(array(
        		'Users::view', 'args' => array($this->request->params['username']))
        	);
        }
        
        // If the user is not an Admin, unset the role_id from form submit just in case
        if($auth->role->name != 'Admin' && isset($this->request->data['role_id'])) {
        	unset($this->request->data['role_id']);
        }
        
        // Unset the username just in case, because we prefer it not to be changed
        unset($this->request->data['username']);
		
		if (($this->request->data) && $user->save($this->request->data)) {
			return $this->redirect(array('Users::view', 'args' => array($user->username)));
		}
		return compact('user', 'role_list', 'auth');
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
        
		$user = Users::first(array(
			'conditions' => array('username' => $this->request->params['username']),
			'with' => array('Roles')
		));
        
        // If the user is not an Admin, or is trying to delete his or her own account
        // redirect to the user's view
        if($auth->role->name != 'Admin' || $auth->username == $user->username) {
        	return $this->redirect(array(
        		'Users::view', 'args' => array($this->request->params['username']))
        	);
        }
        
        // For the following to work, the delete form must have an explicit 'method' => 'post'
        // since the default method is PUT
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Users::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		$user->delete();
			
		return $this->redirect('Users::index');
	}
	
	public function register() {
    	
    	if(Users::count()) {
    		return $this->redirect('/login');
    	}
    	
    	$user = Users::create();
    	
    	if (($this->request->data) && $user->save($this->request->data)) {
    	
        	if(Auth::check('default', $this->request)) {
            	return $this->redirect('/home');
        	}
		}
    	
    	return $this->render(array('template' => 'register', 'layout' => 'simple'));
	
	}
}

?>
