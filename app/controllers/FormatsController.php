<?php

namespace app\controllers;

use app\models\Formats;
use lithium\action\DispatchException;

class FormatsController extends \lithium\action\Controller {

	public function index() {
		$formats = Formats::all();
		return compact('formats');
	}

	public function view() {
		$format = Formats::first($this->request->id);
		return compact('format');
	}

	public function add() {
		$format = Formats::create();

		if (($this->request->data) && $format->save($this->request->data)) {
			return $this->redirect(array('Formats::view', 'args' => array($format->id)));
		}
		return compact('format');
	}

	public function edit() {
		$format = Formats::find($this->request->id);

		if (!$format) {
			return $this->redirect('Formats::index');
		}
		if (($this->request->data) && $format->save($this->request->data)) {
			return $this->redirect(array('Formats::view', 'args' => array($format->id)));
		}
		return compact('format');
	}

	public function delete() {
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Formats::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		Formats::find($this->request->id)->delete();
		return $this->redirect('Formats::index');
	}
}

?>