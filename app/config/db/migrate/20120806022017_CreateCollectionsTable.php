<?php

class CreateCollectionsTable extends Ruckusing_BaseMigration {

  public function up() {
      $t = $this->create_table("collections");
      
      $t->column("title", "string");
      $t->column("description", "text");
      $t->column("slug", "string");
      $t->column("date_created", "datetime");
      $t->column("date_modified", "timestamp");
      $t->finish();
      
      $this->add_index("collections", "slug", array("unique" => true));
	
  }//up()

  public function down() {
	$this->drop_table("collections");
  }//down()
}
?>
