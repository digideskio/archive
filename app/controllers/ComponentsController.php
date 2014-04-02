<?php

namespace app\controllers;

use app\models\Components;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class ComponentsController extends \lithium\action\Controller {

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

			$archive_id1 = $this->request->data['archive_id1'];
			$archive_id2 = $this->request->data['archive_id2'];

			$component = Components::find('first', array(
				'conditions' => compact('archive_id1', 'archive_id2'),
			));

			if (!$component) {

				$component = Components::create();
				$component->save($this->request->data);

			}
		}

		return $this->redirect($this->request->env('HTTP_REFERER'));
	}

	public function delete() {

		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Components::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}

		$component = Components::find($this->request->id);

		if ($component) {
			$component->delete();
		}

		return $this->redirect($this->request->env('HTTP_REFERER'));
	}
}

?>
