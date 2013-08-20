<?php

namespace app\controllers;

use app\models\WorksLinks;
use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class WorksLinksController extends \lithium\action\Controller {

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
	
			$works_links = WorksLinks::create();
			$works_links->save($this->request->data);
			
			return $this->redirect($this->request->env('HTTP_REFERER'));
		}
		return $this->redirect('Works::index');
	}

	public function delete() {
        
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "WorksLinks::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		WorksLinks::find($this->request->id)->delete();
		
		return $this->redirect($this->request->env('HTTP_REFERER'));
	}
}

?>
