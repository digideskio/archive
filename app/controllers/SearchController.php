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
use lithium\core\Environment;

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
		$architectures = array();
		$exhibitions = array();
		$publications = array();
		$documents = array();

		$query = '';

		if (isset($data['query']) && $data['query']) {
			$query = $data['query'];
			$esc_query = mysql_escape_string($query);
        
			$order = array('earliest_date' => 'DESC');

			$work_conditions = "((`title` LIKE '%$esc_query%') OR (`artist` LIKE '%$esc_query%') OR (`classification` LIKE '%$esc_query%') OR (`earliest_date` LIKE '%$esc_query%') OR (`materials` LIKE '%$esc_query%') OR (`remarks` LIKE '%$esc_query%') OR (`creation_number` LIKE '%$esc_query%') OR (`annotation` LIKE '%$esc_query%'))";

			$works = Works::find('artworks', array(
				'with' => 'Archives',
				'conditions' => $work_conditions
			));

			$architecture_conditions = "((`title` LIKE '%$esc_query%') OR (`architect` LIKE '%$esc_query%') OR (`client` LIKE '%$esc_query%') OR (`project_lead` LIKE '%$esc_query%') OR (`earliest_date` LIKE '%$esc_query%') OR (`status` LIKE '%$esc_query%') OR (`location` LIKE '%$esc_query%') OR (`city` LIKE '%$esc_query%') OR (`country` LIKE '%$esc_query%') OR (`remarks` LIKE '%$esc_query%'))";

			$architectures = Architectures::find('all', array(
				'with' => 'Archives',
				'order' => $order,
				'conditions' => $architecture_conditions,
			));

			$exhibition_conditions = "((`title` LIKE '%$esc_query%') OR (`venue` LIKE '%$esc_query%') OR (`curator` LIKE '%$esc_query%') OR (`earliest_date` LIKE '%$esc_query%') OR (`city` LIKE '%$esc_query%') OR (`country` LIKE '%$esc_query%') OR (`remarks` LIKE '%$esc_query%'))";

			//FIXME trying to find:: with => Components seems to mess up the conditions and page
			$exhibitions = Exhibitions::find('all', array(
				'with' => array('Archives'),
				'order' => $order,
				'conditions' => $exhibition_conditions,
			));

			$publication_conditions = "((`title` LIKE '%$esc_query%') OR (`author` LIKE '%$esc_query%') OR (`publisher` LIKE '%$esc_query%') OR (`editor` LIKE '%$esc_query%') OR (`earliest_date` LIKE '%$esc_query%') OR (`subject` LIKE '%$esc_query%') OR (`language` LIKE '%$esc_query%') OR (`storage_location` LIKE '%$esc_query%') OR (`storage_number` LIKE '%$esc_query%') OR (`publication_number` LIKE '%$esc_query%'))";

			$publications = Publications::find('all', array(
				'with' => 'Archives',
				'order' => $order,
				'conditions' => $publication_conditions,
			));

			$doc_conditions = "((`title` LIKE '%$esc_query%') OR (`date_created` LIKE '%$esc_query%') OR (`repository` LIKE '%$esc_query%') OR (`credit` LIKE '%$esc_query%') OR (`remarks` LIKE '%$esc_query%'))";

			$documents = Documents::find('all', array(
				'conditions' => $doc_conditions
			));

		}

		$architecture = Environment::get('architecture');		
        
        return compact('works', 'architectures', 'exhibitions', 'publications', 'documents', 'query', 'architecture', 'auth');
        
	}
	

}
