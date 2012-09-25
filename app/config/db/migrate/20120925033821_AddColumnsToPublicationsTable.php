<?php

class AddColumnsToPublicationsTable extends Ruckusing_BaseMigration {

  public function up() {

	$this->rename_column("publications", "location", "storage_location");
	$this->rename_column("publications", "location_code", "storage_number");

	$this->change_column("publications", "storage_location", "string", array("null" => false));
	$this->change_column("publications", "storage_number", "string", array("null" => false));

	$this->add_column("publications", "location", "string", array("null" => false));
	$this->add_column("publications", "type", "string", array("null" => false));
	$this->add_column("publications", "address", "string", array("null" => false));
	$this->add_column("publications", "format", "string", array("null" => false));
	$this->add_column("publications", "subject_date", "string", array("null" => false));

  }//up()

  public function down() {

  	$this->remove_column("publications", "location");
	$this->remove_column("publications", "type");
	$this->remove_column("publications", "address");
	$this->remove_column("publications", "format");
	$this->remove_column("publications", "subject_date");

	$this->rename_column("publications", "storage_location", "location");
	$this->rename_column("publications", "storage_number", "location_code");

	$this->change_column("publications", "location", "string", array("null" => false));
	$this->change_column("publications", "location_code", "string", array("null" => false));

  }//down()
}
?>
