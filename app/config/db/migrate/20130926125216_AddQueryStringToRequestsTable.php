<?php

class AddQueryStringToRequestsTable extends Ruckusing_Migration_Base
{
    public function up()
    {
	
		$this->add_column("requests", "query_string", "string", array("limit" => 2048, "null" => false));

    }//up()

    public function down()
    {
	
		$this->remove_column("requests", "query_string");

    }//down()
}
