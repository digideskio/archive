<?php

namespace app\controllers;

use app\models\Collections;
use lithium\action\DispatchException;

class CollectionsController extends \lithium\action\Controller {

	public function index() {
		$collections = Collections::all();
		return compact('collections');
	}

	public function view() {
		$collection = Collections::first($this->request->id);
		return compact('collection');
	}

	public function add() {
		$collection = Collections::create();

		if (($this->request->data) && $collection->save($this->request->data)) {
			return $this->redirect(array('Collections::view', 'args' => array($collection->id)));
		}
		return compact('collection');
	}

	public function edit() {
		$collection = Collections::find($this->request->id);

		if (!$collection) {
			return $this->redirect('Collections::index');
		}
		if (($this->request->data) && $collection->save($this->request->data)) {
			return $this->redirect(array('Collections::view', 'args' => array($collection->id)));
		}
		return compact('collection');
	}

	public function delete() {
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Collections::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		Collections::find($this->request->id)->delete();
		return $this->redirect('Collections::index');
	}
}

?>