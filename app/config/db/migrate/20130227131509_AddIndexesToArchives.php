<?php

class AddIndexesToArchives extends Ruckusing_BaseMigration {

  public function up() {

	$this->change_column("archives_histories", "archive_id", "integer", array("unsigned" => true, "null" => false));

	$this->add_index("archives", "slug", array("unique" => true));
	$this->add_index("archives", "user_id");
	$this->add_index("archives_histories", "archive_id");
	$this->add_index("archives_histories", "user_id");

  }//up()

  public function down() {

	$this->change_column("archives_histories", "archive_id", "integer", array("unsigned" => true));

	$this->remove_index("archives", "slug", array("unique" => true));
	$this->remove_index("archives", "user_id");
	$this->remove_index("archives_histories", "archive_id");
	$this->remove_index("archives_histories", "user_id");


  }//down()
}
?>
