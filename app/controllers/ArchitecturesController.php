<?php

namespace app\controllers;

use app\models\Architectures;
use app\models\ArchitecturesHistories;

use app\models\Users;
use app\models\Roles;
use app\models\Documents;
use app\models\ArchivesDocuments;

use app\models\Archives;
use app\models\ArchivesHistories;

use lithium\action\DispatchException;
use lithium\security\Auth;
use lithium\core\Environment;

class ArchitecturesController extends \lithium\action\Controller {

	public $rules = array(
		'index' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'search' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'histories' => array(
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
		'history' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'delete' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
	);

	public function index() {

		if (!Environment::get('architecture')) {
			return $this->redirect('Pages::home');
		}

		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 40;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$order = array('earliest_date' => 'DESC');
		$total = Architectures::count();

		$limit = ($limit == 'all') ? $total : $limit;

		$architectures = Architectures::find('all', array(
			'with' => 'Archives',
			'order' => array('Archives.earliest_date' => 'DESC'),
			'limit' => $limit,
			'page' => $page
		));

		return compact('architectures', 'total', 'page', 'limit');
	}

	public function histories() {

		$limit = 50;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$order = array('start_date' => 'DESC');
		$total = ArchitecturesHistories::count();
		$archives_histories = ArchivesHistories::find('all', array(
			'with' => array('Users', 'Archives'),
			'conditions' => array('ArchivesHistories.controller' => 'architectures'),
			'limit' => $limit,
			'order' => $order,
			'page' => $page
		));

		return compact('archives_histories', 'total', 'page', 'limit');
	}

	public function search() {

		$architectures = array();

		$order = array('earliest_date' => 'DESC');

		$query = '';
		$condition = '';

		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 40;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$total = 0;

		$data = $this->request->data ?: $this->request->query;

		if (isset($data['query']) && $data['query']) {
			$condition = isset($data['condition']) ? $data['condition'] : '';

			$query = $data['query'];

			if ($condition) {
				$conditions = array("$condition" => array('LIKE' => "%$query%"));
			} else {

				$architecture_ids = array();

				$fields = array('Archives.name', 'architect', 'client', 'project_lead', 'earliest_date', 'status', 'location', 'city', 'country', 'remarks');

				foreach ($fields as $field) {
					$matching_works = Architectures::find('all', array(
						'with' => 'Archives',
						'fields' => 'Architectures.id',
						'conditions' => array($field => array('LIKE' => "%$query%")),
					));

					if ($matching_works) {
						$matching_ids = $matching_works->map(function($mw) {
							return $mw->id;
						}, array('collect' => false));

						$architecture_ids = array_unique(array_merge($architecture_ids, $matching_ids));
					}
				}

				$conditions = $architecture_ids ?  array('Architectures.id' => $architecture_ids) : array('Archives.name' => $query);

			}

			$architectures = Architectures::find('all', array(
				'with' => 'Archives',
				'order' => $order,
				'conditions' => $conditions,
				'limit' => $limit,
				'page' => $page
			));

			$total = Architectures::count('all', array(
				'with' => 'Archives',
				'order' => $order,
				'conditions' => $conditions,
			));

		}
		return compact('architectures', 'condition', 'query', 'total', 'page', 'limit');
	}

	public function view() {

		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {

			//Get single record from the database where the slug matches the URL
			$architecture = Architectures::first(array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));

			if(!$architecture) {
				$this->redirect(array('Architectures::index'));
			} else {

				$archives_documents = ArchivesDocuments::find('all', array(
					'with' => array(
						'Documents',
						'Documents.Formats'
					),
					'conditions' => array('ArchivesDocuments.archive_id' => $architecture->id),
					'order' => array('Documents.slug' => 'ASC')
				));

				//Send the retrieved data to the view
				return compact('architecture', 'archives_documents');

			}
		}

		//since no record was specified, redirect to the index page
		$this->redirect(array('Architectures::index'));
	}

	public function add() {

		$archive = Archives::create();
		$architecture = Architectures::create();

		if ($this->request->data) {

			if (isset($this->request->data['archive'])) {
				$archive_data = $this->request->data['archive'];
				$archive_data['controller'] = 'architectures';
				if ($archive->save($archive_data)) {

					// Save an architecture along with this archive
					$architecture_data = isset($this->request->data['architecture']) ? $this->request->data['architecture'] : array();
					$architecture_data['id'] = $archive->id;

					$architecture = Architectures::create();
					$architecture->save($architecture_data);

					return $this->redirect(array('Architectures::view', 'slug' => $archive->slug));
				}

			}

		} else {
			// Check if any defaults are set
			$archives_config = Environment::get('archives');

			if ($archives_config && isset($archives_config['default'])) {
				$archives_default = $archives_config['default'];

				if (isset($archives_default['published'])) {
					$archive->published = $archives_default['published'];
				}
			}
		}

		return compact('archive', 'architecture');
	}

	public function edit() {

		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {

			$architecture = Architectures::first(array(
				'with' => 'Archives',
				'conditions' => array(
					'Archives.slug' => $this->request->params['slug'],
				)
			));

			if (!$architecture) {
				return $this->redirect('Architectures::index');
			}

			$archive = $architecture->archive;

			if ($this->request->data) {

				if (isset($this->request->data['archive'])) {

					$archive_data = $this->request->data['archive'];

					if ($archive->save($archive_data)) {

						$architecture_data = isset($this->request->data['architecture']) ? $this->request->data['architecture'] : array();
						$architecture->save($architecture_data);

						return $this->redirect(array('Architectures::view', 'slug' => $architecture->archive->slug));
					}
				}
			}

			$archives_documents = ArchivesDocuments::find('all', array(
				'with' => array(
					'Documents',
					'Documents.Formats'
				),
				'conditions' => array('archive_id' => $architecture->id),
				'order' => array('Documents.slug' => 'ASC')
			));

			return compact('archive', 'architecture', 'archives_documents');
		}

		//since no record was specified, redirect to the index page
		$this->redirect(array('Architectures::index'));
	}

	public function history() {

		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {

			//Get single record from the database where the slug matches the URL
			$architecture = Architectures::first(array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));

			if($architecture) {

				$archives_histories = ArchivesHistories::find('all', array(
					'conditions' => array('ArchivesHistories.archive_id' => $architecture->id),
					'order' => 'ArchivesHistories.start_date DESC',
					'with' => array('Users', 'ArchitecturesHistories'),
				));

				//FIXME We can't actually guarantee that the start_date can be used as a foreign key for the histories,
				//so for now let's grab the subclass history table, then iterate through it as well
				$architectures_histories = ArchitecturesHistories::find('all', array(
					'conditions' => array('architecture_id' => $architecture->id),
					'order' => array('start_date' => 'DESC')
				));

				//Send the retrieved data to the view
				return compact('architecture', 'archives_histories', 'architectures_histories');
			}
		}

		//since no record was specified, redirect to the index page
		$this->redirect(array('Architectures::index'));

	}

	public function delete() {

		$architecture = Architectures::first(array(
			'with' => 'Archives',
			'conditions' => array('Archives.slug' => $this->request->params['slug']),
		));

        // For the following to work, the delete form must have an explicit 'method' => 'post'
        // since the default method is PUT
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Architectures::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}

		$architecture->delete();
		return $this->redirect('Architectures::index');
	}
}

?>
