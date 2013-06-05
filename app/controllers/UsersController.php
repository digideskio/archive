<?php

namespace app\controllers;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

use li3_access\security\Access;

class UsersController extends \lithium\action\Controller {

	public $rules = array(
		'index' => array(
			array('rule' => 'allowAdminUser', 'message' => 'Access Denied.', 'redirect' => "Pages::home"),
		),
		'view' => array(
			array('rule' => 'allowAnyUser', 'message' => 'You are not logged in.', 'redirect' => "Sessions::add"),
		),
		'add' => array(
			array('rule' => 'allowAdminUser', 'message' => 'Admin Access Denied.', 'redirect' => "Pages::home"),
		),
		'edit' => array(
			array('rule' => 'allowAdminOrUserRequestingSelf', 'message' => 'Access Denied.', 'redirect' => "Pages::home"),
		),
		'delete' => array(
			array('rule' => 'allowAdminUser', 'message' => 'Access Denied.', 'redirect' => "Pages::home"),
			array('rule' => 'denyUserRequestingSelf', 'message' => 'Access Denied.', 'redirect' => "Pages::home"),
		),
	);

	public function index() {

    	// Check authorization
	    $check = (Auth::check('default')) ?: null;
		
        // Look up the current user with his or her role
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
    
		// Look up all users
		$users = Users::find('all', array(
			'with' => array('Roles')
		));
		
		// Send a list of users, plus the current user, to the index view
		return compact('users', 'auth');
	}

	public function view() {
    
	    $check = (Auth::check('default')) ?: null;

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

	    $check = (Auth::check('default')) ?: null;
		
        // Look up the current user with his or her role
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
		
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
	
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
        
		$user = Users::first(array(
			'conditions' => array('username' => $this->request->params['username']),
			'with' => array('Roles')
		));
        
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
