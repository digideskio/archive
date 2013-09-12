<?php

class CreateExhibitionsDocumentsTable extends Ruckusing_Migration_Base {

  public function up() {
	
	$t = $this->create_table("exhibitions_documents");

	$t->column("exhibition_id", "integer", array(
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
	$this->drop_table("exhibitions_documents");
  }//down()
}
?>
