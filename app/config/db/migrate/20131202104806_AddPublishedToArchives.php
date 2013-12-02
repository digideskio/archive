<?php

class AddPublishedToArchives extends Ruckusing_Migration_Base
{
    public function up()
    {
		// Drop triggers
		$this->execute("DROP TRIGGER ArchivesHistoriesTableInsert");
		$this->execute("DROP TRIGGER ArchivesHistoriesTableDelete");
		$this->execute("DROP TRIGGER ArchivesHistoriesTableUpdate");

		// Add Column
		$this->add_column("archives", "published", "integer", array("limit" => 1, "null" => false));
		$this->add_column("archives_histories", "published", "integer", array("limit" => 1, "null" => false));

		//Create triggers
		$this->execute("CREATE TRIGGER ArchivesHistoriesTableInsert AFTER INSERT ON archives FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO archives_histories (archive_id, name, native_name, language_code, controller, classification, type, catalog_level, description, slug, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified, user_id, parent_id, published, start_date, end_date) VALUES (NEW.id, NEW.name, NEW.native_name, NEW.language_code, NEW.controller, NEW.classification, NEW.type, NEW.catalog_level, NEW.description, NEW.slug, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.date_created, NEW.date_modified, NEW.user_id, NEW.parent_id, NEW.published, N, NULL); END;");
		$this->execute("CREATE TRIGGER ArchivesHistoriesTableDelete AFTER DELETE ON archives FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE archives_histories SET end_date = N WHERE archive_id = OLD.id AND end_date IS NULL; END;");
		$this->execute("CREATE TRIGGER ArchivesHistoriesTableUpdate AFTER UPDATE ON archives FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE archives_histories SET end_date = N WHERE archive_id = OLD.id AND end_date IS NULL; INSERT INTO archives_histories (archive_id, name, native_name, language_code, controller, classification, type, catalog_level, description, slug, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified, user_id, parent_id, published, start_date, end_date) VALUES (NEW.id, NEW.name, NEW.native_name, NEW.language_code, NEW.controller, NEW.classification, NEW.type, NEW.catalog_level, NEW.description, NEW.slug, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.date_created, NEW.date_modified, NEW.user_id, NEW.parent_id, NEW.published, N, NULL); END;");
    }//up()

    public function down()
    {
		// Drop triggers
		$this->execute("DROP TRIGGER ArchivesHistoriesTableInsert");
		$this->execute("DROP TRIGGER ArchivesHistoriesTableDelete");
		$this->execute("DROP TRIGGER ArchivesHistoriesTableUpdate");

		// Remove column
		$this->remove_column("archives", "published");
		$this->remove_column("archives_histories", "published");

		//Create triggers
		$this->execute("CREATE TRIGGER ArchivesHistoriesTableInsert AFTER INSERT ON archives FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO archives_histories (archive_id, name, native_name, language_code, controller, classification, type, catalog_level, description, slug, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified, user_id, parent_id, start_date, end_date) VALUES (NEW.id, NEW.name, NEW.native_name, NEW.language_code, NEW.controller, NEW.classification, NEW.type, NEW.catalog_level, NEW.description, NEW.slug, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.date_created, NEW.date_modified, NEW.user_id, NEW.parent_id, N, NULL); END;");
		$this->execute("CREATE TRIGGER ArchivesHistoriesTableDelete AFTER DELETE ON archives FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE archives_histories SET end_date = N WHERE archive_id = OLD.id AND end_date IS NULL; END;");
		$this->execute("CREATE TRIGGER ArchivesHistoriesTableUpdate AFTER UPDATE ON archives FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE archives_histories SET end_date = N WHERE archive_id = OLD.id AND end_date IS NULL; INSERT INTO archives_histories (archive_id, name, native_name, language_code, controller, classification, type, catalog_level, description, slug, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified, user_id, parent_id, start_date, end_date) VALUES (NEW.id, NEW.name, NEW.native_name, NEW.language_code, NEW.controller, NEW.classification, NEW.type, NEW.catalog_level, NEW.description, NEW.slug, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.date_created, NEW.date_modified, NEW.user_id, NEW.parent_id, N, NULL); END;");
    }//down()
}
