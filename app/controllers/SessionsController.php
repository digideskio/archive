<?php

namespace app\controllers;

use app\models\Users;

use lithium\security\Auth;
use lithium\storage\Session;

class SessionsController extends \lithium\action\Controller {

    public function add() {

		$message = '';
		$path = isset($this->request->query['path']) ? $this->request->query['path'] : '';

    	if(!Users::count()) {
    		return $this->redirect('/register');
    	}

        if ($this->request->data) {
        	if(Auth::check('default', $this->request)) {
				$redirect = isset($this->request->data['path']) && $this->request->data['path'] ? $this->request->data['path'] : '/home';
            	return $this->redirect($redirect);
        	} else {
        		$message = 'Wrong username or password.';
        	}
        }

        Auth::clear('default');

        return $this->render(array('data' => compact('message', 'path'), 'layout' => 'flat'));
    }

    /* ... */

    public function delete() {
        Auth::clear('default');
		return $this->render(array('layout' => 'flat'));
    }
}

?>
