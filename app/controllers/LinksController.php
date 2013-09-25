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

class LinksController extends \lithium\action\Controller {

	public $rules = array(
		'index' => array(
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

		$limit = 50;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$order = array('date_modified' => 'DESC');

		$total = Links::count();

		$links = Links::all(array(
			'with' => array('ArchivesLinks', 'ArchivesLinks.Archives'),
			'limit' => $limit,
			'order' => $order,
			'page' => $page
		));
		return compact('links', 'total', 'page', 'limit', 'saved');
	}

	public function view() {

		$link = Links::first($this->request->id);

		$works = Works::find('all', array(
			'with' => array('ArchivesLinks', 'Archives'),
			'conditions' => array('ArchivesLinks.link_id' => $link->id),
			'order' => array('Archives.earliest_date' => 'DESC')
		));

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
