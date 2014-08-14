<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2012, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The routes file is where you define your URL structure, which is an important part of the
 * [information architecture](http://en.wikipedia.org/wiki/Information_architecture) of your
 * application. Here, you can use _routes_ to match up URL pattern strings to a set of parameters,
 * usually including a controller and action to dispatch matching requests to. For more information,
 * see the `Router` and `Route` classes.
 *
 * @see lithium\net\http\Router
 * @see lithium\net\http\Route
 */
use lithium\net\http\Router;
use lithium\core\Environment;
use lithium\action\Response;
use lithium\security\Auth;

/**
 * Here, we are connecting `'/'` (the base path) to controller called `'Pages'`,
 * its action called `view()`, and we pass a param to select the view file
 * to use (in this case, `/views/pages/home.html.php`; see `app\controllers\PagesController`
 * for details).
 *
 * @see app\controllers\PagesController
 */
Router::connect('/', 'Pages::blank');

/**
 * Connect the rest of `PagesController`'s URLs. This will route URLs like `/pages/about` to
 * `PagesController`, rendering `/views/pages/about.html.php` as a static page.
 */
//Router::connect('/pages/{:args}', 'Pages::view');

/**
 * Connect the login and logout URLs
 */

Router::connect('/login', 'Sessions::add');
Router::connect('/logout', 'Sessions::delete');

Router::connect('/register', 'Users::register');

// Route for publicly accessible package downloads
Router::connect('/files/package/{:file}', array('Files::package'));

//Check if the user is logged in
$check = Auth::check('default');

if ($check) {

Router::connect('/home', 'Pages::home');

/**
 * Add the testing routes. These routes are only connected in non-production environments, and allow
 * browser-based access to the test suite for running unit and integration tests for the Lithium
 * core, as well as your own application and any other loaded plugins or frameworks. Browse to
 * [http://path/to/app/test](/test) to run tests.
 */
if (!Environment::is('production')) {
	Router::connect('/test/{:args}', array('controller' => 'lithium\test\Controller'));
	Router::connect('/test', array('controller' => 'lithium\test\Controller'));
}

/**
 * ### Database object routes
 *
 * The routes below are used primarily for accessing database objects, where `{:id}` corresponds to
 * the primary key of the database object, and can be accessed in the controller as
 * `$this->request->id`.
 *
 * If you're using a relational database, such as MySQL, SQLite or Postgres, where the primary key
 * is an integer, uncomment the routes below to enable URLs like `/posts/edit/1138`,
 * `/posts/view/1138.json`, etc.
 */
// Router::connect('/{:controller}/{:action}/{:id:\d+}.{:type}', array('id' => null));
// Router::connect('/{:controller}/{:action}/{:id:\d+}');

Router::connect('/users', array('Users::index'));
Router::connect('/users/add', array('Users::add'));
Router::connect('/users/register', array('Users::register'));
Router::connect('/users/view/{:username}', array('Users::view'));
Router::connect('/users/view/{:username}/{:page:[0-9]+}', array('Users::view'));
Router::connect('/users/edit/{:username}', array('Users::edit'));
Router::connect('/users/delete/{:username}', array('Users::delete'));
Router::connect('/users/activate/{:username}', array('Users::activate'));

Router::connect('/albums', array('Albums::index'));
Router::connect('/albums/pages/{:page:[0-9]+}', array('Albums::index'));
Router::connect('/albums/add', array('Albums::add'));
Router::connect('/albums/view/{:slug}', array('Albums::view'));
Router::connect('/albums/edit/{:slug}', array('Albums::edit'));
Router::connect('/albums/history/{:slug}', array('Albums::history'));
Router::connect('/albums/publish/{:slug}', array('Albums::publish'));
Router::connect('/albums/packages', array('Albums::packages'));
Router::connect('/albums/package/{:slug}', array('Albums::package'));
Router::connect('/albums/delete/{:slug}', array('Albums::delete'));

Router::connect('/components/delete/{:id}', array('Components::delete'));

Router::connect('/works/pages/{:page:[0-9]+}', array('Works::index'));
Router::connect('/works/view/{:slug}', array('Works::view'));
Router::connect('/works/edit/{:slug}', array('Works::edit'));
Router::connect('/works/sheet/{:slug}', array('Works::sheet'));
Router::connect('/works/attachments/{:slug}', array('Works::attachments'));
Router::connect('/works/history/{:slug}', array('Works::history'));
Router::connect('/works/histories/{:page:[0-9]+}', array('Works::histories'));
Router::connect('/works/search/{:page:[0-9]+}', array('Works::search'));
Router::connect('/works/publish', array('Works::publish'));
Router::connect('/works/delete/{:slug}', array('Works::delete'));

Router::connect('/artists', array('Persons::index'));
Router::connect('/artists/add', array('Persons::add'));
Router::connect('/artists/pages/{:page:[0-9]+}', array('Persons::index'));
Router::connect('/artists/view/{:slug}/{:page:[0-9]+}', array('Persons::view'));
Router::connect('/artists/view/{:slug}', array('Persons::view'));
Router::connect('/artists/edit/{:slug}', array('Persons::edit'));
Router::connect('/artists/delete/{:slug}', array('Persons::delete'));

if (Environment::get('architecture')) {
	Router::connect('/architectures/pages/{:page:[0-9]+}', array('Architectures::index'));
	Router::connect('/architectures/view/{:slug}', array('Architectures::view'));
	Router::connect('/architectures/edit/{:slug}', array('Architectures::edit'));
	Router::connect('/architectures/history/{:slug}', array('Architectures::history'));
	Router::connect('/architectures/histories/{:page:[0-9]+}', array('Architectures::histories'));
	Router::connect('/architectures/search/{:page:[0-9]+}', array('Architectures::search'));
	Router::connect('/architectures/delete/{:slug}', array('Architectures::delete'));
}

Router::connect('/exhibitions/{:file}.{:type}', array('Exhibitions::index'));
Router::connect('/exhibitions/pages/{:page:[0-9]+}', array('Exhibitions::index'));
Router::connect('/exhibitions/view/{:slug}', array('Exhibitions::view'));
Router::connect('/exhibitions/edit/{:slug}', array('Exhibitions::edit'));
Router::connect('/exhibitions/attachments/{:slug}', array('Exhibitions::attachments'));
Router::connect('/exhibitions/history/{:slug}', array('Exhibitions::history'));
Router::connect('/exhibitions/histories/{:page:[0-9]+}', array('Exhibitions::histories'));
Router::connect('/exhibitions/search/{:page:[0-9]+}', array('Exhibitions::search'));
Router::connect('/exhibitions/delete/{:slug}', array('Exhibitions::delete'));

Router::connect('/publications/pages/{:page:[0-9]+}', array('Publications::index'));
Router::connect('/publications/view/{:slug}', array('Publications::view'));
Router::connect('/publications/edit/{:slug}', array('Publications::edit'));
Router::connect('/publications/attachments/{:slug}', array('Publications::attachments'));
Router::connect('/publications/history/{:slug}', array('Publications::history'));
Router::connect('/publications/histories/{:page:[0-9]+}', array('Publications::histories'));
Router::connect('/publications/search/{:page:[0-9]+}', array('Publications::search'));
Router::connect('/publications/delete/{:slug}', array('Publications::delete'));

Router::connect('/documents/index.{:type}', array('Documents::index'));
Router::connect('/documents/pages/{:page:[0-9]+}', array('Documents::index'));
Router::connect('/documents/pages/{:page:[0-9]+}.{:type}', array('Documents::index'));
Router::connect('/documents/view/{:slug}', array('Documents::view'));
Router::connect('/documents/edit/{:slug}', array('Documents::edit'));
Router::connect('/documents/search/{:page:[0-9]+}', array('Documents::search'));
Router::connect('/documents/delete/{:slug}', array('Documents::delete'));

Router::connect('/archives_documents/delete/{:id}', array('ArchivesDocuments::delete'));

Router::connect('/files/view/{:slug}/{:file}', array('Files::view'));
Router::connect('/files/small/{:slug}/{:file}', array('Files::small'));
Router::connect('/files/thumb/{:slug}/{:file}', array('Files::thumb'));
Router::connect('/files/download/{:slug}/{:file}', array('Files::download'));

Router::connect('/files/secure/{:file}', array('Files::secure'));

Router::connect('/packages/add', array('Packages::add'));
Router::connect('/packages/delete/{:id}', array('Packages::delete'));

Router::connect('/links/pages/{:page:[0-9]+}', array('Links::index'));
Router::connect('/links/search/{:page:[0-9]+}', array('Links::search'));
Router::connect('/links/view/{:id}', array('Links::view'));
Router::connect('/links/edit/{:id}', array('Links::edit'));
Router::connect('/links/delete/{:id}', array('Links::delete'));

Router::connect('/archives_links/delete/{:id}', array('ArchivesLinks::delete'));

Router::connect('/notices/view/{:id}', array('Notices::view'));
Router::connect('/notices/edit/{:id}', array('Notices::edit'));
Router::connect('/notices/delete/{:id}', array('Notices::delete'));

Router::connect('/metrics/usage/{:file}.{:type}', array('Metrics::usage'));
Router::connect('/metrics/report/{:file}.{:type}', array('Metrics::report'));

//Router::connect('/{:controller}/{:action}/page:{:page:[0-9]+}');


/**
 * If you're using a document-oriented database, such as CouchDB or MongoDB, or another type of
 * database which uses 24-character hexidecimal values as primary keys, uncomment the routes below.
 */
// Router::connect('/{:controller}/{:action}/{:id:[0-9a-f]{24}}.{:type}', array('id' => null));
// Router::connect('/{:controller}/{:action}/{:id:[0-9a-f]{24}}');

/**
 * Finally, connect the default route. This route acts as a catch-all, intercepting requests in the
 * following forms:
 *
 * - `/foo/bar`: Routes to `FooController::bar()` with no parameters passed.
 * - `/foo/bar/param1/param2`: Routes to `FooController::bar('param1, 'param2')`.
 * - `/foo`: Routes to `FooController::index()`, since `'index'` is assumed to be the action if none
 *   is otherwise specified.
 *
 * In almost all cases, custom routes should be added above this one, since route-matching works in
 * a top-down fashion.
 */
Router::connect('/{:controller}/{:action}/{:args}');

}

/**
 * Redirect the user to a login if no other routes match
 */

Router::connect('/{:args}', array(), function($request) {
	$url = $request->url;
	return new Response(array(
		'headers' => array('location' => "/login?path=$url"),
	));
});

?>
