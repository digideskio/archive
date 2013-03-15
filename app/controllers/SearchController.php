<?php

namespace app\controllers;

use app\models\Users;
use app\models\Roles;

use app\models\Works;
use app\models\Documents;
use app\models\WorksDocuments;
use app\models\Albums;
use app\models\Exhibitions;

use lithium\action\DispatchException;
use lithium\security\Auth;

class SearchController extends \lithium\action\Controller {

	public function index() {
    
    	// Check authorization
	    $check = (Auth::check('default')) ?: null;
	
		// If the user is not authorized, redirect to the login screen
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
        $query = $this->request->query['query'];
        
        $order = array('earliest_date' => 'DESC');
        $works = Works::find('all', array(
			'with' => 'Archives',
			'order' => $order,
			'conditions' => array(
				'title' => array(
					'LIKE' => "%$query%"
			))
		));

		$documents = Documents::find('all', array(
			'conditions' => array(
				'slug' => array(
					'LIKE' => "%$query%"
			))
		));
        
        return compact('works', 'documents', 'query', 'auth');
        
	}
	

}
