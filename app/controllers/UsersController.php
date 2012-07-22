<?php

namespace app\controllers;

use app\models\Users;
use lithium\action\DispatchException;
use lithium\security\Auth;

class UsersController extends \lithium\action\Controller {

	public function index() {
		$users = Users::all();
		return compact('users');
	}

	public function view() {
	
		//Dont run the query if no username is provided
		if($this->request->params['username']) {
		
			//Get single record from the database where the username matches the URL
			$user = Users::first(array(
				'conditions' => array('username' => $this->request->params['username'])
			));
			
			//Send the retrieved post data to the view
			return compact('user');
		}
		
		//since no username was specified, redirect to the index page
		$this->redirect(array('Users::index'));
	}

	public function add() {
        
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

		if (($this->request->data) && $user->save($this->request->data, array('validate' => $validates))) {
			return $this->redirect(array('Users::view', 'args' => array($user->username)));
		}
		return compact('user');
	}

	public function edit() {
		$user = Users::first(array(
			'conditions' => array('username' => $this->request->params['username'])
		));

		if (!$user) {
			return $this->redirect('Users::index');
		}
		if (($this->request->data) && $user->save($this->request->data)) {
			return $this->redirect(array('Users::view', 'args' => array($user->username)));
		}
		return compact('user');
	}

	public function delete() {
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Users::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		Users::find($this->request->id)->delete();
		return $this->redirect('Users::index');
	}
}

?>
