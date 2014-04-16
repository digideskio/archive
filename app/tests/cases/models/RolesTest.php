<?php

namespace app\tests\cases\models;

use app\models\Roles;

class RolesTest extends \lithium\test\Unit {

	public function setUp() {}

	public function tearDown() {}

	public function testDefaultRoles() {

		$role_names = array('Admin', 'Editor', 'Viewer', 'Registrar');

		$roles = Roles::all();

		$sizeof_rolenames = sizeof($role_names);
		$sizeof_roles = sizeof($roles);

		$this->assertEqual($sizeof_rolenames, $sizeof_roles, "The database does not contain the expected number of roles.\nexpected: $sizeof_rolenames \nresult: $sizeof_roles");

		foreach($roles as $role) {
			$role_name = $role->name;
			$this->assertTrue(in_array($role_name, $role_names), "The $role_name role exists in the Roles table but is not listed in the allowed roles: " . implode(', ', $role_names));
		}

	}

}

?>
