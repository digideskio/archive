<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\Authority;

use app\models\Users;

use lithium\security\Auth;
use lithium\storage\Session;

class AuthorityTest extends \lithium\test\Unit {
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

	public function testAuthority() {

		$users = $this->users;
		$admin = $users['admin'];
		$editor = $users['editor'];
		$viewer = $users['viewer'];
		$registrar = $users['registrar'];

		$helper = new Authority();

		Auth::set('default', $admin);
		$this->assertEqual('Admin', $helper->role());
		$this->assertTrue($helper->isAdmin());
		$this->assertTrue($helper->canEdit());
		$this->assertTrue($helper->matches('admin'));
		$this->assertFalse($helper->matches('editor'));
		$this->assertTrue($helper->canInventory());

		Auth::set('default', $editor);
		$this->assertFalse($helper->isAdmin());
		$this->assertTrue($helper->canEdit());
		$this->assertFalse($helper->canInventory());

		Auth::set('default', $viewer);
		$this->assertFalse($helper->isAdmin());
		$this->assertFalse($helper->canEdit());
		$this->assertFalse($helper->canInventory());

		Auth::set('default', $registrar);
		$this->assertFalse($helper->isAdmin());
		$this->assertTrue($helper->canEdit());
		$this->assertTrue($helper->canInventory());
	}

}

?>
