<?php

namespace app\controllers;

use app\models\Publications;
use app\models\PublicationsHistories;
use app\models\PublicationsDocuments;
use app\models\PublicationsLinks;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class PublicationsController extends \lithium\action\Controller {

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

		$limit = 50;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$order = array('earliest_date' => 'DESC');

		$conditions = array();

		$options = $this->request->query;

		if (isset($options['type'])) {
			$type = $options['type'];
			$conditions = compact('type');
		}

		$total = Publications::count('all', array(
			'conditions' => $conditions
		));
		
		$publications = Publications::find('all', array(
			'with' => 'Archives',
			'limit' => $limit,
			'order' => $order,
			'conditions' => $conditions,
			'page' => $page
		));

		$publications_types = Publications::types();

		return compact('publications', 'publications_types', 'total', 'page', 'limit', 'auth', 'options');
	}
	
	public function search() {

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
		
		$publications = array();

		$order = array('earliest_date' => 'DESC');

		$data = $this->request->data;

		$query = '';
		$condition = '';

		if (isset($data['conditions'])) {
			$condition = $data['conditions'];

			if ($condition == 'year') {
				$condition = 'earliest_date';
			}

			$query = $data['query'];
			$conditions = array("$condition" => array('LIKE' => "%$query%"));

			$publications = Publications::find('all', array(
				'with' => 'PublicationsDocuments',
				'order' => $order,
				'conditions' => $conditions,
			));

			if ($condition == 'earliest_date') {
				$condition = 'year';
			}
		}

		$publications_types = Publications::types();

		return compact('publications', 'publications_types', 'condition', 'query', 'auth');
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
			$publication = Publications::first(array(
				'with' => 'Archives',
				'conditions' => array('slug' => $this->request->params['slug']),
			));

			if($publication) {
		
				$publication_documents = PublicationsDocuments::find('all', array(
					'with' => array(
						'Documents',
						'Formats'
					),
					'conditions' => array('publication_id' => $publication->id),
					'order' => array('slug' => 'ASC')
				));

				$publication_links = PublicationsLinks::find('all', array(
					'with' => array(
						'Links'
					),
					'conditions' => array('publication_id' => $publication->id),
					'order' => array('date_modified' =>  'DESC')
				));
			
				//Send the retrieved data to the view
				return compact('publication', 'publication_documents', 'publication_links','auth');

			}
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Publications::index'));
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
        	return $this->redirect('Publications::index');
        }

		$publications_types = Publications::types();
		$pub_types_list = array_combine($publications_types, $publications_types);

		$publication = Publications::create();

		if (($this->request->data) && $publication->save($this->request->data)) {
			return $this->redirect(array('Publications::view', 'args' => array($publication->slug)));
		}
		return compact('publication', 'pub_types_list');
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
		
        // If the user is not an Admin or Editor, redirect to the record view
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
        	return $this->redirect(array(
        		'Publications::view', 'args' => array($this->request->params['slug']))
        	);
        }

		$publication = Publications::first(array(
			'with' => 'Archives',
			'conditions' => array('slug' => $this->request->params['slug'])
		));

		$publications_types = Publications::types();
		$pub_types_list = array_combine($publications_types, $publications_types);

		if (!$publication) {
			return $this->redirect('Publications::index');
		}

		$publication_documents = PublicationsDocuments::find('all', array(
			'with' => array(
				'Documents',
				'Formats'
			),
			'conditions' => array('publication_id' => $publication->id),
			'order' => array('slug' => 'ASC')
		));

		$publication_links = PublicationsLinks::find('all', array(
			'with' => array(
				'Links'
			),
			'conditions' => array('publication_id' => $publication->id),
			'order' => array('date_modified' =>  'DESC')
		));

		if (($this->request->data) && $publication->save($this->request->data)) {
			return $this->redirect(array('Publications::view', 'args' => array($publication->archive->slug)));
		}
		
		return compact('publication', 'pub_types_list', 'publication_documents', 'publication_links');
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
        
		$publication = Publications::first(array(
			'conditions' => array('slug' => $this->request->params['slug']),
		));
        
        // If the user is not an Admin or Editor, redirect to the record view
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
        	return $this->redirect(array(
        		'Publications::view', 'args' => array($this->request->params['slug']))
        	);
        }
        
        // For the following to work, the delete form must have an explicit 'method' => 'post'
        // since the default method is PUT
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Publications::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		$publication->delete();
		return $this->redirect('Publications::index');
	}
}

?>
