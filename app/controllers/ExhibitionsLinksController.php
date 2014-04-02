<?php

namespace app\controllers;

use app\models\ExhibitionsLinks;
use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class ExhibitionsLinksController extends \lithium\action\Controller {

	public $rules = array(
		'add' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
		'delete' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
	);

	public function add() {

		if ($this->request->data) {

			$exhibitions_links = ExhibitionsLinks::create();
			$exhibitions_links->save($this->request->data);

			return $this->redirect(array('Exhibitions::attachments', 'args' => array($this->request->data['exhibition_slug'])));
		}
		return $this->redirect('Exhibitions::index');
	}

	public function delete() {

		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "ExhibitionsLinks::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}

		ExhibitionsLinks::find($this->request->id)->delete();

		return $this->redirect(array(
    		'Exhibitions::attachments', 'args' => array($this->request->data['exhibition_slug']))
    	);
	}
}

?>
