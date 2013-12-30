<?php

class AddIndexesToComponents extends Ruckusing_Migration_Base
{
    public function up()
    {
		$this->query("ALTER TABLE components ADD INDEX (archive_id1)");
		$this->query("ALTER TABLE components ADD INDEX (archive_id2)");
    }//up()

    public function down()
    {
		$this->query("ALTER TABLE components DROP INDEX (archive_id1)");
		$this->query("ALTER TABLE components DROP INDEX (archive_id2)");
    }//down()
}
