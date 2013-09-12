<?php

class CreateCollectionsWorksTable extends Ruckusing_Migration_Base {

  public function up() {
		$t = $this->create_table("collections_works");

		$t->column("collection_id", "integer", array(
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
	$this->drop_table("collections_works");
  }//down()
}
?>
