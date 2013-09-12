<?php

class CreateArchitecturesDocumentsTable extends Ruckusing_Migration_Base {

  public function up() {
	$t = $this->create_table("architectures_documents");
		
      $t->column("architecture_id", "integer", array(
			"unsigned" => true, 
			"null" => false
		));
      $t->column("document_id", "integer", array(
			"unsigned" => true, 
			"null" => false
		));
      $t->finish();

  }//up()

  public function down() {
	$this->drop_table("architectures_documents");
  }//down()
}
?>
