<?php

class DropDatesTable extends Ruckusing_Migration_Base {

  public function up() {
		$this->drop_table("dates");
  }//up()

  public function down() {
		$t = $this->create_table("dates");
		
		$t->column("start", "datetime");
		$t->column("end", "datetime");
		
		$t->column("created", "datetime");
		$t->column("updated", "timestamp");
		$t->finish();
  }//down()
}
?>
