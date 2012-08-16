<?php

namespace app\controllers;

use app\models\ExhibitionsWorks;
use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class ExhibitionsWorksController extends \lithium\action\Controller {

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
        		'Works::view', 'args' => array($this->request->data['work_slug']))
        	);
        }
	
		if ($this->request->data) {
	
			$exhibitions_works = ExhibitionsWorks::create();
			$exhibitions_works->save($this->request->data);
			
			return $this->redirect(array('Works::edit', 'args' => array($this->request->data['work_slug'])));
		}
		return $this->redirect('Works::index');
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
        	return $this->redirect('Works::index');
        }
        
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "ExhibitionsWorks::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		//Dont run the query if no id is provided
		if(isset($this->request->params['id'])) {
			ExhibitionsWorks::first(array(
					'conditions' => array('id' => $this->request->params['id'])
				))->delete();
		}
		
		return $this->redirect(array(
    		'Works::edit', 'args' => array($this->request->data['work_slug']))
    	);
	}
}

?>
