<?php

class AddHeightWidthToDocuments extends Ruckusing_Migration_Base {

  public function up() {
  
  	$this->add_column("documents", "width", "integer");
  	$this->add_column("documents", "height", "integer");

  }//up()

  public function down() {
  
  	$this->remove_column("documents", "width");
  	$this->remove_column("documents", "height");

  }//down()
}
?>
