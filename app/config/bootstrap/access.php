<?php

/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2012, Fabricatorz (http://fabricatorz.com)
 * @license       http://sharism.org/agreement The Sharing Agreement
 */

/**
 * The access file configures the li_access plugin and adds our custom rules.
 * It also adds a the user's "role" to the request parameters.
 *
 * @see lithium\core\Environment
 */

use li3_access\security\Access;
use lithium\security\Auth;
use app\models\Users;

Access::config(array(
	'rule_based' => array(
		'adapter' => 'Rules',
		// optional filters applied to check() method
		'filters' => array(
		function($self, $params, $chain) {

			$check = $params['user'];

			if ($check) {

				$auth = Users::find('first', array(
					'with' => 'Roles',
					'conditions' => array('username' => $check['username']),
				));

				$params['user']['role'] = $auth ? $auth->role->name : '';

			}

			// any config can define filters to do some stuff
			return $chain->next($self, $params, $chain);
		}
		)
	)
));

Access::adapter('rule_based')->add('denyNonUser', function($user, $request, $options) {

	return $user != NULL;

});

Access::adapter('rule_based')->add('allowAdminUser', function($user, $request, $options){

	return $user['role'] == 'Admin'; 

});

Access::adapter('rule_based')->add('isEditorUser', function($user, $request, $options){

	return ($user['role'] == 'Admin' || $user['role'] == 'Editor'); 

});

?>
