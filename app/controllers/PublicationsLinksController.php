<?php

namespace app\controllers;

use app\models\PublicationsLinks;
use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class PublicationsLinksController extends \lithium\action\Controller {

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
	
			$works_links = PublicationsLinks::create();
			$works_links->save($this->request->data);
			
			return $this->redirect(array('Publications::attachments', 'args' => array($this->request->data['publication_slug'])));

		}
		return $this->redirect('Publications::index');
	}

	public function delete() {
        
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "PublicationsLinks::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		PublicationsLinks::find($this->request->id)->delete();
		
		return $this->redirect(array(
    		'Publications::attachments', 'args' => array($this->request->data['publication_slug']))
    	);
	}
}

?>
