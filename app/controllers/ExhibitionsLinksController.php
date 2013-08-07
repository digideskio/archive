<?php

namespace app\controllers;

use app\models\ExhibitionsLinks;
use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class ExhibitionsLinksController extends \lithium\action\Controller {

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
        		'Exhibitions::view', 'args' => array($this->request->data['exhibition_slug']))
        	);
        }
	
		if ($this->request->data) {
	
			$exhibitions_links = ExhibitionsLinks::create();
			$exhibitions_links->save($this->request->data);
			
			return $this->redirect(array('Exhibitions::attachments', 'args' => array($this->request->data['exhibition_slug'])));
		}
		return $this->redirect('Exhibitions::index');
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
        	return $this->redirect('Exhibitions::index');
        }
        
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "ExhibitionsLinks::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		ExhibitionsLinks::find($this->request->id)->delete();
		
		return $this->redirect(array(
    		'Exhibitions::attachments', 'args' => array($this->request->data['exhibition_slug']))
    	);
	}
}

?>
