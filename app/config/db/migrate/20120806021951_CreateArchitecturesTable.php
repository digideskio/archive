<?php

class CreateArchitecturesTable extends Ruckusing_Migration_Base {

  public function up() {
      $t = $this->create_table("architectures");
      
      $t->column("title", "string");
      $t->column("client", "string");
      $t->column("project_lead", "string");
      $t->column("status", "string");
      $t->column("location", "string");
      $t->column("city", "string");
      $t->column("country", "string");
      $t->column("remarks", "text");
      $t->column("earliest_date", "date");
      $t->column("latest_date", "date");
      $t->column("slug", "string");
      $t->column("date_created", "datetime");
      $t->column("date_modified", "datetime");
      $t->finish();
      
      $this->add_index("architectures", "slug", array("unique" => true));
      

  }//up()

  public function down() {
      $this->drop_table("architectures");
  }//down()
}
?>
