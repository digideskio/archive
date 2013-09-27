<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2012, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * This file contains a series of method filters that allow you to intercept different parts of
 * Lithium's dispatch cycle. The filters below are used for on-demand loading of routing
 * configuration, and automatically configuring the correct environment in which the application
 * runs.
 *
 * For more information on in the filters system, see `lithium\util\collection\Filters`.
 *
 * @see lithium\util\collection\Filters
 */

use lithium\core\Libraries;
use lithium\core\Environment;
use lithium\action\Dispatcher;
use lithium\storage\Session;
use lithium\security\Auth;

/**
 * This filter intercepts the `run()` method of the `Dispatcher`, and first passes the `'request'`
 * parameter (an instance of the `Request` object) to the `Environment` class to detect which
 * environment the application is running in. Then, loads all application routes in all plugins,
 * loading the default application routes last.
 *
 * Change this code if plugin routes must be loaded in a specific order (i.e. not the same order as
 * the plugins are added in your bootstrap configuration), or if application routes must be loaded
 * first (in which case the default catch-all routes should be removed).
 *
 * If `Dispatcher::run()` is called multiple times in the course of a single request, change the
 * `include`s to `include_once`.
 *
 * This filter re-configures your session storage if it detects a 'session' Enviroment variable.
 * For example, you can use a Cookie adapter with the Hmac strategy by adding the following 
 * code in your connections.php file:
 * 
 * {{{use lithium\core\Environment;
 * 		$session = array('default' => array(
 *			'adapter' => 'Cookie',
 * 			'strategies' => array('Hmac' => array('secret' => 'YOUR_SECRET')),
 *			'name' => 'YOUR_APP',
 * 		));
 *		Environment::set('production', compact('session'));
 * }}}
 *
 * In addition, the filter sets a `custom` session config using the Php adapter, which is
 * used to store temporary user options.
 *
 * The filter then checks authentication. If an Exception is thrown (by Hmac) then it will 
 * clear the session (the cookie) and log the user out.
 *
 * @see lithium\action\Request
 * @see lithium\core\Environment
 * @see lithium\net\http\Router
 */
Dispatcher::applyFilter('run', function($self, $params, $chain) {
	Environment::set($params['request']);

	$session = Environment::get('session');

	// Session config for saving temporary per-session user options
	$custom = array('adapter' => 'Php', 'name' => 'Custom');

	if ($session) {
		$session['custom'] = $custom;
		Session::config($session);
	}
	
	try {
		Auth::check('default');
	} catch (RuntimeException $e) {
		Session::clear(array('default'));
		Auth::clear('default');
		Session::config(array(
			'default' => array('adapter' => 'Php', 'session.name' => 'app')
		));
	}

	foreach (array_reverse(Libraries::get()) as $name => $config) {
		if ($name === 'lithium') {
			continue;
		}
		$file = "{$config['path']}/config/routes.php";
		file_exists($file) ? call_user_func(function() use ($file) { include $file; }) : null;
	}
	return $chain->next($self, $params, $chain);
});


use app\models\Users;
use app\models\Requests;

/**
 * This filter intercepts the `_callable()` method of the `Dispatcher` and saves details about the
 * request to the `Requests` model.
 */
Dispatcher::applyFilter('_callable', function($self, $params, $chain) {

	if (Environment::is('production')) {

		$check = (Auth::check('default')) ?: null;

		if ($check) {
			
			$auth = Users::first(array(
				'conditions' => array('username' => $check['username']),
			));

			$user_id = $auth->id;

			$request = $params['request'];
			
			$url = $request->url;

			$parameters = $request->params;

			$controller = $parameters['controller'];
			$action = $parameters['action'];
			
			// Each request has at most one key, we just need to figure out which one
			$id = !empty($parameters['id']) ? $parameters['id'] : '';
			$slug = !empty($parameters['slug']) ? $parameters['slug'] : '';
			$file = !empty($parameters['file']) ? $parameters['file']  : '';
			$username = !empty($parameters['username']) ? $parameters['username'] : '';

			$keys = array_filter(array($id, $slug, $file, $username));
			$identifier = !empty($keys) ? array_shift($keys) : '';

			$referer = $request->referer();
			$query_string = $request->env('QUERY_STRING');
			$user_agent = $request->env('HTTP_USER_AGENT');
			$request_method = $request->env('REQUEST_METHOD');
			$remote_addr = $request->env('REMOTE_ADDR'); // FIXME The IP address always appears to be localhost
			$request_time = $request->env('REQUEST_TIME');

			$data = compact('url', 'controller', 'action', 'identifier', 'referer', 'query_string', 'user_agent', 'request_method', 'remote_addr', 'request_time', 'user_id');

			$req = Requests::create();
			$req->save($data);

		} else {
			$user_id = 0;
		}

	}

	return $chain->next($self, $params, $chain);

});

?>
