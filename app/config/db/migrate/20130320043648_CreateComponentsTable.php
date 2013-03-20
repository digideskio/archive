<?php

class CreateComponentsTable extends Ruckusing_BaseMigration {

  public function up() {

  	$tables = array("components", "components_histories");

	foreach ($tables as $table) {

		$t = $this->create_table($table, array("id" => false));

		$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "auto_increment" => true));

		if ($table == 'components_histories') {
			$t->column("component_id", "integer", array("unsigned" => true, "null" => false));
		}

		$t->column("archive_id1", "integer", array("unsigned" => true, "null" => false));
		$t->column("archive_id2", "integer", array("unsigned" => true, "null" => false));
		$t->column("type", "string", array("null"=> false));
		$t->column("role", "string", array("null"=> false));
		$t->column("qualifier", "string", array("null"=> false));
		$t->column("extent", "string", array("null"=> false));
		$t->column("remarks", "text", array("null" => false));
		$t->column("attributes", "text", array("null" => false));

		$t->column("earliest_date", "date", array("null" => false));
		$t->column("latest_date", "date", array("null" => false));
		$t->column("earliest_date_format", "string" , array("limit" => 5, "null" => false));
		$t->column("latest_date_format", "string", array("limit" => 5, "null" => false));

		$t->column("date_created", "datetime", array("null" => false));
		$t->column("date_modified", "timestamp", array("null" => false));
		
		$t->column("user_id", "integer", array("unsigned" => true));

		if ($table == 'components_histories' ) {
			$t->column("start_date", "integer", array("unsigned" => true));
			$t->column("end_date", "integer", array("unsigned" => true));
		}

		$t->finish();

	}

	$this->execute("CREATE TRIGGER ComponentsHistoriesTableInsert AFTER INSERT ON components FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO components_histories (component_id, archive_id1, archive_id2, type, role, qualifier, extent, remarks, attributes, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified, user_id, start_date, end_date) VALUES (NEW.id, NEW.archive_id1, NEW.archive_id2, NEW.type, NEW.role, NEW.qualifier, NEW.extent, NEW.remarks, NEW.attributes, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.date_created, NEW.date_modified, NEW.user_id, N, NULL); END;");
	$this->execute("CREATE TRIGGER ComponentsHistoriesTableDelete AFTER DELETE ON components FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE components_histories SET end_date = N WHERE component_id = OLD.id AND end_date IS NULL; END;");
	$this->execute("CREATE TRIGGER ComponentsHistoriesTableUpdate AFTER UPDATE ON components FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE components_histories SET end_date = N WHERE component_id = OLD.id AND end_date IS NULL; INSERT INTO components_histories (component_id, archive_id1, archive_id2, type, role, qualifier, extent, remarks, attributes, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified, user_id, start_date, end_date) VALUES (NEW.id, NEW.archive_id1, NEW.archive_id2, NEW.type, NEW.role, NEW.qualifier, NEW.extent, NEW.remarks, NEW.attributes, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.date_created, NEW.date_modified, NEW.user_id, N, NULL); END;");

  }//up()

  public function down() {

	$this->execute("DROP TRIGGER ComponentsHistoriesTableInsert");
	$this->execute("DROP TRIGGER ComponentsHistoriesTableDelete");
	$this->execute("DROP TRIGGER ComponentsHistoriesTableUpdate");

  	$this->drop_table("components");
  	$this->drop_table("components_histories");

  }//down()
}
?>
