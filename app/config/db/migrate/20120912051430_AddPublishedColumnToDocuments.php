<?php

class AddPublishedColumnToDocuments extends Ruckusing_Migration_Base {

  public function up() {

  		$this->add_column("documents", "published", "integer", array("limit" => 1, "null" => false));

  }//up()

  public function down() {

  		$this->remove_column("documents", "published");

  }//down()
}
?>
