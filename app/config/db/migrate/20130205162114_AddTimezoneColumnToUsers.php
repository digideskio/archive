<?php

class AddTimezoneColumnToUsers extends Ruckusing_BaseMigration {

  public function up() {

  	$this->add_column("users", "timezone_id", "string", array("null" => false));

  }//up()

  public function down() {

  	$this->remove_column("users", "timezone_id");

  }//down()
}
?>
