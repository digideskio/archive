<?php

class AddRegistrarRole extends Ruckusing_Migration_Base
{
    public function up()
    {
        $role = 'Registrar';
        $desc = "maintain records of ownership and lending history";
        $this->execute("INSERT INTO roles (id, name, description) VALUES (4, '$role', '$desc')");
    }//up()

    public function down()
    {
        $role = 'Registrar';
        $this->execute("DELETE FROM roles WHERE name = '$role'");
    }//down()
}
?>
