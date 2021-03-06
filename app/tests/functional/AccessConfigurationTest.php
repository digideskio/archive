<?php

namespace app\tests\functional;

use app\controllers\UsersController;
use app\controllers\WorksController;

use app\models\Users;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

use li3_access\security\Access;

class AccessConfigurationTest extends \lithium\test\Integration {

	public $users = array(
		'admin' => array(
			"username" => "admin",
			"password" => "abcd",
			"name" => "an Admin User",
			"email" => "admin@example.com",
			"role_id" => '1'
		),
		'editor' => array(
			"username" => "editor",
			"password" => "abcd",
			"name" => "an Editor User",
			"email" => "editor@example.com",
			"role_id" => '2'
		),
		'viewer' => array(
			"username" => "viewer",
			"password" => "abcd",
			"name" => "A Viewer User",
			"email" => "viewer@example.com",
			"role_id" => '3'
		),
		'registrar' => array(
			"username" => "registrar",
			"password" => "abcd",
			"name" => "A Registrar User",
			"email" => "registrar@example.com",
			"role_id" => '4'
		),
	);

	public $test_rules = array(
		array('rule' => 'allowAdminUser', 'user' => 'admin', 'access' => true),
		array('rule' => 'allowAdminUser', 'user' => 'editor', 'access' => false),
		array('rule' => 'allowAdminUser', 'user' => 'viewer', 'access' => false),
		array('rule' => 'allowAdminUser', 'user' => 'registrar', 'access' => false),

		array('rule' => 'allowRegistrarUser', 'user' => 'admin', 'access' => true),
		array('rule' => 'allowRegistrarUser', 'user' => 'editor', 'access' => false),
		array('rule' => 'allowRegistrarUser', 'user' => 'viewer', 'access' => false),
		array('rule' => 'allowRegistrarUser', 'user' => 'registrar', 'access' => true),

		array('rule' => 'allowEditorUser', 'user' => 'admin', 'access' => true),
		array('rule' => 'allowEditorUser', 'user' => 'editor', 'access' => true),
		array('rule' => 'allowEditorUser', 'user' => 'viewer', 'access' => false),
		array('rule' => 'allowEditorUser', 'user' => 'registrar', 'access' => true),

		array('rule' => 'allowAdminOrUserRequestingSelf', 'user' => 'admin', 'params' => array('username' => 'editor'), 'access' => true),
		array('rule' => 'allowAdminOrUserRequestingSelf', 'user' => 'editor', 'params' => array('username' => 'editor'), 'access' => true),
		array('rule' => 'allowAdminOrUserRequestingSelf', 'user' => 'editor', 'params' => array('username' => 'viewer'), 'access' => false),
		array('rule' => 'allowAdminOrUserRequestingSelf', 'user' => 'viewer', 'params' => array('username' => 'viewer'), 'access' => true),
		array('rule' => 'allowAdminOrUserRequestingSelf', 'user' => 'registrar', 'params' => array('username' => 'registrar'), 'access' => true),

		array('rule' => 'denyUserRequestingSelf', 'user' => 'admin', 'params' => array('username' => 'admin'), 'access' => false),
		array('rule' => 'denyUserRequestingSelf', 'user' => 'admin', 'params' => array('username' => 'editor'), 'access' => true),
	);

	public function setUp() {

		$users = $this->users;

		Session::config(array(
			'default' => array('adapter' => 'Php', 'session.name' => 'app')
		));

		Auth::clear('default');

		$admin = Users::create();
		$admin->save($users['admin']);

		$editor = Users::create();
		$editor->save($users['editor']);

		$viewer = Users::create();
		$viewer->save($users['viewer']);

		$registrar = Users::create();
		$registrar->save($users['registrar']);

	}

	public function tearDown() {

		Users::all()->delete();
		Auth::clear('default');

	}

	public function testRules() {

		$users = $this->users;
		$test_rules = $this->test_rules;

		foreach ($test_rules as $test_rule) {

			$rules = array(
				array('rule' => $test_rule['rule'])
			);

			$user = $users[$test_rule['user']];

			Auth::set('default', $user);

			$check = Auth::check('default');

			$request = new Request();
			$request->params = isset($test_rule['params']) ? $test_rule['params'] : array();

	    	$access = Access::check('rule_based', $check, $request, array('rules' => $rules));

			$this->assertEqual($test_rule['access'], empty($access), "The access rule {$test_rule['rule']} is not returning the expected result for {$user['name']}.");

		}

	}

}

?>
