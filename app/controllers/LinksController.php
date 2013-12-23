<?php

namespace app\controllers;

use app\models\Links;

use app\models\Archives;
use app\models\ArchivesLinks;
use app\models\Works;
use app\models\Exhibitions;
use app\models\Publications;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;
use lithium\data\collection\RecordSet;

class LinksController extends \lithium\action\Controller {

	public $rules = array(
		'index' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'search' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'view' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'add' => array(
			array('rule' => 'allowAdminUser', 'redirect' => "Pages::home"),
		),
		'edit' => array(
			array('rule' => 'allowAdminUser', 'redirect' => "Pages::home"),
		),
		'delete' => array(
			array('rule' => 'allowAdminUser', 'redirect' => "Pages::home"),
		),
	);

	public function index() {

		$saved = isset($this->request->query['saved']) ? $this->request->query['saved'] : '';

		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 40;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$order = array('date_modified' => 'DESC');

		$total = Links::count();

		$links = Links::find('all', array(
			'with' => array('ArchivesLinks', 'ArchivesLinks.Archives'),
			'limit' => $limit,
			'order' => $order,
			'page' => $page
		));
		return compact('links', 'total', 'page', 'limit', 'saved');
	}

	public function search() {

		$links = array();

		$query = '';
		$condition = '';

		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 40;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$order = array('date_modified' => 'DESC');
		$total = 0;

		$data = $this->request->data ?: $this->request->query;

		$conditions = array('title' => $query);

		if (isset($data['query']) && $data['query']) {

			$query = trim($data['query']);

			$link_ids = array();

			$fields = array('title', 'url', 'description');

			foreach ($fields as $field) {
				$matching_links = Links::find('all', array(
					'fields' => 'Links.id',
					'conditions' => array("$field" => array('LIKE' => "%$query%")),
				));

				if ($matching_links) {
					$matching_ids = $matching_links->map(function($match) {
						return $match->id;
					}, array('collect' => false));

					$link_ids = array_unique(array_merge($link_ids, $matching_ids));
				}
			}

			$conditions = $link_ids ? array('Links.id' => $link_ids) : array('title' => $query);
		}

		$links = Links::find('all', array(
			'with' => array('ArchivesLinks', 'ArchivesLinks.Archives'),
			'conditions' => $conditions,
			'order' => $order,
			'limit' => $limit,
			'page' => $page
		));

		$total = Links::count(array(
			'conditions' => $conditions,
		));

		return compact('links', 'condition', 'query', 'total', 'page', 'limit');
	}

	public function view() {

		$link = Links::first($this->request->id);

		$works_with_links = Works::find('all', array(
			'with' => array('ArchivesLinks', 'Archives'),
			'fields' => array('Archives.id', 'Works.id'),
			'conditions' => array('ArchivesLinks.link_id' => $link->id),
		));

		$work_ids = array();

		if ($works_with_links->count()) {

			//Get all the work IDs in a plain array
			$work_ids = $works_with_links->map(function($wwl) {
				return $wwl->id;
			}, array('collect' => false));

			$works = Works::find('artworks', array(
				'conditions' => array('Works.id' => $work_ids)
			));

		} else {
			$works = new RecordSet();
		}

		$exhibitions = Exhibitions::find('all', array(
			'with' => array('ArchivesLinks', 'Archives'),
			'conditions' => array('ArchivesLinks.link_id' => $link->id),
			'order' => array('Archives.earliest_date' => 'DESC')
		));

		$publications = Publications::find('all', array(
			'with' => array('ArchivesLinks', 'Archives'),
			'conditions' => array('ArchivesLinks.link_id' => $link->id),
			'order' => array('Archives.earliest_date' => 'DESC')
		));

		return compact('link', 'works', 'exhibitions', 'publications');
	}

	public function add() {

		$link = Links::create();

		if (($this->request->data) && $link->save($this->request->data)) {
        	return $this->redirect("/links?saved=$link->id");
		}
		return compact('link');
	}

	public function edit() {

		$link = Links::find($this->request->id);

		$redirect = isset($this->request->query['redirect']) ? $this->request->env('HTTP_REFERER') : '';

		if (!$link) {
			return $this->redirect('Links::index');
		}
		if (($this->request->data) && $link->save($this->request->data)) {

			if ($this->request->data['redirect']) {
				return $this->redirect($this->request->data['redirect']);
			} else {
	      		return $this->redirect("/links?saved=$link->id");
			}
		}
		return compact('link', 'redirect');
	}

	public function delete() {

		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Links::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}

		Links::find($this->request->id)->delete();
		return $this->redirect('Links::index');
	}
}

?>
