<?php

class DropCollectionsLinksTable extends Ruckusing_BaseMigration {

  public function up() {

  	$this->drop_table('collections_links');

  }//up()

  public function down() {

	$t = $this->create_table("collections_links");

	$t->column("collection_id", "integer", array(
		"unsigned" => true,
		"null" => false
	));

	$t->column("link_id", "integer", array(
		"unsigned" => true,
		"null" => false
	));

	$t->finish();

  }//down()
}
?>
