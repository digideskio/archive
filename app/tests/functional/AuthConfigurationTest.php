<?php 

namespace app\tests\functional;

use app\models\Users;
use app\models\Roles;

use lithium\security\Auth;
use lithium\storage\Session;
use lithium\action\Request;

class AuthConfigurationTest extends \lithium\test\Integration {

	public $users = array(
		'admin' => array(
			"username" => "admin",
			"password" => "abcd",
			"name" => "Full Name",
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

	public function testDefaultConfig() {

		$users = $this->users;
		$username = 'admin';

		$this->request = new Request();
		$this->request->data = $users[$username]; 

		$check = Auth::check('default', $this->request);

		$this->assertFalse(isset($check['password']));

		$this->assertTrue(isset($check['id']));

		$this->assertEqual($check['username'], $users[$username]['username']);

		$this->assertEqual($check['role_id'], $users[$username]['role_id']);

		$role = Roles::first($users['admin']['role_id']);

		$this->assertEqual($check['role']['name'], $role->name);

	}
}

?>
