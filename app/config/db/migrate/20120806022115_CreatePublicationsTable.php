<?php

class CreatePublicationsTable extends Ruckusing_Migration_Base {

  public function up() {
		$t = $this->create_table("publications");

		$t->column("title", "string", array('limit' => 2048));
		$t->column("author", "string");
		$t->column("publisher", "string");
		$t->column("earliest_date", "date");
		$t->column("latest_date", "date");
		$t->column("pages", "string");
		$t->column("url", "string", array('limit' => 2048));
		$t->column("subject", "string", array('limit' => 2048));
		$t->column("remarks", "text");
		$t->column("language", "string");
		$t->column("interview", "integer", array('limit' => 1));
		$t->column("location", "string");
		$t->column("location_code", "string");
		$t->column("publication_number", "string");
		$t->column("slug", "string");
		$t->column("date_created", "datetime");
		$t->column("date_modified", "timestamp");
		$t->finish();

		$this->add_index("publications", "slug", array("unique" => true));

  }//up()

  public function down() {
		$this->drop_table("publications");
  }//down()
}
?>
