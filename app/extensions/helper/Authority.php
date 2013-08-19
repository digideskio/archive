<?php

namespace app\extensions\helper;

use app\models\Users;
use lithium\security\Auth;

class Authority extends \lithium\template\Helper {

	protected function _auth() {

		$check = Auth::check('default');

		$auth = Users::find('first', array(
			'with' => 'Roles',
			'conditions' => array('username' => $check['username']),
		));

		return $auth;
		
	}

	public function role() {

		$auth = $this->_auth();
		return $auth->role->name;

	}

	public function canEdit() {

		$auth = $this->_auth();
		return ('Admin' === $auth->role->name || 'Editor' === $auth->role->name);

	}

	public function timezone() {

		$auth = $this->_auth();
		return $auth->timezone_id ?: '';

	}

}

?>
