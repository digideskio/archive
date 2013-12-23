<?php

class CreateWorksArtistsView extends Ruckusing_Migration_Base
{
    public function up()
    {
		$this->query("CREATE VIEW view_works_artists AS SELECT components.archive_id2 AS work_id, GROUP_CONCAT(IF (CONCAT(persons.family_name, archives.name) = '', NULL, CONCAT(persons.family_name, archives.name)) ORDER BY persons.family_name ASC SEPARATOR ', ') AS artist_sort FROM components LEFT JOIN archives ON components.archive_id1 = archives.id LEFT JOIN persons ON components.archive_id1 = persons.id WHERE components.type = 'persons_works' AND ROLE = 'artist' GROUP BY components.archive_id2");

    }//up()

    public function down()
    {
		$this->query("DROP VIEW view_works_artists");
    }//down()
}
