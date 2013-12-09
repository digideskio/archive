<?php

namespace app\controllers;

use app\models\Archives;
use app\models\ArchivesHistories;
use app\models\Exhibitions;
use app\models\ExhibitionsHistories;
use app\models\Works;
use app\models\Publications;
use app\models\Components;
use app\models\Links;
use app\models\ArchivesLinks;
use app\models\ArchivesDocuments;
use app\models\Documents;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;
use lithium\core\Environment;
use lithium\data\collection\RecordSet;

use lithium\core\Libraries;

class ExhibitionsController extends \lithium\action\Controller {

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
		'venues' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'view' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Pages::home"),
		),
		'add' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
		'edit' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
		'attachments' => array(
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
		
		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 40;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$order = array('Archives.earliest_date' => 'DESC');
		$total = Exhibitions::count();

		$limit = ($limit == 'all') ? $total : $limit;
		
		$exhibitions = Exhibitions::find('all', array(
			'with' => array('Archives', 'Components', 'ArchivesLinks', 'ArchivesLinks.Links'),
			'order' => $order,
			'limit' => $limit,
			'page' => $page,
		));

		$pdf = "Exhibitions-" . date('Y-m-d') . ".pdf";
		$content = compact('pdf');

		return compact('exhibitions', 'total', 'page', 'limit', 'pdf', 'content');
	}

	public function search() {

		$exhibitions = array();

		$order = array('Archives.earliest_date' => 'DESC');

		$condition = '';
		$query = '';
		$type = 'All';

		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 40;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$total = 0;

		$data = $this->request->data ?: $this->request->query;

		if (isset($data['query']) && $data['query']) {
			$condition = isset($data['condition']) ? $data['condition'] : '';
			$type = isset($data['type']) ? $data['type'] : 'All';

			$query = trim($data['query']);

			if ($condition) {
				$conditions = array("$condition" => array('LIKE' => "%$query%"));
			} else {

				$exhibition_ids = array();

				$fields = array('Archives.name', 'venue', 'curator', 'earliest_date', 'city', 'country', 'remarks');

				foreach ($fields as $field) {
					$matching_exhibits = Exhibitions::find('all', array(
						'with' => 'Archives',
						'fields' => 'Exhibitions.id',
						'conditions' => array("$field" => array('LIKE' => "%$query%")),
					));

					if ($matching_exhibits) {
						$matching_ids = $matching_exhibits->map(function($me) {
							return $me->id;
						}, array('collect' => false));

						$exhibition_ids = array_unique(array_merge($exhibition_ids, $matching_ids));
					}
				}

				$conditions = $exhibition_ids ? array('Exhibitions.id' => $exhibition_ids) : array('Archives.name' => $query);
			}

			if ($type != 'All') {
				$conditions['Archives.type'] = $type;
			}

			$exhibitions = Exhibitions::find('all', array(
				'with' => array('Archives', 'Components'),
				'order' => $order,
				'conditions' => $conditions,
				'limit' => $limit,
				'page' => $page
			));

			$total = Exhibitions::count('all', array(
				'with' => array('Archives'),
				'order' => $order,
				'conditions' => $conditions,
			));

		}

		return compact('exhibitions', 'condition', 'type', 'query', 'total', 'page', 'limit');
	}

	public function histories() {

		$limit = 50;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$order = array('start_date' => 'DESC');
		$total = ExhibitionsHistories::count();
		$archives_histories = ArchivesHistories::find('all', array(
			'with' => array('Users', 'Archives'),
			'conditions' => array('ArchivesHistories.controller' => 'exhibitions'),
			'limit' => $limit,
			'order' => $order,
			'page' => $page
		));
		
		return compact('archives_histories', 'total', 'page', 'limit');
	}

	public function venues() {

		$exhibitions_venues = Exhibitions::find('all', array(
			'fields' => array('venue', 'count(venue) as exhibits'),
			'group' => 'venue',
			'conditions' => array('venue' => array('!=' => '')),
			'order' => array('venue' => 'ASC')
		));

		$venues = $exhibitions_venues->map(function($ev) {
			return array('name' => $ev->venue, 'count' => $ev->exhibits);
		}, array('collect' => false));

		$exhibitions_cities = Exhibitions::find('all', array(
			'fields' => array('city', 'count(city) as cities'),
			'group' => 'city',
			'conditions' => array('city' => array('!=' => '')),
			'order' => array('city' => 'ASC')
		));

		$cities = $exhibitions_cities->map(function($ec) {
			return array('name' => $ec->city, 'count' => $ec->cities);
		}, array('collect' => false));

		$exhibitions_countries = Exhibitions::find('all', array(
			'fields' => array('country', 'count(country) as countries'),
			'group' => 'country',
			'conditions' => array('country' => array('!=' => '')),
			'order' => array('country' => 'ASC')
		));

		$countries = $exhibitions_countries->map(function($ec) {
			return array('name' => $ec->country, 'count' => $ec->countries);
		}, array('collect' => false));

		return compact('venues', 'cities', 'countries');

	}

	public function view() {
	
		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {
		
			//Get single record from the database where the slug matches the URL
			$exhibition = Exhibitions::find('first', array(
				'with' => 'Archives',
				'conditions' => array(
					'Archives.slug' => $this->request->params['slug'],
			)));
		
			$exhibitions_works = Components::find('all', array(
				'fields' => 'archive_id2',
				'conditions' => array(
					'Components.archive_id1' => $exhibition->id,
					'Components.type' => 'exhibitions_works'
				),
			));

			if ($exhibitions_works->count()) {

				//Get all the work IDs in a plain array
				$work_ids = $exhibitions_works->map(function($ew) {
					return $ew->archive_id2;
				}, array('collect' => false));

				$works = Works::find('artworks', array(
					'conditions' => array('Works.id' => $work_ids),
					'order' => 'Archives.earliest_date DESC'
				));

			} else {
				$works = new RecordSet();
			}

			$total = $works ? $works->count() : 0;

			$exhibitions_publications = Components::find('all', array(
				'fields' => 'archive_id2',
				'conditions' => array(
					'archive_id1' => $exhibition->id,
					'type' => 'exhibitions_publications'
				),
			));

			$publications = array();

			if ($exhibitions_publications->count()) {

				//Get all the IDs in a plain array
				$pub_ids = $exhibitions_publications->map(function($ep) {
					return $ep->archive_id2;
				}, array('collect' => false));

				$publications = Publications::find('all', array(
					'with' => 'Archives',
					'conditions' => array('Publications.id' => $pub_ids),
					'order' => 'earliest_date DESC'
				));

			}

			$archives_links = ArchivesLinks::find('all', array(
				'with' => 'Links',
				'conditions' => array('ArchivesLinks.archive_id' => $exhibition->id),
				'order' => array('Links.date_modified' =>  'DESC')
			));

			$archives_documents = ArchivesDocuments::find('all', array(
				'with' => array(
					'Documents',
					'Documents.Formats'
				),
				'conditions' => array('ArchivesDocuments.archive_id' => $exhibition->id),
				'order' => array('Documents.slug' => 'ASC')
			));
			
			//Send the retrieved data to the view
			return compact(
				'exhibition',
				'works',
				'total',
				'publications',
				'archives_documents',
				'archives_links'
			);
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Exhibitions::index'));
	}

	public function add() {
        
		$archive = Archives::create();
		$exhibition = Exhibitions::create();
		$link = Links::create();
		$documents = array();
		$archives = array();

		if ($this->request->data) {

			if (isset($this->request->data['archives'])) {
				$archive_ids = $this->request->data['archives'];

				$archives = Archives::find('all', array(
					'conditions' => array('Archives.id' => $archive_ids),
					'order' => array('earliest_date' => 'DESC')
				));
			}

			if (isset($this->request->data['documents'])) {
				$document_ids = $this->request->data['documents'];

				$documents = Documents::find('all', array(
					'with' => 'Formats',
					'conditions' => array('Documents.id' => $document_ids),
				));
			}

			if (isset($this->request->data['archive'])) {
				$archive_data = $this->request->data['archive'];
				$archive_data['controller'] = 'exhibitions';

				// Pass in any venue, to be used for the slug
				if (isset($this->request->data['exhibition'])) {
					$exhibit_data = $this->request->data['exhibition'];
					if (isset($exhibit_data['venue'])) {
						$archive_data['venue'] = $exhibit_data['venue'];
					}
				}

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

					// Save a exhibition along with this archive
					$exhibit_data = isset($this->request->data['exhibition']) ? $this->request->data['exhibition'] : array();
					$exhibit_data['id'] = $archive->id;

					$exhibition = Exhibitions::create();
					$exhibition->save($exhibit_data);

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

					// If any archive ids were submitted, save them as Components
					foreach ($archives as $a) {
						$archive_id1 = $archive->id;
						$archive_id2 = $a->id;

						$type = '';

						switch ($a->controller) {
							case 'works':
								$type = 'exhibitions_works';
								break;
							case 'publications':
								$type = 'exhibitions_publications';
								break;
						}

						$component = Components::create();
						$component->save(compact('archive_id1', 'archive_id2', 'type'));
					}

					// If any documents were submitted, save them as ArchivesDocuments
					$archive_id = $archive->id;
					foreach ($documents as $doc) {
						$document_id = $doc->id;
						$ad = ArchivesDocuments::create();
						$ad->save(compact('archive_id', 'document_id'));
					}

					return $this->redirect(array('Exhibitions::view', 'slug' => $archive->slug));
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

		$exhibition_titles = Archives::find('all', array(
			'fields' => 'name',
			'group' => 'name',
			'conditions' => array(
				'name' => array('!=' => ''),
				'controller' => 'exhibitions',
			),
			'order' => array('name' => 'ASC'),
		));

		$titles = $exhibition_titles->map(function($tit) {
			return $tit->name;
		}, array('collect' => false));

		$exhibition_venues = Exhibitions::find('all', array(
			'fields' => 'venue',
			'group' => 'venue',
			'conditions' => array('venue' => array('!=' => '')),
			'order' => array('venue' => 'ASC'),
		));

		$venues = $exhibition_venues->map(function($ven) {
			return $ven->venue;
		}, array('collect' => false));

		$exhibition_cities = Exhibitions::find('all', array(
			'fields' => 'city',
			'group' => 'city',
			'conditions' => array('city' => array('!=' => '')),
			'order' => array('city' => 'ASC'),
		));

		$cities = $exhibition_cities->map(function($cit) {
			return $cit->city;
		}, array('collect' => false));

		$exhibition_countries = Exhibitions::find('all', array(
			'fields' => 'country',
			'group' => 'country',
			'conditions' => array('country' => array('!=' => '')),
			'order' => array('country' => 'ASC'),
		));

		$countries = $exhibition_countries->map(function($con) {
			return $con->country;
		}, array('collect' => false));

		return compact(
			'archive',
			'exhibition',
			'link',
			'archives',
			'documents',
			'titles',
			'venues',
			'cities',
			'countries'
		);
	}

	public function edit() {
		
		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {

			$exhibition = Exhibitions::first(array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));

			if (empty($exhibition)) {
				return $this->redirect('Exhibitions::index');
			}

			$archive = $exhibition->archive;

			if ($this->request->data) {

				if (isset($this->request->data['archive'])) {

					$archive_data = $this->request->data['archive'];

					if ($archive->save($archive_data)) {

						$exhibit_data = isset($this->request->data['exhibition']) ? $this->request->data['exhibition'] : array();
						$exhibition->save($exhibit_data);

						return $this->redirect(array('Exhibitions::view', 'slug' => $archive->slug));
					}
				}
			}

			$archives_documents = ArchivesDocuments::find('all', array(
				'with' => array(
					'Documents',
					'Documents.Formats'
				),
				'conditions' => array('ArchivesDocuments.archive_id' => $exhibition->id),
				'order' => array('Documents.slug' => 'ASC')
			));

			$exhibition_titles = Archives::find('all', array(
				'fields' => 'name',
				'group' => 'name',
				'conditions' => array(
					'name' => array('!=' => ''),
					'controller' => 'exhibitions',
				),
				'order' => array('name' => 'ASC'),
			));

			$titles = $exhibition_titles->map(function($tit) {
				return $tit->name;
			}, array('collect' => false));

			$exhibition_venues = Exhibitions::find('all', array(
				'fields' => 'venue',
				'group' => 'venue',
				'conditions' => array('venue' => array('!=' => '')),
				'order' => array('venue' => 'ASC'),
			));

			$venues = $exhibition_venues->map(function($ven) {
				return $ven->venue;
			}, array('collect' => false));

			$exhibition_cities = Exhibitions::find('all', array(
				'fields' => 'city',
				'group' => 'city',
				'conditions' => array('city' => array('!=' => '')),
				'order' => array('city' => 'ASC'),
			));

			$cities = $exhibition_cities->map(function($cit) {
				return $cit->city;
			}, array('collect' => false));

			$exhibition_countries = Exhibitions::find('all', array(
				'fields' => 'country',
				'group' => 'country',
				'conditions' => array('country' => array('!=' => '')),
				'order' => array('country' => 'ASC'),
			));

			$countries = $exhibition_countries->map(function($con) {
				return $con->country;
			}, array('collect' => false));

			return compact(
				'archive',
				'exhibition',
				'archives_documents',
				'titles',
				'venues',
				'cities',
				'countries'
			);
		}

		//since no record was specified, redirect to the index page
		$this->redirect(array('Exhibitions::index'));
	}

	public function attachments() {

		$exhibition = Exhibitions::find('first', array(
			'with' => 'Archives',
			'conditions' => array(
				'Archives.slug' => $this->request->params['slug'],
		)));

		if (!$exhibition) {
			return $this->redirect('Exhibitions::index');
		}

		$archives_links = ArchivesLinks::find('all', array(
			'with' => 'Links',
			'conditions' => array('ArchivesLinks.archive_id' => $exhibition->id),
			'order' => array('Links.date_modified' =>  'DESC')
		));

		$archives_documents = ArchivesDocuments::find('all', array(
			'with' => array(
				'Documents',
				'Documents.Formats'
			),
			'conditions' => array('ArchivesDocuments.archive_id' => $exhibition->id),
			'order' => array('slug' => 'ASC')
		));

		return compact('exhibition', 'archives_links', 'archives_documents');

	}

	public function history() {
	
		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {
		
			//Get single record from the database where the slug matches the URL
			$exhibition = Exhibitions::first(array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));
			
			if($exhibition) {

				$archives_histories = ArchivesHistories::find('all', array(
					'conditions' => array('ArchivesHistories.archive_id' => $exhibition->id),
					'order' => 'ArchivesHistories.start_date DESC',
					'with' => array('Users', 'ExhibitionsHistories'),
				));

				//FIXME We can't actually guarantee that the start_date can be used as a foreign key for the histories,
				//so for now let's grab the subclass history table, then iterate through it as well
				$exhibitions_histories = ExhibitionsHistories::find('all', array(
					'conditions' => array('exhibition_id' => $exhibition->id),
					'order' => array('start_date' => 'DESC')
				));
		
		
				//Send the retrieved data to the view
				return compact('exhibition', 'archives_histories', 'exhibitions_histories');
			}
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Exhibitions::index'));
	}

	public function delete() {
		
		$exhibition = Exhibitions::find('first', array(
			'with' => 'Archives',
			'conditions' => array(
			'Archives.slug' => $this->request->params['slug'],
		)));
        
        // For the following to work, the delete form must have an explicit 'method' => 'post'
        // since the default method is PUT
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Exhibitions::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		$exhibition->delete();
		return $this->redirect('Exhibitions::index');
	}
}

?>
