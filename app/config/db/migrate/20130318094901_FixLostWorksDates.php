<?php

class FixLostWorksDates extends Ruckusing_BaseMigration {

  public function up() {

  	/* There was a bug in the Works edit form. Saving a new Annotation would 
	   lose the earliest_date and latest_date fields. This migration file
	   will attempt to restore the lost values. It is advisable to double-
	   check the final values manually.
	*/

	//Drop our triggers
	$this->execute("DROP TRIGGER ArchivesHistoriesTableInsert");
	$this->execute("DROP TRIGGER ArchivesHistoriesTableDelete");
	$this->execute("DROP TRIGGER ArchivesHistoriesTableUpdate");

	//Find all the records that are affected
	$archives = $this->select_all("SELECT archives.id FROM archives LEFT JOIN works ON archives.id = works.id WHERE controller = 'works' AND (annotation != '' OR EXISTS (select id FROM works_histories WHERE work_id = archives.id AND annotation != '')) AND earliest_date = '0000-00-00' AND EXISTS (SELECT id FROM archives_histories WHERE archive_id = archives.id AND earliest_date != '0000-00-00') AND date_modified > '2013-02-27 00:00:00'");

	if ($archives) {

		foreach ($archives as $row) {
			$id = $row['id'];
			
			//Find the correct earliest_date
			$archive_history = $this->select_one("SELECT earliest_date, start_date FROM archives_histories WHERE archive_id = '$id' AND earliest_date != '0000-00-00' ORDER BY start_date DESC limit 1 ");

			if($archive_history) {
				
				$earliest_date = $archive_history['earliest_date'];
				$start_date = $archive_history['start_date'];

				$this->execute("UPDATE archives_histories SET earliest_date = '$earliest_date' WHERE archive_id = '$id' AND start_date > '$start_date'");
				$this->execute("UPDATE archives SET earliest_date = '$earliest_date' WHERE id = '$id'");

			}

			//Find the correct latest_date
			$archive_history = $this->select_one("SELECT latest_date, start_date FROM archives_histories WHERE archive_id = '$id' AND latest_date != '0000-00-00' ORDER BY start_date DESC limit 1 ");

			if($archive_history) {
				
				$latest_date = $archive_history['latest_date'];
				$start_date = $archive_history['start_date'];

				$this->execute("UPDATE archives_histories SET latest_date = '$latest_date' WHERE archive_id = '$id' AND start_date > '$start_date'");
				$this->execute("UPDATE archives SET latest_date = '$latest_date' WHERE id = '$id'");

			}


		}
	
	}

	//Finally, re-create the triggers for Works Histories
	$this->execute("CREATE TRIGGER ArchivesHistoriesTableInsert AFTER INSERT ON archives FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO archives_histories (archive_id, name, native_name, language_code, controller, classification, type, catalog_level, description, slug, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified, user_id, parent_id, start_date, end_date) VALUES (NEW.id, NEW.name, NEW.native_name, NEW.language_code, NEW.controller, NEW.classification, NEW.type, NEW.catalog_level, NEW.description, NEW.slug, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.date_created, NEW.date_modified, NEW.user_id, NEW.parent_id, N, NULL); END;");
	$this->execute("CREATE TRIGGER ArchivesHistoriesTableDelete AFTER DELETE ON archives FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE archives_histories SET end_date = N WHERE archive_id = OLD.id AND end_date IS NULL; END;");
	$this->execute("CREATE TRIGGER ArchivesHistoriesTableUpdate AFTER UPDATE ON archives FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE archives_histories SET end_date = N WHERE archive_id = OLD.id AND end_date IS NULL; INSERT INTO archives_histories (archive_id, name, native_name, language_code, controller, classification, type, catalog_level, description, slug, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified, user_id, parent_id, start_date, end_date) VALUES (NEW.id, NEW.name, NEW.native_name, NEW.language_code, NEW.controller, NEW.classification, NEW.type, NEW.catalog_level, NEW.description, NEW.slug, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.date_created, NEW.date_modified, NEW.user_id, NEW.parent_id, N, NULL); END;");
		
  }//up()

  public function down() {

  }//down()
}
?>
