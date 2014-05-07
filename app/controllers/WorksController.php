<?php

namespace app\controllers;

use app\models\Works;
use app\models\WorksHistories;
use app\models\Archives;
use app\models\ArchivesHistories;

use app\models\Users;
use app\models\Roles;
use app\models\Persons;
use app\models\Documents;
use app\models\ArchivesDocuments;
use app\models\Albums;
use app\models\Exhibitions;
use app\models\Components;
use app\models\Links;
use app\models\ArchivesLinks;

use li3_filesystem\extensions\storage\FileSystem;

use lithium\action\DispatchException;
use lithium\security\Auth;
use lithium\core\Environment;
use lithium\data\collection\RecordSet;
use lithium\template\View;
use lithium\util\Inflector;
use lithium\net\http\Router;

use li3_access\security\Access;

class WorksController extends \lithium\action\Controller {

	public $rules = array(
		'index' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'search' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'classifications' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'locations' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Works::index"),
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
		'attachments' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
		'history' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'publish' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'delete' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
	);

	public function index() {

		$filter = '';

		if (Environment::get('artworks')) {
			$artworks = Environment::get('artworks');
			$filter = isset($artworks['filter']) ? $artworks['filter'] : '';
		}

		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 40;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;

		$action = isset($this->request->query['action']) ? $this->request->query['action'] : '';

		$total = Works::find('count', array(
			'conditions' => $filter,
		));

		//Interpret any non-integer limit to mean 'All' results
		$limit = !(intval($limit)) ? $total : $limit;

		$works = Works::find('artworks', array(
			'limit' => $limit,
			'conditions' => $filter,
			'page' => $page
		));

		return compact('works', 'total', 'page', 'limit', 'action');
	}

	public function search() {

		$works = array();

		$query = '';
		$condition = '';

		$limit = 40;

		if (Environment::get('artworks')) {
			$artworks = Environment::get('artworks');

			if (isset($artworks['search'])) {
				$search = $artworks['search'];
				$limit = isset($search['limit']) ? $search['limit'] : $limit;
			}
		}

		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : $limit;

		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$total = 0;

		$data = $this->request->data ?: $this->request->query;

		if (isset($data['query']) && $data['query']) {
			$condition = isset($data['condition']) ? $data['condition'] : '';

			$query = $data['query'];

			// If there is only one condition, and we are not searching for an aritst
			// then simply pass on the single condition
			if ($condition && $condition != 'artist') {
				$conditions = array($condition => array('LIKE' => "%$query%"));
			} else {

				$artwork_ids = array();

				$name_fields = array('Archives.name', 'Archives.native_name');

				foreach ($name_fields as $field) {

					// Find artists whose name matches the query
					$artists = Persons::find('all', array(
						'with' => array('Archives', 'Components'),
						'fields' => array(
							'Components.id',
							'Components.archive_id2',
						),
						'conditions' => array(
							$field => array('LIKE' => "%$query%"),
							'Components.type' => 'persons_works',
							'Components.role' => 'Artist'
						)
					));

					// Gather the IDs of their artworks
					foreach ($artists as $artist) {
						foreach ($artist->components as $c) {
							array_push($artwork_ids, $c->archive_id2);
						}
					}

				}

				// If there is no condition set, then search across other important
				// artworks fields
				if (!$condition) {
					$fields = array('Archives.name', 'Archives.classification', 'Archives.earliest_date', 'Works.materials', 'Works.remarks', 'Works.creation_number', 'Works.annotation');

					foreach ($fields as $field) {
						$matching_works = Works::find('artworks', array(
							'fields' => 'Works.id',
							'conditions' => array("$field" => array('LIKE' => "%$query%")),
						));

						if ($matching_works->count() > 0) {
							$matching_ids = $matching_works->map(function($mw) {
								return $mw->id;
							}, array('collect' => false));

							$artwork_ids = array_unique(array_merge($artwork_ids, $matching_ids));
						}
					}
				}

				$conditions = $artwork_ids ? array('Works.id' => $artwork_ids) : array('Works.id' => '');
			}

			$filter = '';

			if (Environment::get('artworks')) {
				$artworks = Environment::get('artworks');
				$filter = isset($artworks['filter']) ? $artworks['filter'] : '';
			}

			$conditions = $filter ? array_merge($filter, $conditions) : $conditions;

			$total = Works::count('artworks', array(
				'with' => 'Archives',
				'conditions' => $conditions,
			));

			//Interpret any non-integer limit to mean 'All' results
			$limit = !(intval($limit)) ? $total : $limit;

			$works = Works::find('artworks', array(
				'with' => 'Archives',
				'conditions' => $conditions,
				'limit' => $limit,
				'page' => $page
			));

		}

		return compact('works', 'condition', 'query', 'total', 'page', 'limit');

	}

	public function classifications() {

		$works_classifications = Archives::find('all', array(
			'fields' => array('classification', 'count(classification) as works'),
			'group' => 'classification',
			'conditions' => array('classification' => array('!=' => ''), 'controller' => 'works'),
			'order' => array('classification' => 'ASC'),
		));

		$classifications = $works_classifications->map(function($wc) {
			$archives = Archives::find('all', array(
				'fields' => 'id',
				'conditions' => array('classification' => $wc->classification, 'controller' => 'works'),
				'order' => array('date_created' => 'DESC'),
			));

			$archives_ids = $archives->map(function($ai) {
				return $ai->id;
			}, array('collect' => false));

			$doc_preview_conditions = array(
				'ArchivesDocuments.archive_id' => $archives_ids,
				'or' => array(
					array('Formats.mime_type' => 'application/pdf'),
					array('Formats.mime_type' => array('LIKE' => 'image/%'))
				)
			);

			$document = Documents::find('first', array(
				'with' => array('Formats', 'ArchivesDocuments'),
				'conditions' => $doc_preview_conditions,
				'order' => array('date_modified' => 'DESC')
			));

            if ($document) {
                $file_name = $document->title . '.' .$document->format->extension;
                $thumbnail = Router::match(array("Files::thumb", 'slug' => $document->slug, 'file' => $file_name));
            } else {
                $thumbnail = '';
            }

			$document_slug = $document ? $document->slug : '';

			return array('name' => $wc->classification, 'works' => $wc->works, 'thumbnail' => $thumbnail);
		}, array('collect' => false));

		return compact('classifications');
	}

	public function locations() {

        $locations = array();

		//Check that inventory is enabled
		if (Environment::get('inventory')) {
            $works_locations = Works::find('all', array(
                'fields' => array('location', 'count(location) as works'),
                'group' => 'location',
                'conditions' => array('location' => array('!=' => '')),
                'order' => array('works' => 'DESC'),
            ));

            $locations = $works_locations->map(function($wc) {
                return array('name' => $wc->location, 'works' => $wc->works);
            }, array('collect' => false));
		}

		return compact('locations');
	}

	public function histories() {

		$limit = 50;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$order = array('start_date' => 'DESC');
		$total = WorksHistories::count();
		$archives_histories = ArchivesHistories::find('all', array(
			'with' => array('Users', 'Archives'),
			'conditions' => array('ArchivesHistories.controller' => 'works'),
			'limit' => $limit,
			'order' => $order,
			'page' => $page
		));

		return compact('archives_histories', 'total', 'page', 'limit');
	}

	public function view() {

		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {

			//Get single record from the database where the slug matches the URL
			$works = Works::find('artworks', array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));

			$work = $works->first();

			if($work) {

				$artists = Persons::find('all', array(
					'with' => array('Archives', 'Components'),
					'conditions' => array(
						'Components.archive_id2' => $work->id,
					),
					'order' => array('Archives.name' => 'ASC')
				));

				$archives_documents = ArchivesDocuments::find('all', array(
					'with' => array(
						'Documents',
						'Documents.Formats'
					),
					'conditions' => array('ArchivesDocuments.archive_id' => $work->id),
					'order' => array('Documents.slug' => 'ASC')
				));

				$albums = Albums::find('all', array(
					'with' => array('Archives', 'Components'),
					'conditions' => array(
						'Components.archive_id2' => $work->id,
					),
					'order' => array('Archives.name' =>  'ASC')
				));

				$exhibitions = Exhibitions::find('all', array(
					'with' => array('Archives', 'Components'),
					'conditions' => array(
						'Components.archive_id2' => $work->id,
					),
					'order' => array('Archives.name' =>  'ASC')
				));

				$archives_links = ArchivesLinks::find('all', array(
					'with' => array(
						'Links'
					),
					'conditions' => array('ArchivesLinks.archive_id' => $work->id),
					'order' => array('Links.date_modified' =>  'DESC')
				));

				return compact('work', 'artists', 'archives_documents', 'archives_links', 'albums', 'exhibitions');
			}
		}

		//since no record was specified, redirect to the index page
		$this->redirect(array('Works::index'));
	}

	public function add() {

		$archive = Archives::create();
		$work = Works::create();
		$link = Links::create();
		$artist = Persons::create();
		$documents = array();

		if ($this->request->data) {

			if (isset($this->request->data['documents'])) {
				$document_ids = $this->request->data['documents'];

				$documents = Documents::find('all', array(
					'with' => 'Formats',
					'conditions' => array('Documents.id' => $document_ids),
				));
			}

			if (isset($this->request->data['artist'])) {
				$artist_data = $this->request->data['artist'];
				if (isset($artist_data['id'])) {
					$artist = Persons::find('first', array(
						'with' => 'Archives',
						'conditions' => array('id' => $artist_data['id'])
					));
				}
			}

			if (isset($this->request->data['archive'])) {
				$archive_data = $this->request->data['archive'];
				$archive_data['controller'] = 'works';
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

					// Save a work along with this archive
					$work_data = isset($this->request->data['work']) ? $this->request->data['work'] : array();
					$work_data['id'] = $archive->id;

					$work = Works::create();
					$work->save($work_data);

					// If an Artist was named, save a Component to connect person to artwork
					if(!empty($artist_data) && !empty($artist->id)) {
						$persons_works = Components::create();

						$persons_works->save(array(
							'archive_id1' => $artist->id,
							'archive_id2' => $work->id,
							'type' => 'persons_works',
							'role' => 'Artist'
						));
					}

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

					// If any documents were submitted, save them as ArchivesDocuments
					$archive_id = $archive->id;
					foreach ($documents as $doc) {
						$document_id = $doc->id;
						$ad = ArchivesDocuments::create();
						$ad->save(compact('archive_id', 'document_id'));
					}

					return $this->redirect(array('Works::view', 'slug' => $archive->slug));
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

			$artworks_config = Environment::get('artworks');

			if ($artworks_config && isset($artworks_config['artist']['default'])) {
				$default_artist = $artworks_config['artist']['default'];
				$artist = Persons::find('first', array(
					'with' => 'Archives',
					'conditions' => array('Archives.name' => $default_artist)
				));
			}
		}

		$artists = Persons::find('all', array(
			'with' => 'Archives',
			'order' => array('Archives.name' => 'ASC'),
			'conditions' => array('Archives.classification' => 'Artist')
		));

		$works_materials = Works::find('all', array(
			'fields' => array('materials', 'count(materials) as works'),
			'group' => 'materials',
			'conditions' => array('materials' => array('!=' => '')),
			'order' => array('works' => 'DESC', 'materials' => 'ASC')
		));

		$materials = $works_materials->map(function($wm) {
			return $wm->materials;
		}, array('collect' => false));

		$works_locations = Works::find('all', array(
			'fields' => array('location'),
			'group' => 'location',
			'conditions' => array('location' => array('!=' => '')),
			'order' => array('location' => 'ASC')
		));

		$locations = $works_locations->map(function($wl) {
			return $wl->location;
		}, array('collect' => false));

		$users = Users::find('all', array(
			'order' => array('name' => 'ASC'),
		));

		$classifications = Works::classifications();

		return compact('archive', 'work', 'artist', 'link', 'artists', 'classifications', 'materials', 'locations', 'users', 'documents');
	}

	public function edit() {

		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {

			$work = Works::first(array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));

			if (empty($work)) {
				return $this->redirect('Works::index');
			}

			$archive = $work->archive;

			$artist = Persons::find('first', array(
				'with' => array('Archives', 'Components'),
				'conditions' => array(
					'Components.archive_id2' => $work->id,
				),
				'order' => array('Archives.name' => 'ASC')
			));

			if ($this->request->data) {
				$data = $this->request->data;

				if (isset($data['archive'])) {

					$archive_data = $data['archive'];

					if ($archive->save($archive_data)) {

						$work_data = isset($data['work']) ? $data['work'] : array();
						$work->save($work_data);

						// Get the posted artist data
						$artist_data = isset($data['artist']) ? $data['artist'] : array();
						$artist_id = isset($artist_data['id']) ? $artist_data['id'] : '';

						// Check if there was an artist for this work,
						// but the posted artist does not match
						if ($artist && ($artist->id != $artist_id)) {
							// Delete the relationship between the work and the original artist
							foreach ($artist->components as $c) {
								$c->delete();
							}
						}

						// Check if a new artist has been posted
						if ($artist_id && (!$artist || $artist->id != $artist_id)) {
							// Associate the artwork with the new artist
							$persons_works = Components::create();

							$persons_works->save(array(
								'archive_id1' => $artist_id,
								'archive_id2' => $work->id,
								'type' => 'persons_works',
								'role' => 'Artist'
							));
						}

						return $this->redirect(array('Works::view', 'slug' => $archive->slug));
					}
				}
			}

			$artists = Persons::find('all', array(
				'with' => 'Archives',
				'order' => array('Archives.name' => 'ASC'),
				'conditions' => array('Archives.classification' => 'Artist')
			));

			$works_materials = Works::find('all', array(
				'fields' => array('materials', 'count(materials) as works'),
				'group' => 'materials',
				'conditions' => array('materials' => array('!=' => '')),
				'order' => array('works' => 'DESC', 'materials' => 'ASC')
			));

			$materials = $works_materials->map(function($wm) {
				return $wm->materials;
			}, array('collect' => false));

			$classifications = Works::classifications();

			$works_locations = Works::find('all', array(
				'fields' => array('location'),
				'group' => 'location',
				'conditions' => array('location' => array('!=' => '')),
				'order' => array('location' => 'ASC')
			));

			$locations = $works_locations->map(function($wl) {
				return $wl->location;
			}, array('collect' => false));

			$users = Users::find('all', array(
				'order' => array('name' => 'ASC'),
			));

			$albums = Albums::find('all', array(
				'with' => array('Archives', 'Components'),
				'conditions' => array(
					'Components.archive_id2' => $work->id,
				),
				'order' => array('Archives.name' =>  'ASC')
			));

			$album_ids = array();

			foreach ($albums as $album) {
				array_push($album_ids, $album->id);
			}

			//Find the albums the work is NOT in
			$other_album_conditions = ($album_ids) ? array('Albums.id' => array('!=' => $album_ids)) : '';

			$other_albums = Albums::find('all', array(
				'with' => 'Archives',
				'order' => array('Archives.name' =>  'ASC'),
				'conditions' => $other_album_conditions
			));

			$exhibitions = Exhibitions::find('all', array(
				'with' => array('Archives', 'Components'),
				'conditions' => array(
					'Components.archive_id2' => $work->id,
				),
				'order' => array('Archives.name' =>  'ASC')
			));

			$exhibition_ids = array();

			foreach ($exhibitions as $exhibition) {
				array_push($exhibition_ids, $exhibition->id);
			}

			//Find the exhibitions the work is NOT in
			$other_exhibition_conditions = ($exhibition_ids) ? array('Exhibitions.id' => array('!=' => $exhibition_ids)) : '';

			$other_exhibitions = Exhibitions::find('all', array(
				'with' => 'Archives',
				'order' => array('earliest_date' => 'DESC'),
				'conditions' => $other_exhibition_conditions
			));

			$archives_documents = ArchivesDocuments::find('all', array(
				'with' => array(
					'Documents',
					'Documents.Formats'
				),
				'conditions' => array('archive_id' => $work->id),
				'order' => array('Documents.slug' => 'ASC')
			));

			return compact(
				'archive',
				'work',
				'artist',
				'archives_documents',
				'albums',
				'other_albums',
				'exhibitions',
				'other_exhibitions',
				'artists',
				'classifications',
				'materials',
				'locations',
				'users'
			);
		}

		$this->redirect(array('Works::index'));

	}

	public function attachments() {

		if(isset($this->request->params['slug'])) {

			$work = Works::first(array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));

			if($work) {

				$albums = Albums::find('all', array(
					'with' => array('Archives', 'Components'),
					'conditions' => array(
						'Components.archive_id2' => $work->id,
					),
					'order' => array('Archives.name' =>  'ASC')
				));

				$album_ids = array();

				foreach ($albums as $album) {
					array_push($album_ids, $album->id);
				}

				//Find the albums the work is NOT in
				$other_album_conditions = ($album_ids) ? array('Albums.id' => array('!=' => $album_ids)) : '';

				$other_albums = Albums::find('all', array(
					'with' => 'Archives',
					'order' => array('Archives.name' =>  'ASC'),
					'conditions' => $other_album_conditions
				));

				$exhibitions = Exhibitions::find('all', array(
					'with' => array('Archives', 'Components'),
					'conditions' => array(
						'Components.archive_id2' => $work->id,
					),
					'order' => array('Archives.name' =>  'ASC')
				));

				$exhibition_ids = array();

				foreach ($exhibitions as $exhibition) {
					array_push($exhibition_ids, $exhibition->id);
				}

				//Find the exhibitions the work is NOT in
				$other_exhibition_conditions = ($exhibition_ids) ? array('Exhibitions.id' => array('!=' => $exhibition_ids)) : '';

				$other_exhibitions = Exhibitions::find('all', array(
					'with' => 'Archives',
					'order' => array('Archives.name' =>  'ASC'),
					'conditions' => $other_exhibition_conditions
				));

				$archives_documents = ArchivesDocuments::find('all', array(
					'with' => array(
						'Documents',
						'Documents.Formats'
					),
					'conditions' => array('archive_id' => $work->id),
					'order' => array('Documents.slug' => 'ASC')
				));

				$archives_links = ArchivesLinks::find('all', array(
					'with' => array(
						'Links'
					),
					'conditions' => array('ArchivesLinks.archive_id' => $work->id),
					'order' => array('Links.date_modified' =>  'DESC')
				));

				if (($this->request->data) && $work->save($this->request->data)) {
					return $this->redirect(array('Works::view', 'args' => array($this->request->params['slug'])));
				}

				return compact(
					'work',
					'archives_documents',
					'albums',
					'other_albums',
					'exhibitions',
					'other_exhibitions',
					'archives_links'
				);
			}
		}

		$this->redirect(array('Works::index'));

	}

	public function history() {

		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {

			//Get single record from the database where the slug matches the URL
			$work = Works::first(array(
				'with' => 'Archives',
				'conditions' => array('Archives.slug' => $this->request->params['slug']),
			));

			if($work) {

				$archives_histories = ArchivesHistories::find('all', array(
					'conditions' => array('ArchivesHistories.archive_id' => $work->id),
					'order' => 'ArchivesHistories.start_date DESC',
					'with' => array('Users', 'WorksHistories'),
				));

				//FIXME We can't actually guarantee that the start_date can be used as a foreign key for the histories,
				//so for now let's grab the subclass history table, then iterate through it as well
				$works_histories = WorksHistories::find('all', array(
					'conditions' => array('work_id' => $work->id),
					'order' => array('start_date' => 'DESC')
				));

				//Send the retrieved data to the view
				return compact('work', 'archives_histories', 'works_histories');
			}
		}

		//since no record was specified, redirect to the index page
		$this->redirect(array('Works::index'));
	}

	public function publish() {

		$query = $this->request->query;
		$data = $this->request->data ?: $this->request->query;

		$archive_ids = isset($data['archives']) ? $data['archives'] : array();
		$exhibition_id = isset($data['exhibition']) ? $data['exhibition'] : NULL;

		if ($archive_ids || $exhibition_id) {

			$parent = Archives::create();

			if (!empty($exhibition_id)) {
				$parent = Archives::find('first', array(
					'conditions' => array(
						'Archives.id' => $exhibition_id
					)
				));

				$exhibitions_works = Components::find('all', array(
					'fields' => 'archive_id2',
					'conditions' => array(
						'archive_id1' => $parent->id,
						'type' => 'exhibitions_works'
					),
				));

				$work_ids = $exhibitions_works->map(function($ew) {
					return $ew->archive_id2;
				}, array('collect' => false));

				$archive_ids = array_merge($archive_ids, $work_ids);
			}

			$works = Works::find('artworks', array(
				'conditions' => array('Archives.id' => $archive_ids),
			));

			$artists = Persons::find('all', array(
				'with' => array('Archives', 'Components'),
				'conditions' => array(
					'Components.archive_id2' => $archive_ids,
					'Components.role' => 'artist'
				),
				'order' => array('Archives.name' => 'ASC')
			));

            $inventory = false;

            $check = (Auth::check('default')) ?: null;
            if ($check) {
                $auth = Users::first(array(
                    'conditions' => array('username' => $check['username']),
                    'with' => array('Roles')
                ));

                if ($auth) {

                    if($auth->role->name === 'Admin' || $auth->role->name === "Registrar") {
                        if (Environment::get('inventory')) {
                            $inventory = true;
                        }
                    }
                }
            }

			if (!empty($parent->name)) {
				$pdf = $parent->slug . '.pdf';
				$template = 'list';
			} elseif ($works->count() == 1) {
				$work = $works->current();
				$pdf = $work->archive->slug . '.pdf';
				$template = 'single';
			} else {
				$host = Inflector::humanize($this->request->env('HTTP_HOST'));
				$pdf = Inflector::slug($host) . '.pdf';
				$template = 'list';
			}

			$organization = Environment::get('organization');
			$title = isset($organization['name']) ? $organization['name'] : '';
			$config = FileSystem::config('documents');
			$options = array(
				'path' => $config['path'],
				'title' => $title
			);

			$view  = new View(array(
				'paths' => array(
					'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
					'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
				)
			));

			echo $view->render(
				'all',
				array('content' => compact(
					'pdf',
					'works',
					'parent',
					'artists',
					'inventory',
					'options'
				)),
				array(
					'controller' => 'works',
					'template' => $template,
					'type' => 'pdf',
					'layout' => 'download'
				)
			);
		}

	}

	public function delete() {

        $action = null;
        $request = $this->request;

        $slug = isset($request->params['slug']) ? $request->params['slug'] : null;
        $archives = isset($request->data['archives']) ? $request->data['archives'] : array();

        if ($this->request->is('post') || $this->request->is('delete')) {
            $conditions = array();

            if (!empty($slug)) {
                $conditions['Archives.slug'] = $slug;
            }

            if (!empty($archives)) {
                $conditions['Archives.id'] = $archives;
            }

            if (!empty($conditions)) {
                $success = Works::find('all', array(
                    'with' => 'Archives',
                    'conditions' => $conditions
                ))->delete();

                $action = $success ? 'delete' : null;
            }
        }

        return compact('action');
	}
}

?>
