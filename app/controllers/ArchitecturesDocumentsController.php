<?php

namespace app\controllers;

use app\models\ArchitecturesDocuments;
use app\models\Documents;
use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class ArchitecturesDocumentsController extends \lithium\action\Controller {

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
        	return $this->redirect(array(
        		'Architectures::view', 'args' => array($this->request->data['architecture_slug']))
        	);
        }
	
		if ($this->request->data) {
	
			$architecture_documents = ArchitecturesDocuments::create();
		
			$document = Documents::first(array(
				'conditions' => array('slug' => $this->request->data['document_slug'])
			));
		
			if($document) {
				$this->request->data['document_id'] = $document->id;
				
				$architecture_documents->save($this->request->data);
			
			}

			return $this->redirect(array('Architectures::edit', 'args' => array($this->request->data['architecture_slug'])));
		}
		return $this->redirect('Architectures::index');
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
        
        // If the user is not an Admin or Editor, redirect
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
        	return $this->redirect('Architectures::index');
        }
        
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "ArchitecturesDocuments::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		//Dont run the query if no id is provided
		if(isset($this->request->params['id'])) {
			ArchitecturesDocuments::first(array(
					'conditions' => array('id' => $this->request->params['id'])
				))->delete();
		}
		
		return $this->redirect(array(
    		'Architectures::edit', 'args' => array($this->request->data['architecture_slug']))
    	);
	}
}

?>
