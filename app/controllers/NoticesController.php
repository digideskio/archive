<?php

namespace app\controllers;

use app\models\Notices;

use app\models\Users;

use lithium\action\DispatchException;
use lithium\security\Auth;

class NoticesController extends \lithium\action\Controller {

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

		$order = array('date_modified' => 'DESC');

		$notices = Notices::all(array(
			'order' => $order
		));
		return compact('notices');
	}

	public function view() {

		$notice = Notices::first($this->request->id);
		return compact('notice');
	}

	public function add() {

		$notice = Notices::create();

		if (($this->request->data) && $notice->save($this->request->data)) {
			return $this->redirect(array('Notices::index'));
		}
		return compact('notice');
	}

	public function edit() {

		$notice = Notices::find($this->request->id);

		if (!$notice) {
			return $this->redirect('Notices::index');
		}
		if (($this->request->data) && $notice->save($this->request->data)) {
			return $this->redirect(array('Notices::index'));
		}
		return compact('notice');

	}

	public function delete() {

		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Notices::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		Notices::find($this->request->id)->delete();
		return $this->redirect('Notices::index');
	}
}

?>
