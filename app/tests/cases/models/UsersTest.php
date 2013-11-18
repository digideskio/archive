<?php

namespace app\tests\cases\models;

use app\models\Users;

use lithium\security\Password;

class UsersTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}
	
	public function testCreateUser() {
		$data = array(
			"username" => "test",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "email@example.com",
			"role_id" => '3'
		);
		$user = Users::create($data);
		
		$this->assertTrue($user->validates());
		$this->assertTrue($user->save($data));
		$this->assertEqual($user->username, "test");
		$this->assertEqual($user->name, "Full Name");
		$this->assertEqual($user->email, "email@example.com");
		
		$check = Password::check("abcd", $user->password);
		
		$this->assertTrue($check);
		
		$repeat_username_data = array(
			"username" => "test",
			"password" => "efgh",
			"name" => "Test Name",
			"email" => "test@example.com",
			"role_id" => '3'
		);
		$repeat_user = Users::create($data);

		$this->assertFalse($repeat_user->validates());
		
		$this->assertFalse($repeat_user->save($repeat_username_data));
		
		$user->delete();
	}
	
	public function testCreateUserWithNoUsername() {
		$user = Users::create(array(
			"username" => "",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "email@example.com",
			"role_id" => '3'
		));
		
		$this->assertFalse($user->validates());
		$this->assertFalse($user->save());
	}
	
	public function testCreateUserWithNoPassword() {
		$user = Users::create(array(
			"username" => "test",
			"password" => "",
			"name" => "Full Name",
			"email" => "email@example.com",
			"role_id" => '3'
		));
		
		$this->assertFalse($user->validates());
		$this->assertFalse($user->save());
	}
	
	public function testCreateUserWithNoName() {
		$user = Users::create(array(
			"username" => "test",
			"password" => "abcd",
			"name" => "",
			"email" => "email@example.com",
			"role_id" => '3'
		));
		
		$this->assertFalse($user->validates());
		$this->assertFalse($user->save());
	}
	
	public function testCreateUserWithNoEmail() {
		$user = Users::create(array(
			"username" => "test",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "",
			"role_id" => '3'
		));
		
		$this->assertFalse($user->validates());
		$this->assertFalse($user->save());
	}
	
	public function testCreateUserWithInvalidEmail() {
		$user = Users::create(array(
			"username" => "test",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "email"
		));
		
		$this->assertFalse($user->validates());
		$this->assertFalse($user->save());
	}

	public function testInitials() {
		
		$data = array (
			"username" => "user1",
			"name" => "User One"
		);

		$user = Users::create($data);
	
		$this->assertEqual('UO', $user->initials());

	}
}

?>
