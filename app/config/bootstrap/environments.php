<?php

/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2012, Fabricatorz (http://fabricatorz.com)
 * @license       http://sharism.org/agreement The Sharing Agreement
 */

/**
 * The environments file sets the Environment for the entire app. We can first of all specify
 * a different database connection for each environment in connections.php. We can also 
 * change the behavior of the app depending on the environment. For example, routes.php will
 * disable the /test page it if detects we are running in the 'production' environment. By
 * default, the environment is set to 'development' if the site is running on localhost, and
 * 'test' if we are running the /test page. Otherwise, it defaults to the 'production' environment.
 * We can change this behavior by passing a new anonymous function to Environment::is().
 * 
 * Instead, we will determine the environment based an an Apache config variable. If the variable
 * is not set, we will default to the 'production' environment. You can change the environment in
 * your Apache config by adding the following line:
 * 
 * SetEnv LITHIUM_ENVIRONMENT "development"
 *
 * @see lithium\core\Environment
 */

use lithium\core\Environment;

Environment::is(function($request) {
    return $request->env('LITHIUM_ENVIRONMENT') ?: 'production';
});

?>
