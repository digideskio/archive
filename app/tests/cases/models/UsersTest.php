<?php

namespace app\tests\cases\models;

use app\models\Users;
use app\tests\mocks\data\MockUsers;

use lithium\security\Password;

class UsersTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}
	
	public function testCreateUser() {
		$user = Users::create();
		$data = array(
			"username" => "test",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "email@example.com"
		);
		
		
		$this->assertTrue($user->save($data));
		$this->assertEqual($user->username, "test");
		$this->assertEqual($user->name, "Full Name");
		$this->assertEqual($user->email, "email@example.com");
		
		$check = Password::check("abcd", $user->password);
		
		$this->assertTrue($check);
		
		$user->delete();
	}
	
	public function testCreateUserWithNoUsername() {
		$user = Users::create(array(
			"username" => "",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "email@example.com"
		));
		
		$this->assertFalse($user->save());
	}
	
	public function testCreateUserWithNoPassword() {
		$user = Users::create(array(
			"username" => "test",
			"password" => "",
			"name" => "Full Name",
			"email" => "email@example.com"
		));
		
		$this->assertFalse($user->save());
	}
	
	public function testCreateUserWithNoName() {
		$user = Users::create(array(
			"username" => "test",
			"password" => "abcd",
			"name" => "",
			"email" => "email@example.com"
		));
		
		$this->assertFalse($user->save());
	}
	
	public function testCreateUserWithNoEmail() {
		$user = Users::create(array(
			"username" => "test",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => ""
		));
		
		$this->assertFalse($user->save());
	}
	
	public function testCreateUserWithInvalidEmail() {
		$user = Users::create(array(
			"username" => "test",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "email"
		));
		
		$this->assertFalse($user->save());
	}
	
	public function testFindUser() {
	
		$user = MockUsers::find('first');
		
		$this->assertEqual($user['username'], "user1");
	
	}


}

?>
