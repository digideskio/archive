<?php

class AddAnnotationToWorks extends Ruckusing_BaseMigration {

  public function up() {
		
		$this->add_column("works", "annotation", "text");

  }//up()

  public function down() {
		
		$this->remove_column("works", "annotation");

  }//down()
}
?>
