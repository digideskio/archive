<?php

class FixDocumentsDateCreated extends Ruckusing_Migration_Base
{
    public function up()
    {
		$this->execute("UPDATE documents SET date_created = date_modified WHERE date_created = '0000-00-00 00:00:00'");
    }//up()

    public function down()
    {
    }//down()
}
