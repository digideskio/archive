<?php

/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2012, Fabricatorz (http://fabricatorz.com)
 * @license       http://sharism.org/agreement The Sharing Agreement
 */

/**
 * The access file configures the li_access plugin and adds our custom rules
 *
 * @see lithium\core\Environment
 */

use li3_access\security\Access;

Access::config(array(
	'rule_based' => array(
		'adapter' => 'Rules',
		// optional filters applied to check() method
		'filters' => array(
		function($self, $params, $chain) {
			// any config can define filters to do some stuff
			return $chain->next($self, $params, $chain);
		}
		)
	)
));


Access::adapter('rule_based')->add('isAdminUser', function($user, $request, $options){

	if($user['role_id'] == '1'){
		return true;
	}
	return false;
});

Access::adapter('rule_based')->add('isEditorUser', function($user, $request, $options){
	if($user['role'] == 'editor' ||  $user['role'] == 'admin'){
		return true;
	}
	return false;
});
