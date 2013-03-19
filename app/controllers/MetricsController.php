<?php

namespace app\controllers;

use app\models\Albums;
use app\models\AlbumsWorks;
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

use lithium\data\Model;

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

		$albums = Albums::count();
		$albums_works = AlbumsWorks::count();
		$works = Works::count();
		$works_documents = WorksDocuments::count();
		$architectures = Architectures::count();
		$architectures_documents = ArchitecturesDocuments::count();
		$exhibitions = Exhibitions::count();
		$solo_shows = Exhibitions::count('all', array(
			'with' => 'Archives',
			'conditions' => array('type' => 'Solo')
		));
		$group_shows = Exhibitions::count('all', array(
			'with' => 'Archives',
			'conditions' => array('type' => 'Group')
		));
		$exhibitions_works = ExhibitionsWorks::count();
		$documents = Documents::count();
		$publications = Publications::count();
		$publications_documents = PublicationsDocuments::count();

		$no_date = array('earliest_date' => '0000-00-00');

		$works_years = Model::connection()->read("SELECT count(*) as records, YEAR(earliest_date) AS year FROM archives WHERE controller = 'works' and YEAR(earliest_date) != '0' GROUP BY year ORDER BY year ASC");

		$architectures_years = Model::connection()->read("SELECT count(*) as records, YEAR(earliest_date) AS year FROM archives WHERE controller = 'architectures' and  YEAR(earliest_date) != '0' GROUP BY year ORDER BY year ASC");

		$exhibitions_years = Model::connection()->read("SELECT count(*) as records, YEAR(earliest_date) AS year FROM archives WHERE controller = 'exhibitions' AND YEAR(earliest_date) != '0' GROUP BY year ORDER BY year ASC");

		$publications_years = Model::connection()->read("SELECT count(*) as records, YEAR(earliest_date) AS year FROM archives WHERE controller = 'publications' and YEAR(earliest_date) != '0' GROUP BY year ORDER BY year ASC");

		$publications_languages = Model::connection()->read(
			"select count(*) as records, languages.name as language from archives left join languages on archives.language_code = languages.code where controller = 'publications' and language_code != ''  group by languages.name order by records DESC"
		);

		return compact(
			'albums', 
			'albums_works', 
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

			'works_years',
			'works_no_year',

			'architectures_years',
			'exhibitions_years',

			'publications_years',
			'publications_languages',

			'auth'
		);
	}

}

?>
