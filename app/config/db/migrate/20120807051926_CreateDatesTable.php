<?php

class CreateDatesTable extends Ruckusing_BaseMigration {

  public function up() {
		$t = $this->create_table("dates");
		
		$t->column("start", "datetime");
		$t->column("end", "datetime");
		
		$t->column("created", "datetime");
		$t->column("updated", "timestamp");
		$t->finish();

  }//up()

  public function down() {
		$this->drop_table("dates");

  }//down()
}
?>
