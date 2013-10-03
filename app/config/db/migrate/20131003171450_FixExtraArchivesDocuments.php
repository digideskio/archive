<?php

class FixExtraArchivesDocuments extends Ruckusing_Migration_Base
{
    public function up()
    {
		$this->execute("DELETE FROM archives_documents WHERE archive_id IS NULL");
    }//up()

    public function down()
    {
    }//down()
}
