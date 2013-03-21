<?php

namespace app\controllers;

use app\models\ArchivesDocuments;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class ArchivesDocumentsController extends \lithium\action\Controller {

	public function add() {
	    $check = (Auth::check('default')) ?: null;
	
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
        
        // If the user is not an Admin or Editor, redirect to the record view
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
			return $this->redirect('Pages::home');
        }

		$archive_document = ArchivesDocuments::create();

		if (($this->request->data) && $archive_document->save($this->request->data)) {
			return $this->redirect($this->request->env('HTTP_REFERER'));
		}

		return $this->redirect('Pages::home');
	}


	public function delete() {
	    $check = (Auth::check('default')) ?: null;
	
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
        
        // If the user is not an Admin or Editor, redirect to the record view
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
			return $this->redirect('Pages::home');
        }
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Components::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}

		ArchivesDocuments::find($this->request->id)->delete();

		return $this->redirect($this->request->env('HTTP_REFERER'));
	}
}

?>
