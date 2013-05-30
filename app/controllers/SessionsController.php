<?php

namespace app\controllers;

use app\models\Users;

use lithium\security\Auth;

class SessionsController extends \lithium\action\Controller {

    public function add() {

        Auth::clear('default');

		$message = '';
    	
    	if(!Users::count()) {
    		return $this->redirect('/register');
    	}
    
        if ($this->request->data) { 
        	if(Auth::check('default', $this->request)) {
            	return $this->redirect('/home');
        	} else {
        		$message = 'Wrong username or password.';
        	}
        }
        
        return $this->render(array('data' => compact('message'), 'layout' => 'simple'));
    }

    /* ... */

    public function delete() {
        Auth::clear('default');
		return $this->render(array('layout' => 'simple'));
    }
}

?>
