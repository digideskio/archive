<?php

namespace app\controllers;

use app\models\Publications;
use app\models\PublicationsHistories;
use app\models\PublicationsDocuments;
use app\models\PublicationsLinks;

use app\models\Archives;
use app\models\ArchivesHistories;

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

		if (isset($options['classification'])) {
			$classification = $options['classification'];
			$conditions = compact('classification');
		}

		$total = Publications::count('all', array(
			'with' => 'Archives',
			'conditions' => $conditions
		));
		
		$publications = Publications::find('all', array(
			'with' => 'Archives',
			'limit' => $limit,
			'order' => $order,
			'conditions' => $conditions,
			'page' => $page
		));

		$pub_classifications = Publications::classifications();

		return compact('publications', 'pub_classifications', 'total', 'page', 'limit', 'auth', 'options');
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
				'with' => 'Archives',
				'order' => $order,
				'conditions' => $conditions,
			));

			if ($condition == 'earliest_date') {
				$condition = 'year';
			}
		}

		$pub_classifications = Publications::classifications();

		return compact('publications', 'pub_classifications', 'condition', 'query', 'auth');
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

		$pub_classifications = Publications::classifications();
		$pub_classes_list = array_combine($pub_classifications, $pub_classifications);

		$publication = Publications::create();

		if (($this->request->data) && $publication->save($this->request->data)) {
			//The slug has been saved with the Archive object, so let's look it up
			$archive = Archives::find('first', array(
				'conditions' => array('id' => $publication->id)
			));
			return $this->redirect(array('Publications::view', 'args' => array($archive->slug)));
		}
		return compact('publication', 'pub_classes_list');
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

		$pub_classifications = Publications::classifications();
		$pub_classes_list = array_combine($pub_classifications, $pub_classifications);

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
		
		return compact('publication', 'pub_classes_list', 'publication_documents', 'publication_links');
	}

	public function history() {
	
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

				$archives_histories = ArchivesHistories::find('all', array(
					'conditions' => array('ArchivesHistories.archive_id' => $publication->id),
					'order' => 'ArchivesHistories.start_date DESC',
					'with' => array('Users', 'PublicationsHistories'),
				));
		
				//Send the retrieved data to the view
				return compact('auth', 'publication', 'archives_histories', 'auth');
			}
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Works::index'));
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
			'with' => 'Archives',
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
