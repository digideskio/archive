<?php

class CreateRolesTable extends Ruckusing_BaseMigration {

  public function up() {
		$t = $this->create_table("roles");

		$t->column("name", "string");
		$t->finish();
		
		$this->execute("INSERT INTO roles (id, name) VALUES (1,'Admin'),(2,'Editor'),(3,'Viewer')");

  }//up()

  public function down() {
		$this->drop_table("roles");
  }//down()
}
?>
