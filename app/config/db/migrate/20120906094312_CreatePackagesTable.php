<?php

class CreatePackagesTable extends Ruckusing_Migration_Base {

	public function up() {

		$t = $this->create_table("packages");

		$t->column("collection_id", "integer", array(
			"unsigned" => true, 
			"null" => false
		));

		$t->column("name", "string", array("null" => false));
		$t->column("filesystem", "string", array("null" => false));

		$t->column("date_created", "datetime", array("null" => false));
		$t->column("date_modified", "datetime", array("null" => false));

		$t->finish();

	}//up()

	public function down() {
		$this->drop_table("packages");
	}//down()
}
?>
