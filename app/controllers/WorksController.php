<?php

namespace app\controllers;

use app\models\Works;
use lithium\action\DispatchException;

class WorksController extends \lithium\action\Controller {

	public function index() {
		$works = Works::all();
		return compact('works');
	}

	public function view() {
		$work = Works::first($this->request->id);
		return compact('work');
	}

	public function add() {
		$work = Works::create();

		if (($this->request->data) && $work->save($this->request->data)) {
			return $this->redirect(array('Works::view', 'args' => array($work->id)));
		}
		return compact('work');
	}

	public function edit() {
		$work = Works::find($this->request->id);

		if (!$work) {
			return $this->redirect('Works::index');
		}
		if (($this->request->data) && $work->save($this->request->data)) {
			return $this->redirect(array('Works::view', 'args' => array($work->id)));
		}
		return compact('work');
	}

	public function delete() {
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Works::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		Works::find($this->request->id)->delete();
		return $this->redirect('Works::index');
	}
}

?>