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
	);

	public function setUp() {
	
		$users = $this->users;

		Session::config(array(
			'default' => array('adapter' => 'Php', 'session.name' => 'app')
		));
	
		Auth::clear('default');
	
		$admin = Users::create();
		$admin->save($users['admin']);
	
	}

	public function tearDown() {
	
		Users::all()->delete();
		Auth::clear('default');
	
	}

	public function testRole() {
	
		$users = $this->users;
		$user = $users['admin'];
		Auth::set('default', $user);
		
		$helper = new Authority();

		$this->assertEqual('Admin', $helper->role()); 
	}
}

?>
