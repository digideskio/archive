<?php

namespace app\controllers;

use app\models\Persons;
use app\models\Archives;
use app\models\Links;

use lithium\core\Environment;

class PersonsController extends \lithium\action\Controller {

	public $rules = array(
		'index' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'view' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'add' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
		'edit' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
		'delete' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
	);

	public function index() {

		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 40;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$total = Persons::count();

		$limit = ($limit == 'all') ? $total : $limit;
		
		$persons = Persons::find('all', array(
			'with' => 'Archives',
			'order' => array('Archives.name' => 'ASC'),
			'limit' => $limit,
			'page' => $page
		));

		return compact('persons', 'total', 'page', 'limit');

	}

	public function add() {
		
		$archive = Archives::create();
		$person = Persons::create();
		$link = Links::create();

		$classifications = array('Artist');
		
		if ($this->request->data) {

			if (isset($this->request->data['archive'])) {
				$archive_data = $this->request->data['archive'];
				$archive_data['controller'] = 'artists';
				$archive = Archives::create($archive_data);

				// Check if a URL for a Link was submitted. The link "validates" if the URL is
				// valid, or blank
				$link_data = array();
				$link_validates = true;
				if (isset($this->request->data['link'])) {
					$link_data = $this->request->data['link'];
					if (!empty($link_data['url'])) {
						$link = Links::create($link_data);
						$link_validates = $link->validates();
					}
				}

				if ($archive->validates() && $link_validates) {

					$archive = Archives::create();
					$archive->save($archive_data);

					// Save a person along with this archive
					$person_data = isset($this->request->data['person']) ? $this->request->data['person'] : array();
					$person_data['id'] = $archive->id;

					$person = Persons::create();
					$person->save($person_data);

					// If Link data was supplied, save Link and ArchivesLinks objects
					if (!empty($link_data) && !empty($link_data['url'])) {
						$link_data['title'] = $archive->name;
						$link = Links::create();
						$link->save($link_data);

						$archives_link = ArchivesLinks::create();
						$archives_link->save(array(
							'archive_id' => $archive->id,
							'link_id' => $link->id
						));
					}

					return $this->redirect(array('Persons::view', 'slug' => $archive->slug));
				}
			}
		} else {
			// Set defaults
			$archive->classification = 'Artist';

			$archives_config = Environment::get('archives');

			if ($archives_config && isset($archives_config['default'])) {
				$archives_default = $archives_config['default'];

				if (isset($archives_default['published'])) {
					$archive->published = $archives_default['published'];
				}
			}
		}

		return compact(
			'archive',
			'person',
			'link',
			'classifications'
		);

	}

	public function view() {

	}

	public function edit() {

	}

	public function delete() {

		return $this->redirect('Persons::index');
	}

}

?>
