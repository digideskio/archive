<?php

class CreateArchivesHistoriesTable extends Ruckusing_Migration_Base {

  public function up() {

  	$t = $this->create_table("archives_histories", array("id" => false));

	$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "auto_increment" => true));

	$t->column("archive_id", "integer", array("unsigned" => true));
	$t->column("name", "string", array("limit" => 1024, "null" => false));
	$t->column("native_name", "string", array("limit" => 1024, "null" => false));
	$t->column("language_code", "string", array("limit" => 12, "null" => false));
	$t->column("controller", "string", array("null"=> false));
	$t->column("classification", "string", array("null"=> false));
	$t->column("type", "string", array("null"=> false));
	$t->column("catalog_level", "string", array("null"=> false));  
	$t->column("description", "text", array("null" => false));

	$t->column("slug", "string", array("null"=> false));  

	$t->column("earliest_date", "date", array("null" => false));
	$t->column("latest_date", "date", array("null" => false));
	$t->column("earliest_date_format", "string" , array("limit" => 5, "null" => false));
	$t->column("latest_date_format", "string", array("limit" => 5, "null" => false));

	$t->column("date_created", "datetime", array("null" => false));
	$t->column("date_modified", "timestamp", array("null" => false));

	$t->column("user_id", "integer", array("unsigned" => true));
	$t->column("parent_id", "integer", array("unsigned" => true));

	$t->column("start_date", "integer", array("unsigned" => true));
	$t->column("end_date", "integer", array("unsigned" => true));

	$t->finish();

	$this->execute("CREATE TRIGGER ArchivesHistoriesTableInsert AFTER INSERT ON archives FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO archives_histories (archive_id, name, native_name, language_code, controller, classification, type, catalog_level, description, slug, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified, user_id, parent_id, start_date, end_date) VALUES (NEW.id, NEW.name, NEW.native_name, NEW.language_code, NEW.controller, NEW.classification, NEW.type, NEW.catalog_level, NEW.description, NEW.slug, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.date_created, NEW.date_modified, NEW.user_id, NEW.parent_id, N, NULL); END;");
	$this->execute("CREATE TRIGGER ArchivesHistoriesTableDelete AFTER DELETE ON archives FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE archives_histories SET end_date = N WHERE archive_id = OLD.id AND end_date IS NULL; END;");
	$this->execute("CREATE TRIGGER ArchivesHistoriesTableUpdate AFTER UPDATE ON archives FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE archives_histories SET end_date = N WHERE archive_id = OLD.id AND end_date IS NULL; INSERT INTO archives_histories (archive_id, name, native_name, language_code, controller, classification, type, catalog_level, description, slug, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified, user_id, parent_id, start_date, end_date) VALUES (NEW.id, NEW.name, NEW.native_name, NEW.language_code, NEW.controller, NEW.classification, NEW.type, NEW.catalog_level, NEW.description, NEW.slug, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.date_created, NEW.date_modified, NEW.user_id, NEW.parent_id, N, NULL); END;");

  }//up()

  public function down() {

  	$this->drop_table("archives_histories");

	$this->execute("DROP TRIGGER ArchivesHistoriesTableInsert");
	$this->execute("DROP TRIGGER ArchivesHistoriesTableDelete");
	$this->execute("DROP TRIGGER ArchivesHistoriesTableUpdate");

  }//down()
}
?>
