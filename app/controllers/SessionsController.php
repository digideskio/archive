<?php

namespace app\controllers;

use lithium\security\Auth;
use li3_flash_message\extensions\storage\FlashMessage;

class SessionsController extends \lithium\action\Controller {

    public function add() {
        if ($this->request->data) {
        	if(Auth::check('default', $this->request)) {
            	return $this->redirect('/home');
        	} else {
        		FlashMessage::write('Wrong username or password.');
        	}
        }
        
        return $this->render(array('layout' => 'simple'));
    }

    /* ... */

    public function delete() {
        Auth::clear('default');
        return $this->redirect('/login');
    }
}

?>
