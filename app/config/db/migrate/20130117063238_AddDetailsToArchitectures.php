<?php

class AddDetailsToArchitectures extends Ruckusing_BaseMigration {

  public function up() {

  	$this->add_column("architectures", "architects", "string", array("null" => false));
	$this->add_column("architectures", "consultants", "string", array("null" => false));
	$this->add_column("architectures", "materials", "string", array("null" => false));
	$this->add_column("architectures", "area", "float", array("null" => false));
	$this->add_column("architectures", "annotation", "text", array("null" => false));

  }//up()

  public function down() {

  	$this->remove_column("architectures", "architects");
	$this->remove_column("architectures", "consultants");
	$this->remove_column("architectures", "materials");
	$this->remove_column("architectures", "area");
	$this->remove_column("architectures", "annotation");

  }//down()
}
?>
