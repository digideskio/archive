<?php

class CreateLinksTable extends Ruckusing_Migration_Base {

  public function up() {

	$t = $this->create_table("links");

	$t->column("title", "string", array("limit" => 2048, "null" => false));
	$t->column("url", "string", array("limit" => 2048, "null" => false));
	$t->column("description", "text", array("null" => false));

	$t->column("date_created", "datetime", array("null" => false));
	$t->column("date_modified", "timestamp", array("null" => false));

	$t->finish();

  }//up()

  public function down() {

	$this->drop_table("links");

  }//down()
}
?>
