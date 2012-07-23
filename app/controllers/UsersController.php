<?php

namespace app\controllers;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

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
			
			//Send the retrieved post data to the view
			return compact('user', 'auth');
		}
		
		//since no username was specified, redirect to the index page
		$this->redirect(array('Users::index'));
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
        
        // If the user is not an Admin, redirect to the index
        if($auth->role->name != 'Admin') {
        	return $this->redirect('Users::index');
        }
        
        //TODO don't repeat the entire validation from the Users model
        //instead, just add the uniqueUsername rule when creating a new user
        $validates = array(
			'username' => array(
				array('uniqueUsername', 'message' => 'This username is already taken.'),
				array('notEmpty', 'message' => 'Please enter a username.'),
				array('alphaNumeric', 'skipEmpty' => true, 'message' => 'Alphanumeric characters only.'),
			),
		    'password' => array(
		        array('notEmpty', 'message'=>'Please enter a password.')
		    ),
		    'name' => array(
		        array('notEmpty', 'message'=>'Please enter a full name.')
		    ),
		    'email' => array(
		        array('notEmpty', 'message'=>'Include an email address.'),
		        array('email', 'skipEmpty' => true, 'message' => 'The email address must be valid.')
		    ),
		);
		
		$user = Users::create();
		$roles = Roles::all();

		if (($this->request->data) && $user->save($this->request->data, array('validate' => $validates))) {
			return $this->redirect(array('Users::view', 'args' => array($user->username)));
		}
		return compact('user', 'roles');
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
        
        // If the user is not an Admin, unset the role_id from form submit just in case,
        if($auth->role->name != 'Admin' && isset($this->request->data['role_id'])) {
        	unset($this->request->data['role_id']);
        }
		
		if (($this->request->data) && $user->save($this->request->data)) {
			return $this->redirect(array('Users::view', 'args' => array($user->username)));
		}
		return compact('user', 'roles', 'auth');
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
}

?>
