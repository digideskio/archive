<?php

class AddDescriptionToRoles extends Ruckusing_Migration_Base
{
    public function up()
    {
		$this->add_column("roles", "description", "string", array("limit" => 1024, "null" => false));

        $roles = array(
            array('name' => 'Admin', 'description' => 'manage users and all aspects of the archive'),
            array('name' => 'Editor', 'description' => 'view and edit basic archive data'),
            array('name' => 'Viewer', 'description' => 'view basic archive data')
        );

        foreach ($roles as $role) {
            $name = $role['name'];
            $desc = $role['description'];

            $this->execute("UPDATE roles SET description = '$desc' WHERE name = '$name'");
        }

    }//up()

    public function down()
    {
		$this->remove_column("roles", "description");
    }//down()
}
?>
