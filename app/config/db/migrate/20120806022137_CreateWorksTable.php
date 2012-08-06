<?php

class CreateWorksTable extends Ruckusing_BaseMigration {

  public function up() {
		$t = $this->create_table("works");
      
		$t->column("artist", "string");
		$t->column("title", "string");
		$t->column("classification", "string");
		$t->column("materials", "text");
		$t->column("quantity", "string");
		$t->column("location", "string");
		$t->column("lender", "string");
		$t->column("remarks", "text");
		$t->column("earliest_date", "datetime");
		$t->column("latest_date", "datetime");
		$t->column("creation_number", "string");
		$t->column("height", "float");
		$t->column("width", "float");
		$t->column("depth", "float");
		$t->column("diameter", "float");
		$t->column("weight", "float");
		$t->column("running_time", "string");
		$t->column("measurement_remarks", "text");
		$t->column("slug", "string");
		$t->column("date_created", "datetime");
		$t->column("date_modified", "timestamp");
		$t->finish();

		$this->add_index("works", "slug", array("unique" => true));

  }//up()

  public function down() {
      $this->drop_table("works");
  }//down()
}
?>
