<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2012, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\controllers;

use app\models\Notices;
use app\models\Archives;
use app\models\Documents;

use lithium\security\Auth;

/**
 * This controller is used for serving static pages by name, which are located in the `/views/pages`
 * folder.
 *
 * A Lithium application's default routing provides for automatically routing and rendering
 * static pages using this controller. The default route (`/`) will render the `home` template, as
 * specified in the `view()` action.
 *
 * Additionally, any other static templates in `/views/pages` can be called by name in the URL. For
 * example, browsing to `/pages/about` will render `/views/pages/about.html.php`, if it exists.
 *
 * Templates can be nested within directories as well, which will automatically be accounted for.
 * For example, browsing to `/pages/about/company` will render
 * `/views/pages/about/company.html.php`.
 */
class PagesController extends \lithium\action\Controller {

	public $rules = array(
		'home' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'view' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
	);

	public function view() {

		$options = array();
		$path = func_get_args();

		if (!$path || $path === array('home')) {
			$path = array('home');
			$options['compiler'] = array('fallback' => true);
		}

		$options['template'] = join('/', $path);
		return $this->render($options);
	}

	public function home() {

		$path = 'alerts';

		$conditions = compact('path');
		$order = array('date_created' => 'DESC');

		$alerts = Notices::find('all', array(
			'conditions' => $conditions,
			'order' => $order,
			'limit' => 1
		));

		$updates = Notices::find('all', array(
			'conditions' => array('path' => 'updates'),
			'order' => $order,
			'limit' => 5
		));

		$works = Archives::find('all', array(
			'with' => 'Users',
			'conditions' => array('controller' => 'works'),
			'order' => $order,
			'limit' => 10,
		));

		$exhibitions = Archives::find('all', array(
			'with' => 'Users',
			'conditions' => array('controller' => 'exhibitions'),
			'order' => $order,
			'limit' => 10,
		));

		$publications = Archives::find('all', array(
			'with' => 'Users',
			'conditions' => array('controller' => 'publications'),
			'order' => $order,
			'limit' => 10,
		));

        $documents = Documents::find('all', array(
			'with' => array('Formats'),
			'limit' => 10,
			'order' => $order,
		));

		return compact('alerts', 'updates', 'works', 'exhibitions', 'publications', 'documents');

	}

	public function blank() {
		return $this->render(array('layout' => ''));
	}
}

?>
