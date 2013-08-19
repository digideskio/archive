<?php

namespace app\extensions\helper;

class Authority extends \lithium\template\Helper {

	public function role() {

		$check = \lithium\security\Auth::check('default');

		$auth = \app\models\Users::find('first', array(
			'with' => 'Roles',
			'conditions' => array('username' => $check['username']),
		));

		return $auth->role->name;

	}

}

?>
