<?php

class CreateUsersTable extends Ruckusing_BaseMigration {

  public function up() {
		$t = $this->create_table("users");

		$t->column("username", "string");
		$t->column("name", "string");
		$t->column("email", "string", array("limit" => 255));
		$t->column("password", "string");
		$t->column("role_id", "integer", array(
			"unsigned" => true, 
			"null" => false
		));
		$t->finish();

		$this->add_index("users", "username", array("unique" => true));
      

  }//up()

  public function down() {
		$this->drop_table("users");
  }//down()
}
?>
