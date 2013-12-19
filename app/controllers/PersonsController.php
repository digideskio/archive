<?php

namespace app\controllers;

use app\models\Persons;
use app\models\Archives;
use app\models\Links;
use app\models\Components;
use app\models\Works;

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
	
		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {
		
			//Get single record from the database where the slug matches the URL
			$person = Persons::first(array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));

			$total = $works ? $works->count() : 0;
			
			if (!$person) {
				$this->redirect(array('Persons::index'));
			} else {

				$total = Components::count('all', array(
					'conditions' => array(
						'archive_id1' => $person->id,
						'type' => 'persons_works'
					)
				));

				$persons_works = Components::find('all', array(
					'fields' => 'archive_id2',
					'conditions' => array(
						'archive_id1' => $person->id,
						'type' => 'persons_works'
					)
				));

				$works = array();

				$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 40;
				$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;

				if ($persons_works->count()) {

					//Get all the work IDs in a plain array
					$work_ids = $persons_works->map(function($pw) {
						return $pw->archive_id2;
					}, array('collect' => false));

					$works = Works::find('all', array(
						'with' => array('Archives', 'Components.Persons.Archives'),
						'conditions' => array('Works.id' => $work_ids),
						'order' => 'Archives.earliest_date DESC',
						'limit' => $limit,
						'page' => $page
					));

				}
			
				//Send the retrieved data to the view
				return compact('person', 'works', 'total', 'page', 'limit');
			
			}
		}
		
		//since no record was specified, redirect to the index page
		//$this->redirect(array('Persons::index'));
	}

	public function edit() {
		
		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {
		
			$person = Persons::first(array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));
		
			if (empty($person)) {
				return $this->redirect('Persons::index');
			}

			$archive = $person->archive;

			if ($this->request->data) {

				if (isset($this->request->data['archive'])) {

					$archive_data = $this->request->data['archive'];

					if ($archive->save($archive_data)) {

						$person_data = isset($this->request->data['person']) ? $this->request->data['person'] : array();
						$person->save($person_data);

						return $this->redirect(array('Persons::view', 'slug' => $archive->slug));
					}
				}
			}

			$classifications = array('Artist');

			return compact(
				'archive',
				'person',
				'classifications'
			);
		}

		$this->redirect(array('Persons::index'));
		
	}

	public function delete() {

		return $this->redirect('Persons::index');
	}

}

?>
