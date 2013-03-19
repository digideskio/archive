<?php

namespace app\controllers;

use app\models\Archives;
use app\models\Exhibitions;
use app\models\ExhibitionsWorks;
use app\models\Works;
use app\models\ExhibitionsDocuments;
use app\models\ExhibitionsLinks;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class ExhibitionsController extends \lithium\action\Controller {

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
		
	 	$order = 'earliest_date DESC';
		
		$exhibitions = Exhibitions::find('all', array(
			'with' => array('Archives', 'ExhibitionsWorks'),
			'order' => $order,
		));
		
		return compact('exhibitions', 'auth');
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

		$exhibitions = array();

		$order = array('earliest_date' => 'DESC');

		$condition = '';
		$query = '';
		$type = 'All';

		$data = $this->request->data;

		if (isset($data['conditions'])) {
			$condition = $data['conditions'];
			$type = $data['type'];

			if ($condition == 'year') {
				$condition = 'earliest_date';
			}

			$query = $data['query'];
			$conditions = array("$condition" => array('LIKE' => "%$query%"));

			if($type != 'All') {
				$conditions['type'] = $type;
			}

			$exhibitions = Exhibitions::find('all', array(
				'with' => array('Archives', 'ExhibitionsWorks'),
				'order' => $order,
				'conditions' => $conditions
			));

			if ($condition == 'earliest_date') {
				$condition = 'year';
			}
		}

		return compact('exhibitions', 'condition', 'type', 'query', 'auth');
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
			$exhibition = Exhibitions::find('first', array(
				'with' => 'Archives',
				'conditions' => array(
				'slug' => $this->request->params['slug'],
			)));
		
			$total = ExhibitionsWorks::find('count', array(
				'conditions' => array('exhibition_id' => $exhibition->id)
			));
			
			$exhibitions_works = ExhibitionsWorks::find('all', array(
				'fields' => 'work_id',
				'conditions' => array('exhibition_id' => $exhibition->id),
			));

			$works = array();

			if ($exhibitions_works->count()) {

				//Get all the work IDs in a plain array
				$work_ids = $exhibitions_works->map(function($ew) {
					return $ew->work_id;
				}, array('collect' => false));

				$works = Works::find('all', array(
					'with' => 'Archives',
					'conditions' => array('Works.id' => $work_ids),
					'order' => 'earliest_date DESC'
				));

			}

			$exhibitions_links = ExhibitionsLinks::find('all', array(
				'with' => 'Links',
				'conditions' => array('exhibition_id' => $exhibition->id),
				'order' => array('date_modified' =>  'DESC')
			));

			$exhibition_documents = ExhibitionsDocuments::find('all', array(
				'with' => array(
					'Documents',
					'Formats'
				),
				'conditions' => array('exhibition_id' => $exhibition->id),
				'order' => array('slug' => 'ASC')
			));
			
			//Send the retrieved data to the view
			return compact('exhibition', 'works', 'total', 'exhibition_documents', 'exhibitions_links', 'auth');
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Exhibitions::index'));
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
        	return $this->redirect('Exhibitions::index');
        }
        
		$exhibition = Exhibitions::create();

		if (($this->request->data) && $exhibition->save($this->request->data)) {
			//The slug has been saved with the Archive object, so let's look it up
			$archive = Archives::find('first', array(
				'conditions' => array('id' => $exhibition->id)
			));
		
			return $this->redirect(array('Exhibitions::view', 'args' => array($archive->slug)));
		}

		$exhibition_titles = Exhibitions::find('all', array(
			'fields' => 'title',
			'group' => 'title',
			'conditions' => array('title' => array('!=' => '')),
			'order' => array('title' => 'ASC'),
		));

		$titles = $exhibition_titles->map(function($tit) {
			return $tit->title;
		}, array('collect' => false));

		$exhibition_venues = Exhibitions::find('all', array(
			'fields' => 'venue',
			'group' => 'venue',
			'conditions' => array('venue' => array('!=' => '')),
			'order' => array('venue' => 'ASC'),
		));

		$venues = $exhibition_venues->map(function($ven) {
			return $ven->venue;
		}, array('collect' => false));

		$exhibition_cities = Exhibitions::find('all', array(
			'fields' => 'city',
			'group' => 'city',
			'conditions' => array('city' => array('!=' => '')),
			'order' => array('city' => 'ASC'),
		));

		$cities = $exhibition_cities->map(function($cit) {
			return $cit->city;
		}, array('collect' => false));

		$exhibition_countries = Exhibitions::find('all', array(
			'fields' => 'country',
			'group' => 'country',
			'conditions' => array('country' => array('!=' => '')),
			'order' => array('country' => 'ASC'),
		));

		$countries = $exhibition_countries->map(function($con) {
			return $con->country;
		}, array('collect' => false));

		return compact('exhibition', 'titles', 'venues', 'cities', 'countries');
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
		
		$exhibition = Exhibitions::find('first', array(
			'with' => 'Archives',
			'conditions' => array(
				'slug' => $this->request->params['slug'],
		)));

		if (!$exhibition) {
			return $this->redirect('Exhibitions::index');
		}

		$exhibition_links = ExhibitionsLinks::find('all', array(
			'with' => 'Links',
			'conditions' => array('exhibition_id' => $exhibition->id),
			'order' => array('date_modified' =>  'DESC')
		));

		$exhibition_documents = ExhibitionsDocuments::find('all', array(
			'with' => array(
				'Documents',
				'Formats'
			),
			'conditions' => array('exhibition_id' => $exhibition->id),
			'order' => array('slug' => 'ASC')
		));

		if (($this->request->data) && $exhibition->save($this->request->data)) {
		
			$exhibition->save($this->request->data);
		
			return $this->redirect(array('Exhibitions::view', 'args' => array($exhibition->archive->slug)));
		}

		$exhibition_titles = Exhibitions::find('all', array(
			'fields' => 'title',
			'group' => 'title',
			'conditions' => array('title' => array('!=' => '')),
			'order' => array('title' => 'ASC'),
		));

		$titles = $exhibition_titles->map(function($tit) {
			return $tit->title;
		}, array('collect' => false));

		$exhibition_venues = Exhibitions::find('all', array(
			'fields' => 'venue',
			'group' => 'venue',
			'conditions' => array('venue' => array('!=' => '')),
			'order' => array('venue' => 'ASC'),
		));

		$venues = $exhibition_venues->map(function($ven) {
			return $ven->venue;
		}, array('collect' => false));

		$exhibition_cities = Exhibitions::find('all', array(
			'fields' => 'city',
			'group' => 'city',
			'conditions' => array('city' => array('!=' => '')),
			'order' => array('city' => 'ASC'),
		));

		$cities = $exhibition_cities->map(function($cit) {
			return $cit->city;
		}, array('collect' => false));

		$exhibition_countries = Exhibitions::find('all', array(
			'fields' => 'country',
			'group' => 'country',
			'conditions' => array('country' => array('!=' => '')),
			'order' => array('country' => 'ASC'),
		));

		$countries = $exhibition_countries->map(function($con) {
			return $con->country;
		}, array('collect' => false));
		
		return compact('exhibition', 'exhibition_documents', 'exhibition_links', 'titles', 'venues', 'cities', 'countries');
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
		
		$exhibition = Exhibitions::find('first', array(
			'with' => 'Archives',
			'conditions' => array(
			'slug' => $this->request->params['slug'],
		)));
        
        // If the user is not an Admin or Editor, redirect to the record view
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
        	return $this->redirect(array(
        		'Exhibitions::view', 'args' => array($this->request->params['slug']))
        	);
        }
        
        // For the following to work, the delete form must have an explicit 'method' => 'post'
        // since the default method is PUT
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Exhibitions::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		$exhibition->delete();
		return $this->redirect('Exhibitions::index');
	}
}

?>
