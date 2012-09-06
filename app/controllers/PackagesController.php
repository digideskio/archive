<?php

namespace app\controllers;

use app\models\Packages;
use lithium\action\DispatchException;

class PackagesController extends \lithium\action\Controller {

	public function index() {
		$packages = Packages::all();
		return compact('packages');
	}

	public function view() {
		$package = Packages::first($this->request->id);
		return compact('package');
	}

	public function add() {
		$package = Packages::create();

		if (($this->request->data) && $package->save($this->request->data)) {
			return $this->redirect(array('Packages::view', 'args' => array($package->id)));
		}
		return compact('package');
	}

	public function edit() {
		$package = Packages::find($this->request->id);

		if (!$package) {
			return $this->redirect('Packages::index');
		}
		if (($this->request->data) && $package->save($this->request->data)) {
			return $this->redirect(array('Packages::view', 'args' => array($package->id)));
		}
		return compact('package');
	}

	public function delete() {
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Packages::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		Packages::find($this->request->id)->delete();
		return $this->redirect('Packages::index');
	}
}

?>
