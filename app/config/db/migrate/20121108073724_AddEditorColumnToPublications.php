<?php

class AddEditorColumnToPublications extends Ruckusing_Migration_Base {

  public function up() {

	$this->add_column("publications", "editor", "string", array("null" => FALSE));

  }//up()

  public function down() {

  	$this->remove_column("publications", "editor");

  }//down()
}
?>
