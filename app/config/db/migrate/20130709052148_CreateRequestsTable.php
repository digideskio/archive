<?php

class CreateRequestsTable extends Ruckusing_Migration_Base {

  public function up() {

		$t = $this->create_table('requests', array('id' => false));

		$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "auto_increment" => true));

		$t->column("url", "string", array("limit" => 2048, "null" => false));
		$t->column("controller", "string", array("null" => false));
		$t->column("action", "string", array("null" => false));
		$t->column("identifier", "string", array("null" => false));
		$t->column("referer", "string", array("limit" => 2048, "null" => false));
		$t->column("user_agent", "string", array("limit" => 2048, "null" => false));
		$t->column("request_method", "string", array("null" => false));
		$t->column("remote_addr", "string", array("null" => false));
		$t->column("request_time", "integer", array("unsigned" => true, "null" => false));
		$t->column("user_id", "integer", array("unsigned" => true, "null" => false));

		$t->finish();

  }//up()

  public function down() {

  		$this->drop_table('requests');

  }//down()
}
?>
