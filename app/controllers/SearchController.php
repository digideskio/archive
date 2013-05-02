<?php

namespace app\controllers;

use app\models\Users;
use app\models\Roles;

use app\models\Works;
use app\models\Architectures;
use app\models\Documents;
use app\models\WorksDocuments;
use app\models\Albums;
use app\models\Exhibitions;
use app\models\Publications;

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

			$architecture_conditions = "(`title` LIKE '%$query%') OR (`client` LIKE '%$query%') OR (`project_lead` LIKE '%$query%') OR (`earliest_date` LIKE '%$query%') OR (`status` LIKE '%$query%') OR (`location` LIKE '%$query%') OR (`city` LIKE '%$query%') OR (`country` LIKE '%$query%') OR (`remarks` LIKE '%$query%')";

			$architectures = Architectures::find('all', array(
				'with' => 'Archives',
				'order' => $order,
				'conditions' => $architecture_conditions,
			));

			$exhibition_conditions = "(`title` LIKE '%$query%') OR (`venue` LIKE '%$query%') OR (`curator` LIKE '%$query%') OR (`earliest_date` LIKE '%$query%') OR (`city` LIKE '%$query%') OR (`country` LIKE '%$query%') OR (`remarks` LIKE '%$query%')";

			//FIXME trying to find:: with => Components seems to mess up the conditions and page
			$exhibitions = Exhibitions::find('all', array(
				'with' => array('Archives'),
				'order' => $order,
				'conditions' => $exhibition_conditions,
			));

			$publication_conditions = "(`title` LIKE '%$query%') OR (`author` LIKE '%$query%') OR (`publisher` LIKE '%$query%') OR (`editor` LIKE '%$query%') OR (`earliest_date` LIKE '%$query%') OR (`subject` LIKE '%$query%') OR (`language` LIKE '%$query%') OR (`storage_location` LIKE '%$query%') OR (`storage_number` LIKE '%$query%') OR (`publication_number` LIKE '%$query%')";

			$publications = Publications::find('all', array(
				'with' => 'Archives',
				'order' => $order,
				'conditions' => $publication_conditions,
			));

			$doc_conditions = "(`title` LIKE '%$query%') OR (`date_created` LIKE '%$query%') OR (`repository` LIKE '%$query%') OR (`credit` LIKE '%$query%') OR (`remarks` LIKE '%$query%')";

			$documents = Documents::find('all', array(
				'conditions' => $doc_conditions
			));

			}
        
        return compact('works', 'architectures', 'exhibitions', 'publications', 'documents', 'query', 'auth');
        
	}
	

}
