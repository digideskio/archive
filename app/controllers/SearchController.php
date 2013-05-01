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
        
		$data = $this->request->data ?: $this->request->query;

		$works = array();
		$documents = array();

		if (isset($data['query']) && $data['query']) {
			$query = $data['query'];
        
			$order = array('earliest_date' => 'DESC');

			$work_conditions = "(`title` LIKE '%$query%') OR (`artist` LIKE '%$query%') OR (`classification` LIKE '%$query%') OR (`earliest_date` LIKE '%$query%') OR (`materials` LIKE '%$query%') OR (`remarks` LIKE '%$query%') OR (`creation_number` LIKE '%$query%') OR (`annotation` LIKE '%$query%')";

			$works = Works::find('all', array(
				'with' => 'Archives',
				'order' => $order,
				'conditions' => $work_conditions
			));

			$doc_conditions = "(`title` LIKE '%$query%') OR (`date_created` LIKE '%$query%') OR (`repository` LIKE '%$query%') OR (`credit` LIKE '%$query%') OR (`remarks` LIKE '%$query%')";

			$documents = Documents::find('all', array(
				'conditions' => $doc_conditions
			));

			}
        
        return compact('works', 'documents', 'query', 'auth');
        
	}
	

}
