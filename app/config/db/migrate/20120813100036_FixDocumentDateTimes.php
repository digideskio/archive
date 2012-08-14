<?php

class FixDocumentDateTimes extends Ruckusing_BaseMigration {

  public function up() {
  		
  		$this->change_column("documents", "file_date", "datetime", array("null" => false));

  }//up()

  public function down() {

  }//down()
}
?>
