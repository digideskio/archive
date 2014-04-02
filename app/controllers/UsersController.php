<?php

namespace app\controllers;

use app\models\Users;
use app\models\Roles;
use app\models\ArchivesHistories;

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
		'activate' => array(
			array('rule' => 'allowAdminUser', 'message' => 'Access Denied.', 'redirect' => "Pages::home"),
			array('rule' => 'denyUserRequestingSelf', 'message' => 'Access Denied.', 'redirect' => "Pages::home"),
		),
	);

	public function index() {

		$users = Users::find('all', array(
			'with' => array('Roles'),
			'order' => array('active' => 'DESC', 'username' => 'ASC')
		));

		return compact('users');
	}

	public function view() {

		//Dont run the query if no username is provided
		if(isset($this->request->params['username'])) {

			//Get single record from the database where the username matches the URL
			$user = Users::first(array(
				'conditions' => array('username' => $this->request->params['username']),
				'with' => array('Roles')
			));

			$limit = 50;
			$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
			$order = array('start_date' => 'DESC');
			$total = ArchivesHistories::find('count', array(
				'with' => array('Users', 'Archives'),
				'conditions' => array('Users.username' => $user->username),
			));
			$archives_histories = ArchivesHistories::find('all', array(
				'with' => array('Users', 'Archives'),
				'conditions' => array('Users.username' => $user->username),
				'limit' => $limit,
				'order' => $order,
				'page' => $page
			));

			//Send the retrieved data to the view
			return compact('user', 'archives_histories', 'total', 'page', 'limit');
		}

		//since no username was specified, redirect to the index page
		$this->redirect(array('Users::index'));
	}

	public function add() {

		$user = Users::create();
		$roles = Roles::all();
		$role_list = array();

		foreach($roles as $role) {
			$role_list[$role->id] = $role->name;
		}

		if (($this->request->data) && $user->save($this->request->data)) {
			return $this->redirect(array('Users::view', 'username' => $user->username));
		}
		return compact('user', 'role_list');
	}

	public function edit() {

		$username = $this->request->params['username'];

		$user = Users::first(array(
			'conditions' => array('username' => $username),
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

		if (($this->request->data)) {

			$check = (Auth::check('default')) ?: null;

			if ($check) {

				$auth = Users::first(array(
					'conditions' => array('username' => $check['username']),
					'with' => array('Roles')
				));

				if ($auth) {

					// Define a whitelist of the fields that can be modified
					$whitelist = array('name', 'email', 'password');

					// If the user is an Admin, add role_id to the whitelist
					if($auth->role->name == 'Admin') {
						array_push($whitelist, 'role_id');
					}

					if ($user->save($this->request->data, compact('whitelist'))) {
						return $this->redirect(array('Users::view', 'username' => $username));
					}
				}
			}
		}
		return compact('user', 'role_list');
	}

	public function activate() {

		$user = Users::first(array(
			'conditions' => array('username' => $this->request->params['username']),
			'with' => array('Roles')
		));

        // For the following to work, the form must have an explicit 'method' => 'post'
        // since the default method is PUT
		if (!$this->request->is('post')) {
			$msg = "Users::activate can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}

		// Set the user to active
		$user->active = true;
		$user->save();

		return $this->redirect(array('Users::view', 'username' => $user->username));
	}

	public function delete() {

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

		// Set the user to in-active
		$user->active = false;
		$user->save();

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
