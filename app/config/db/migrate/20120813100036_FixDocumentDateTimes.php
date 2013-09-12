<?php

class FixDocumentDateTimes extends Ruckusing_Migration_Base {

  public function up() {
  		
  		$this->change_column("documents", "file_date", "datetime", array("null" => false));

  }//up()

  public function down() {

  }//down()
}
?>
