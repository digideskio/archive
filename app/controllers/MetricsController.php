<?php

namespace app\controllers;

use app\models\Archives;
use app\models\Albums;
use app\models\Works;
use app\models\Architectures;
use app\models\Exhibitions;
use app\models\Publications;
use app\models\Documents;
use app\models\Components;
use app\models\ArchivesHistories;
use app\models\ArchivesDocuments;
use app\models\Requests;
use app\models\Notices;

use app\models\Users;
use app\models\Roles;

use lithium\data\Model;

use lithium\security\Auth;
use lithium\action\DispatchException;
use lithium\core\Environment;

use lithium\core\Libraries;
use lithium\util\Inflector;

class MetricsController extends \lithium\action\Controller {

	public $rules = array(
		'index' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'usage' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'report' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
	);

	public function index() {

		$albums = Albums::count();
		$albums_works = Components::find('count', array(
			'conditions' => array('type' => 'albums_works')
		));
		$works = Works::count();
		$architectures = Architectures::count();
		$exhibitions = Exhibitions::count();
		$solo_shows = Exhibitions::count('all', array(
			'with' => 'Archives',
			'conditions' => array('Archives.type' => 'Solo')
		));
		$group_shows = Exhibitions::count('all', array(
			'with' => 'Archives',
			'conditions' => array('Archives.type' => 'Group')
		));
		$exhibitions_works = Components::find('count', array(
			'conditions' => array('type' => 'exhibitions_works')
		));
		$documents = Documents::count();
		$publications = Publications::count();
		$publications_archives_documents = ArchivesDocuments::connection()->read("SELECT count(*) as records FROM archives_documents WHERE archive_id IN (SELECT id FROM publications)");
		$pad = $publications_archives_documents[0];
		$publications_documents = $pad['records'];

		$no_date = array('earliest_date' => '0000-00-00');

		$works_years = Archives::connection()->read("SELECT count(*) as records, YEAR(earliest_date) AS year FROM archives WHERE controller = 'works' and YEAR(earliest_date) != '0' GROUP BY year ORDER BY year ASC");

		$architectures_years = Archives::connection()->read("SELECT count(*) as records, YEAR(earliest_date) AS year FROM archives WHERE controller = 'architectures' and  YEAR(earliest_date) != '0' GROUP BY year ORDER BY year ASC");

		$exhibitions_years = Archives::connection()->read("SELECT count(*) as records, YEAR(earliest_date) AS year FROM archives WHERE controller = 'exhibitions' AND YEAR(earliest_date) != '0' GROUP BY year ORDER BY year ASC");

		$publications_years = Archives::connection()->read("SELECT count(*) as records, YEAR(earliest_date) AS year FROM archives WHERE controller = 'publications' and YEAR(earliest_date) != '0' GROUP BY year ORDER BY year ASC");

		$publications_languages = Archives::connection()->read(
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

			'architecture'
		);
	}

	public function usage() {
	    $check = (Auth::check('default')) ?: null;
	
        // Look up the current user with his or her role
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));

		if($auth->timezone_id) {
			$tz = new \DateTimeZone($auth->timezone_id);
		}

		$daily_views = Requests::connection()->read(
			"SELECT count(*) AS records, (request_time - MOD(request_time, 86400)) * 1000 AS milliseconds FROM requests WHERE controller != 'Files' AND url != 'login' GROUP BY milliseconds ORDER BY milliseconds ASC"
		);

		$daily_views_last_three_months = Requests::connection()->read(
			"SELECT count(*) AS records, (request_time - MOD(request_time, 86400)) * 1000 AS milliseconds FROM requests WHERE (request_time > (UNIX_TIMESTAMP() - 7257600)) AND controller != 'Files' AND url != 'login' GROUP BY milliseconds ORDER BY milliseconds ASC"
		);

		$monthly_edits = ArchivesHistories::connection()->read(
			"select count(*) AS records, UNIX_TIMESTAMP(DATE_FORMAT(date_modified, '%Y-%m-01')) * 1000 as milliseconds FROM archives_histories group by milliseconds order by milliseconds ASC"
		);

		$daily_edits = ArchivesHistories::connection()->read(
			"select count(*) AS records, UNIX_TIMESTAMP(DATE(date_modified)) * 1000 as milliseconds FROM archives_histories group by milliseconds order by milliseconds ASC"
		);

		$daily_edits_last_three_months = ArchivesHistories::connection()->read(
			"select count(*) AS records, UNIX_TIMESTAMP(DATE(date_modified)) * 1000 as milliseconds FROM archives_histories WHERE UNIX_TIMESTAMP(DATE(date_modified)) * 1000 > (UNIX_TIMESTAMP() * 1000 - 8035200000) group by milliseconds order by milliseconds ASC"
		);

		$archives_histories_count = ArchivesHistories::count();

		$daily_creates = Archives::connection()->read(
			"select count(*) AS records, UNIX_TIMESTAMP(DATE(date_created)) * 1000 as milliseconds FROM archives group by milliseconds order by milliseconds ASC"
		);

		$earliest_record = ArchivesHistories::connection()->read(
			"select date_modified from archives_histories order by date_modified ASC limit 1"	
		);

		$all_time_date = new \DateTime($earliest_record[0]['date_modified']);
		$today = new \DateTime();
		$interval = $today->diff($all_time_date);
		$total_days = $interval->days;

		$today = new \DateTime();
		$month_date = $today->sub(new \DateInterval('P30D'));
		$today = new \DateTime();
		$month_date_interval = $today->diff($month_date);
		$month_days = $month_date_interval->days;

		$month_date = $total_days > $month_days ? $month_date : $all_time_date;

		$month_date_interval = $today->diff($month_date);
		$month_days = $month_date_interval->days;

		$week_date = $today->sub(new \DateInterval('P7D'));
		$today = new \DateTime();
		$week_date_interval = $today->diff($week_date);
		$week_days = $week_date_interval->days;

		$week_date = $total_days > $week_days ? $week_date : $all_time_date;

		$week_date_interval = $today->diff($week_date);
		$week_days = $week_date_interval->days;

		$now_date = new \DateTime();

		if (isset($tz)) {
			$now_date->setTimeZone($tz);
			$all_time_date->setTimeZone($tz);
			$month_date->setTimeZone($tz);
			$week_date->setTimeZone($tz);
		};

		$dates = array(
			'now' => $now_date->format('d M Y'),
			'all_time' => $all_time_date->format('d M Y'),
			'month' => $month_date->format('d M Y'),
			'week' => $week_date->format('d M Y')
		);

		$intervals = array(
			'all_time' => $total_days,
			'month' => $month_date_interval->days,
			'week' => $week_date_interval->days
		);

		$contributors_total = Archives::connection()->read(
			"SELECT COUNT(DISTINCT user_id) as records FROM archives WHERE user_id IS NOT NULL AND user_id != '0'"
		);

		$contributors_month = Archives::connection()->read(
			"SELECT COUNT(DISTINCT user_id) as records FROM archives WHERE user_id IS NOT NULL AND user_id != '0' AND UNIX_TIMESTAMP(date_modified) > (UNIX_TIMESTAMP() - 2419200)"
		);

		$contributors_week = Archives::connection()->read(
			"SELECT COUNT(DISTINCT user_id) as records FROM archives WHERE user_id IS NOT NULL AND user_id != '0' AND UNIX_TIMESTAMP(date_modified) > (UNIX_TIMESTAMP() - 604800)"
		);

		$contributors = array(
			'total' => $contributors_total[0]['records'],
			'month' => $contributors_month[0]['records'],
			'week' => $contributors_week[0]['records']
		);

		$contributions_total = Archives::connection()->read(
			"SELECT username, users.name as name, count(*) as records FROM archives LEFT JOIN users ON archives.user_id = users.id WHERE user_id IS NOT NULL AND user_id != '0' GROUP BY user_id ORDER BY records DESC"
		);

		$contributions_month = Archives::connection()->read(
			"SELECT username, users.name as name, count(*) as records FROM archives LEFT JOIN users ON archives.user_id = users.id WHERE user_id IS NOT NULL AND user_id != '0' AND UNIX_TIMESTAMP(date_modified) > (UNIX_TIMESTAMP() - 2419200) GROUP BY user_id ORDER BY records DESC"
		);

		$contributions_week = Archives::connection()->read(
			"SELECT username, users.name as name, count(*) as records FROM archives LEFT JOIN users ON archives.user_id = users.id WHERE user_id IS NOT NULL AND user_id != '0' AND UNIX_TIMESTAMP(date_modified) > (UNIX_TIMESTAMP() - 604800) GROUP BY user_id ORDER BY records DESC"
		);

		$contributions = array(
			'total' => $contributions_total,
			'month' => $contributions_month,
			'week' => $contributions_week
		);

		$artworks_total = Archives::connection()->read(
			"SELECT count(id) as records FROM archives WHERE controller='works'"
		);

		$artworks_month = Archives::connection()->read(
			"SELECT count(id) as records FROM archives WHERE controller='works' AND UNIX_TIMESTAMP(date_modified) > (UNIX_TIMESTAMP() - 2419200)"
		);

		$artworks_week = Archives::connection()->read(
			"SELECT count(id) as records FROM archives WHERE controller='works' AND UNIX_TIMESTAMP(date_modified) > (UNIX_TIMESTAMP() - 604800)"
		);

		$works = array(
			'total' => $artworks_total[0]['records'],
			'month' => $artworks_month[0]['records'],
			'week' => $artworks_week[0]['records']
		);

		$exhibitions_total = Archives::connection()->read(
			"SELECT count(id) as records FROM archives WHERE controller='exhibitions'"
		);

		$exhibitions_month = Archives::connection()->read(
			"SELECT count(id) as records FROM archives WHERE controller='exhibitions' AND UNIX_TIMESTAMP(date_modified) > (UNIX_TIMESTAMP() - 2419200)"
		);

		$exhibitions_week = Archives::connection()->read(
			"SELECT count(id) as records FROM archives WHERE controller='exhibitions' AND UNIX_TIMESTAMP(date_modified) > (UNIX_TIMESTAMP() - 604800)"
		);

		$exhibitions = array(
			'total' => $exhibitions_total[0]['records'],
			'month' => $exhibitions_month[0]['records'],
			'week' => $exhibitions_week[0]['records']
		);

		$publications_total = Archives::connection()->read(
			"SELECT count(id) as records FROM archives WHERE controller='publications'"
		);

		$publications_month = Archives::connection()->read(
			"SELECT count(id) as records FROM archives WHERE controller='publications' AND UNIX_TIMESTAMP(date_modified) > (UNIX_TIMESTAMP() - 2419200)"
		);

		$publications_week = Archives::connection()->read(
			"SELECT count(id) as records FROM archives WHERE controller='publications' AND UNIX_TIMESTAMP(date_modified) > (UNIX_TIMESTAMP() - 604800)"
		);

		$publications = array(
			'total' => $publications_total[0]['records'],
			'month' => $publications_month[0]['records'],
			'week' => $publications_week[0]['records']
		);

		$documents_total = Documents::connection()->read(
			"SELECT count(id) as records FROM documents" 
		);

		$documents_month = Documents::connection()->read(
			"SELECT count(id) as records FROM documents WHERE UNIX_TIMESTAMP(date_modified) > (UNIX_TIMESTAMP() - 2419200)"
		);

		$documents_week = Documents::connection()->read(
			"SELECT count(id) as records FROM documents WHERE UNIX_TIMESTAMP(date_modified) > (UNIX_TIMESTAMP() - 604800)"
		);

		$documents = array(
			'total' => $documents_total[0]['records'],
			'month' => $documents_month[0]['records'],
			'week' => $documents_week[0]['records']
		);

		$host = Inflector::humanize($this->request->env('HTTP_HOST'));
		$title = "$host Metrics: " . $dates['now'];
		$filename = Inflector::slug($host) . '-Metrics-' . $now_date->format('Y-m-d') . '.pdf';

		return compact(
			'dates',
			'intervals',
			'daily_views',
			'daily_views_last_three_months',
			'monthly_edits',
			'daily_edits',
			'daily_edits_last_three_months',
			'archives_histories_count',
			'daily_creates',
			'total_days',
			'works',
			'exhibitions',
			'publications',
			'documents',
			'contributors',
			'contributions',
			'filename',
			'title'
		);
		
	}

	public function report() {
		define("DEFAULT_REPORTING_PERIOD", 30); // 30 days
		$period = DEFAULT_REPORTING_PERIOD;

		if(isset($this->request->query['period'])) {
			$period = (int) $this->request->query['period'];
		}

	    $check = (Auth::check('default')) ?: null;
	
        // Look up the current user with his or her role
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));

		if($auth->timezone_id) {
			date_default_timezone_set($auth->timezone_id);
		}

		$earliest_record = Archives::connection()->read(
			"select date_modified from archives order by date_modified ASC limit 1"
		);

		$all_time_date = new \DateTime($earliest_record[0]['date_modified']);
		$today = new \DateTime();
		$interval = $today->diff($all_time_date);
		$total_days = $interval->days;

		$interval_spec = 'P' . $period . 'D';

		$start_date = new \DateTime();
		$today = new \DateTime();
		$start_date = $today->sub(new \DateInterval($interval_spec));
		$today = new \DateTime();
		$start_date_interval = $today->diff($start_date);
		$start_days = $start_date_interval->days;

		$start_date = $total_days > $start_days ? $start_date : $all_time_date;

		$end_date = new \DateTime();

		$dates = array(
			'start' => $start_date->format('d M Y'),
			'end' => $end_date->format('d M Y'),
		);

		$updates = Notices::find('all', array(
			'order' => array('date_created' => 'DESC'),
			'conditions' => array('date_created' => array('>' => $dates['start']))
		));

		$archives = Archives::find('all', array(
			'with' => array('Users'),
			'conditions' => array('date_modified' => array('>' => $dates['start'])),
			'order' => array(
				'controller' => 'ASC',
				'name' => 'ASC',
			)
		));

		$host = Inflector::humanize($this->request->env('HTTP_HOST'));
		$title = "$host Progress Report: " . $dates['start'] . ' - ' . $dates['end'];
		$filename = Inflector::slug($host) . '-Updates-' . $end_date->format('Y-m-d') . '.pdf';

		return compact(
			'dates',
			'updates',
			'archives',
			'filename',
			'title'
		);

	}

}

?>
