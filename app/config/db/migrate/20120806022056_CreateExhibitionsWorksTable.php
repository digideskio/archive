<?php

class CreateExhibitionsWorksTable extends Ruckusing_Migration_Base {

  public function up() {
		$t = $this->create_table("exhibitions_works");

		$t->column("exhibition_id", "integer", array(
			"unsigned" => true, 
			"null" => false
		));
		$t->column("work_id", "integer", array(
			"unsigned" => true, 
			"null" => false
		));
		$t->finish();

  }//up()

  public function down() {
      $this->drop_table("exhibitions_works");
  }//down()
}
?>
