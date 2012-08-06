<?php

class CreateDocumentsTable extends Ruckusing_BaseMigration {

  public function up() {
		$t = $this->create_table("documents");

		$t->column("title", "string");
		$t->column("hash", "string", array('limit' => 32));
		$t->column("repository", "string");
		$t->column("format_id", "integer", array(
			"unsigned" => true, 
			"null" => false
		));
		$t->column("file_date", "date");
		$t->column("credit", "string");
		$t->column("remarks", "text");
		$t->column("slug", "string");
		$t->column("date_created", "datetime");
		$t->column("date_modified", "timestamp");
		$t->finish();

		$this->add_index("documents", "slug", array("unique" => true));
		

  }//up()

  public function down() {
      $this->drop_table("documents");
  }//down()
}
?>
