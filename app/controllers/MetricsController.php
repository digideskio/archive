<?php

namespace app\controllers;

use app\models\Albums;
use app\models\Works;
use app\models\Architectures;
use app\models\Exhibitions;
use app\models\Publications;
use app\models\Documents;
use app\models\Components;

use app\models\Users;
use app\models\Roles;

use lithium\data\Model;

use lithium\security\Auth;
use lithium\action\DispatchException;
use lithium\core\Environment;

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
		$albums_works = Components::find('count', array(
			'conditions' => array('type' => 'albums_works')
		));
		$works = Works::count();
		$architectures = Architectures::count();
		$exhibitions = Exhibitions::count();
		$solo_shows = Exhibitions::count('all', array(
			'with' => 'Archives',
			'conditions' => array('type' => 'Solo')
		));
		$group_shows = Exhibitions::count('all', array(
			'with' => 'Archives',
			'conditions' => array('type' => 'Group')
		));
		$exhibitions_works = Components::find('count', array(
			'conditions' => array('type' => 'exhibitions_works')
		));
		$documents = Documents::count();
		$publications = Publications::count();

		$publications_archives_documents = Model::connection()->read("SELECT count(*) as records FROM archives_documents WHERE archive_id IN (SELECT id FROM publications)");
		$pad = $publications_archives_documents[0];
		$publications_documents = $pad['records'];

		$no_date = array('earliest_date' => '0000-00-00');

		$works_years = Model::connection()->read("SELECT count(*) as records, YEAR(earliest_date) AS year FROM archives WHERE controller = 'works' and YEAR(earliest_date) != '0' GROUP BY year ORDER BY year ASC");

		$architectures_years = Model::connection()->read("SELECT count(*) as records, YEAR(earliest_date) AS year FROM archives WHERE controller = 'architectures' and  YEAR(earliest_date) != '0' GROUP BY year ORDER BY year ASC");

		$exhibitions_years = Model::connection()->read("SELECT count(*) as records, YEAR(earliest_date) AS year FROM archives WHERE controller = 'exhibitions' AND YEAR(earliest_date) != '0' GROUP BY year ORDER BY year ASC");

		$publications_years = Model::connection()->read("SELECT count(*) as records, YEAR(earliest_date) AS year FROM archives WHERE controller = 'publications' and YEAR(earliest_date) != '0' GROUP BY year ORDER BY year ASC");

		$publications_languages = Model::connection()->read(
			"select count(*) as records, languages.name as language from archives left join languages on archives.language_code = languages.code where controller = 'publications' and language_code != ''  group by languages.name order by records DESC"
		);

		$architecture = Environment::get('architecture');

		return compact(
			'albums', 
			'albums_works', 
			'works', 
			'architectures', 
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

			'architecture',
			'auth'
		);
	}

	public function usage() {

		$monthly_edits = Model::connection()->read(
			"select count(*) AS records, UNIX_TIMESTAMP(DATE_FORMAT(date_modified, '%Y-%m-01')) * 1000 as milliseconds FROM archives_histories group by milliseconds order by milliseconds ASC"
		);

		$daily_edits = Model::connection()->read(
			"select count(*) AS records, UNIX_TIMESTAMP(DATE(date_modified)) * 1000 as milliseconds FROM archives_histories group by milliseconds order by milliseconds ASC"
		);

		$daily_edits_last_two_months = Model::connection()->read(
			"select count(*) AS records, UNIX_TIMESTAMP(DATE(date_modified)) * 1000 as milliseconds FROM archives_histories WHERE UNIX_TIMESTAMP(DATE(date_modified)) * 1000 > '1364774400000' group by milliseconds order by milliseconds ASC"
		);

		return compact(
			'monthly_edits',
			'daily_edits',
			'daily_edits_last_two_months'
		);
		
	}

}

?>
