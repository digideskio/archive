<?php

class CreatePublicationsDocuments extends Ruckusing_BaseMigration {

  public function up() {

	$t = $this->create_table("publications_documents");

	$t->column("publication_id", "integer", array(
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
	$this->drop_table("publications_documents");
  }//down()
}
?>
