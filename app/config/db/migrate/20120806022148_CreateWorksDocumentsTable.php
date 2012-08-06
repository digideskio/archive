<?php

class CreateWorksDocumentsTable extends Ruckusing_BaseMigration {

  public function up() {
		$t = $this->create_table("works_documents");

		$t->column("work_id", "integer", array(
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
	$this->drop_table("works_documents");
  }//down()
}
?>
