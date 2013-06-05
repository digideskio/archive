<?php

/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2012, Fabricatorz (http://fabricatorz.com)
 * @license       http://sharism.org/agreement The Sharing Agreement
 */

/**
 * The access file configures the li_access plugin and adds our custom rules.
 *
 * @see lithium\core\Environment
 */

use li3_access\security\Access;
use lithium\security\Auth;
use app\models\Users;
use lithium\action\Dispatcher;
use lithium\action\Response;

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

Access::adapter('rule_based')->add('allowAdminUser', function($user, $request, $options){

	return $user['role'] == 'Admin'; 

});

Access::adapter('rule_based')->add('allowEditorUser', function($user, $request, $options){

	return ($user['role'] == 'Admin' || $user['role'] == 'Editor'); 

});

Access::adapter('rule_based')->add('allowAdminOrUserRequestingSelf', function($user, $request, $options) {

	return ($user['role'] == 'Admin' || $user['username'] == $request->params['username']);

});

Access::adapter('rule_based')->add('denyUserRequestingSelf', function($user, $request, $options) {

	return $user['username'] != $request->params['username'];

});

/**
 * This filter intercepts the `_callable()` method of the `Dispatcher` after it finds a
 * controller object but before it returns it to `Dispatcher::run()`. It examines the
 * controller for a $rules property, which contains access rules for each action.
 */

 Dispatcher::applyFilter('_callable', function($self, $params, $chain) {

	$ctrl =  $chain->next($self, $params, $chain);
	$request = isset($params['request']) ? $params['request'] : null;
	$action  = $params['params']['action'];

	if (isset($ctrl->rules) && isset($ctrl->rules[$action])) {
		$rules = $ctrl->rules[$action];

		$check = (Auth::check('default')) ?: null;

		$access = Access::check('rule_based', $check, $request, array('rules' => $rules));

        if (!empty($access)) {
			return function() use ($request, $access) {
				return new Response(compact('request') + array('location' => $access['redirect'], 'status' => '302'));
			};
        }
	}

	return $ctrl;

 });

?>
