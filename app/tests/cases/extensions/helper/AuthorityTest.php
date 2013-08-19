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

		$helper = new Authority();

		Auth::set('default', $admin);
		$this->assertEqual('Admin', $helper->role()); 
		$this->assertTrue($helper->canAdmin());
		$this->assertTrue($helper->canEdit());

		Auth::set('default', $editor);
		$this->assertFalse($helper->canAdmin());
		$this->assertTrue($helper->canEdit());

		Auth::set('default', $viewer);
		$this->assertFalse($helper->canAdmin());
		$this->assertFalse($helper->canEdit());
	}

}

?>
