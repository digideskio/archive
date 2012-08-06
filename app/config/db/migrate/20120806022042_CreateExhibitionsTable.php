<?php

class CreateExhibitionsTable extends Ruckusing_BaseMigration {

  public function up() {
		$t = $this->create_table("exhibitions");
		
		$t->column("title", "string");
		$t->column("curator", "string");
		$t->column("venue", "string");
		$t->column("city", "string");
		$t->column("country", "string");
		$t->column("remarks", "text");
		$t->column("earliest_date", "datetime");
		$t->column("latest_date", "datetime");
		$t->column("type", "string");
		$t->column("slug", "string");
		$t->finish();

		$this->add_index("exhibitions", "slug", array("unique" => true));

  }//up()

  public function down() {
      $this->drop_table("documents");
  }//down()
}
?>
