<?php

class AddActiveColumnToUsers extends Ruckusing_Migration_Base
{
    public function up()
    {
		$this->add_column("users", "active", "boolean", array("null" => false));
    }//up()

    public function down()
    {
		$this->remove_column("users", "active");
    }//down()
}
