<?php

class CreateLinksRelationshipTables extends Ruckusing_BaseMigration {

  public function up() {

	$models = array(
		"architecture",
		"collection",
		"document",
		"exhibition",
		"publication",
		"work"
	);

	foreach ($models as $model) {

		$t = $this->create_table($model . "s_links");

		$t->column($model . "_id", "integer", array(
			"unsigned" => true,
			"null" => false
		));

		$t->column("link_id", "integer", array(
			"unsigned" => true,
			"null" => false
		));

		$t->finish();

	}

  }//up()

  public function down() {
	
	$models = array(
		"architecture",
		"collection",
		"document",
		"exhibition",
		"publication",
		"work"
	);

	foreach ($models as $model) {
		$this->drop_table($model . "s_links");
	}

  }//down()
}
?>
