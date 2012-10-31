<?php

class AddNoticesTable extends Ruckusing_BaseMigration {

  public function up() {

	$t = $this->create_table("notices");

	$t->column("path", "string", array("null" => false));
	$t->column("subject", "string", array("null" => false));
	$t->column("body", "text", array("null" => false));

	$t->column("date_created", "datetime", array("null" => false));
	$t->column("date_modified", "timestamp", array("null" => false));
	
	$t->finish();

  }//up()

  public function down() {

	$this->drop_table("notices");

  }//down()
}
?>
