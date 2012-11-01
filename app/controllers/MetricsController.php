<?php

namespace app\controllers;

use app\models\Collections;
use app\models\CollectionsWorks;
use app\models\Works;
use app\models\WorksDocuments;
use app\models\Architectures;
use app\models\ArchitecturesDocuments;
use app\models\Exhibitions;
use app\models\ExhibitionsWorks;
use app\models\Publications;
use app\models\PublicationsDocuments;
use app\models\Documents;

use app\models\Users;
use app\models\Roles;

use lithium\security\Auth;
use lithium\action\DispatchException;

class MetricsController extends \lithium\action\Controller {

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

		$collections = Collections::count();
		$collections_works = CollectionsWorks::count();
		$works = Works::count();
		$works_documents = WorksDocuments::count();
		$architectures = Architectures::count();
		$architectures_documents = ArchitecturesDocuments::count();
		$exhibitions = Exhibitions::count();
		$solo_shows = Exhibitions::count('all', array(
			'conditions' => array('type' => 'Solo')
		));
		$group_shows = Exhibitions::count('all', array(
			'conditions' => array('type' => 'Group')
		));
		$exhibitions_works = ExhibitionsWorks::count();
		$documents = Documents::count();
		$publications = Publications::count();
		$publications_documents = PublicationsDocuments::count();

		return compact(
			'collections', 
			'collections_works', 
			'works', 
			'works_documents', 
			'architectures', 
			'architectures_documents',
			'exhibitions',
			'solo_shows',
			'group_shows',
			'exhibitions_works',
			'documents',
			'publications',
			'publications_documents',
			'auth'
		);
	}

}

?>
