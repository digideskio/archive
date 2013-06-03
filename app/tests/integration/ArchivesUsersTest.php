<?php

namespace app\tests\integration;

use app\models\Users;
use app\models\Archives;

use lithium\security\Auth;
use lithium\storage\Session;

class ArchivesUsersTest extends \lithium\test\Integration {

	public $users = array(
		'editor' => array(
			"username" => "editor",
			"password" => "abcd",
			"name" => "Full Name",
			"email" => "editor@example.com",
			"role_id" => '2'
		),
	);

	public function setUp() {
	
		$users = $this->users;

		Session::config(array(
			'default' => array('adapter' => 'Php', 'session.name' => 'app')
		));
	
		Auth::clear('default');
	
		$editor = Users::create();
		$editor->save($users['editor']);
	
	}

	public function tearDown() {
	
		Users::all()->delete();
		Auth::clear('default');
	
	}

	public function testArchiveSaveUserId() {

		$users = $this->users;

		Auth::set('default', $users['editor']);

		$data = array(
			'title' => 'Test With User'
		);

		$archive = Archives::create();
		$archive->save($data);

		$user = Users::first();

		$this->assertEqual($user->id, $archive->user_id);

	}

}

?>
