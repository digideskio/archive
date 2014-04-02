<?php

namespace app\tests\functional;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;
use lithium\net\http\Router;
use lithium\core\Libraries;

class RoutesTest extends \lithium\test\Integration {

	public function setUp() {

		Session::config(array(
			'default' => array('adapter' => 'Php', 'session.name' => 'app')
		));

		Auth::clear('default');

	}

	/*
	 * In order for other tests to work correctly, we have to set the Auth and re-connect
	 * all the routes.
	 */
	public function tearDown() {
		Auth::set('default', array('username' => 'test'));

		Router::reset();

		$config = Libraries::get('app');
		$file = "{$config['path']}/config/routes.php";
		file_exists($file) ? call_user_func(function() use ($file) { include $file; }) : null;
	}

	public function testPublicRoutes() {

		/*
		 * These are the only routes that should be publicly accessible, along with their
		 * connected controllers and actions.
		 */
		$public_routes = array(
			'/' => array('controller' => 'Pages', 'action' => 'blank'),
			'/login' => array('controller' => 'Sessions', 'action' => 'add'),
			'/logout' => array('controller' => 'Sessions', 'action' => 'delete'),
			'/register' => array('controller' => 'Users', 'action' => 'register'),
			'/files/package/{:file}' => array('controller' => 'Files', 'action' => 'package'),
			'/{:args}' => array('action' => 'index')
		);

		/*
		 * The setUp has already cleared the current Auth. Next, reload the routes file.
		 */
		Router::reset();

		$config = Libraries::get('app');
		$file = "{$config['path']}/config/routes.php";
		file_exists($file) ? call_user_func(function() use ($file) { include $file; }) : null;

		$configurations = Router::get();
		$routes = $configurations[0];
		$connected_routes = array();

		foreach ($routes as $route) {
			$export = $route->export();
			$template = $export['template'];
			$params = $export['params'];
			$controller = isset($export['params']['controller']) ? $export['params']['controller'] : '';
			$action = isset($export['params']['action']) ? $export['params']['action'] : '';

			$connected_routes[$template] = $params;

		}

		ksort($public_routes);
		ksort($connected_routes);

		$this->assertEqual(sizeof($public_routes), sizeof($connected_routes));

		foreach ($connected_routes as $template => $params) {
			$this->assertEqual($public_routes[$template], $params);
		}

	}

	/*
	 * This test hits the server and checks that all connected routes return a 302 except for
	 * the paths /, /login and /logout. The test is a bit slow to run and may be unnecessary.
	 * An additional complication is that the /register route will return different results
	 * depending on whether the connected database has any users in it or not.
	 */
	/*public function testRoutesSignedOut() {

		$public_routes = array('/', '/login', '/logout');

		$request = new Request();

		$request = Router::parse($request);
		$host = 'http://' . $request->env('HTTP_HOST');

		$configurations = Router::get();
		$routes = $configurations[0];

		foreach ($routes as $route) {
			$export = $route->export();
			$controller = isset($export['params']['controller']) ? $export['params']['controller'] : '';

			if ($controller) {

				$url = $export['template'];
				$url = preg_replace('#\{.*?\}#s', '1', $url);

				if (!in_array($url, $public_routes)) {

					$headers = get_headers($host . $url);

					$http_302 = 'HTTP/1.1 302 Found';
					$http_response = $headers[0];

					$this->assertEqual($http_response, $http_302, "Incorrect HTTP Response for $url. Expected: $http_302, Received: $http_response");
				}
			}
		}


	}*/

}

?>
