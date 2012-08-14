<?php

class ChangeWorksDateTypes extends Ruckusing_BaseMigration {

  public function up() {

	$this->change_column("works", "earliest_date", "date", array("null" => false));
	$this->change_column("works", "latest_date", "date", array("null" => false));

  }//up()

  public function down() {

	$this->change_column("works", "earliest_date", "datetime", array("null" => false));
	$this->change_column("works", "latest_date", "datetime", array("null" => false));

  }//down()
}
?>
