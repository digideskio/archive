<?php

class CreateArchivesTable extends Ruckusing_Migration_Base {

  public function up() {

	$t = $this->create_table("archives", array("id" => false));

	$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "auto_increment" => true));
	$t->column("name", "string", array("limit" => 1024, "null" => false));
	$t->column("native_name", "string", array("limit" => 1024, "null" => false));
	$t->column("language_code", "string", array("limit" => 12, "null" => false));
	$t->column("controller", "string", array("null"=> false));
	$t->column("classification", "string", array("null"=> false));
	$t->column("type", "string", array("null"=> false));
	$t->column("catalog_level", "string", array("null"=> false));  
	$t->column("description", "text", array("null" => false));

	$t->column("slug", "string", array("null"=> false));  

	$t->column("earliest_date", "date", array("null" => false));
	$t->column("latest_date", "date", array("null" => false));
	$t->column("earliest_date_format", "string" , array("limit" => 5, "null" => false));
	$t->column("latest_date_format", "string", array("limit" => 5, "null" => false));

	$t->column("date_created", "datetime", array("null" => false));
	$t->column("date_modified", "timestamp", array("null" => false));

	$t->column("user_id", "integer", array("unsigned" => true));
	$t->column("parent_id", "integer", array("unsigned" => true));

	$t->finish();

  }//up()

  public function down() {

  	$this->drop_table("archives");

  }//down()
}
?>
