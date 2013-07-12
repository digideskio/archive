<?php

class TrackUserWithDocuments extends Ruckusing_BaseMigration {

  public function up() {

  	$this->add_column("documents", "user_id", "integer", array("unsigned" => true, "null" => false));

  }//up()

  public function down() {

  	$this->remove_column("documents", "user_id");

  }//down()
}
?>
