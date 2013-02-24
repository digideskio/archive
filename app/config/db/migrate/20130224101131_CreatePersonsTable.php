<?php

class CreatePersonsTable extends Ruckusing_BaseMigration {

  public function up() {

	$t = $this->create_table("persons", array("id" => false));

	$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "auto_increment" => true));
	$t->column("family_name", "string", array("null" => false));
	$t->column("given_name", "string", array("null" => false));
	$t->column("native_family_name", "string", array("null" => false));
	$t->column("native_given_name", "string", array("null" => false));
	$t->column("sex", "string", array("limit" => 20, "null" => false));
	$t->column("nationality", "string", array("null" => false));
	$t->column("biography", "text", array("null" => false));
	$t->column("remarks", "text", array("null" => false));
	$t->column("roles", "string", array("limit" => 128, "null" => false));
	$t->column("email", "string", array("limit" => 128, "null" => false));
	$t->column("address", "string", array("null" => false));
	$t->column("phone", "string", array("null" => false));

	$t->finish();

  }//up()

  public function down() {

  	$this->drop_table("persons");

  }//down()
}
?>
