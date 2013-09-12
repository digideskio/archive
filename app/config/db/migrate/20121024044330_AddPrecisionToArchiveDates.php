<?php

class AddPrecisionToArchiveDates extends Ruckusing_Migration_Base {

  public function up() {

	$this->execute("DROP TRIGGER WorksHistoriesTableInsert");
	$this->execute("DROP TRIGGER WorksHistoriesTableDelete");
	$this->execute("DROP TRIGGER WorksHistoriesTableUpdate");

	$tables = array('architectures', 'exhibitions', 'publications', 'works');

	foreach ($tables as $table) {
		$this->add_column($table, "earliest_date_format", "string" , array('limit' => 5, 'null' => false));
		$this->add_column($table, "latest_date_format", "string", array('limit' => 5, 'null' => false));

		$result = $this->select_all("SELECT id, earliest_date, latest_date, date_modified FROM $table");

		if ($result) {
			foreach ($result as $record) {
				$id = $record['id'];
				$earliest_date = $record['earliest_date'];
				$latest_date = $record['latest_date'];
				$date_modified = $record['date_modified'];

				$earliest_date_format = ($earliest_date != "0000-00-00") ? 'Y-m-d' : '';
				$latest_date_format = ($latest_date != "0000-00-00") ? 'Y-m-d' : '';

				$this->execute("UPDATE $table SET earliest_date_format = '$earliest_date_format', latest_date_format = '$latest_date_format', date_modified = '$date_modified' WHERE id = '$id'");
			}
		}
	} 
	
	$this->add_column("works_histories", "earliest_date_format", "string" , array('limit' => 5, 'null' => false));
	$this->add_column("works_histories", "latest_date_format", "string", array('limit' => 5, 'null' => false));


	$this->execute("CREATE TRIGGER WorksHistoriesTableInsert AFTER INSERT ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); INSERT INTO works_histories (work_id, user_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, earliest_date_format, latest_date_format, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.user_id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");
	$this->execute("CREATE TRIGGER WorksHistoriesTableDelete AFTER DELETE ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; END");
	$this->execute("CREATE TRIGGER WorksHistoriesTableUpdate AFTER UPDATE ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; INSERT INTO works_histories (work_id, user_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, earliest_date_format, latest_date_format, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.user_id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");

  }//up()

  public function down() {

	$tables = array('architectures', 'exhibitions', 'publications', 'works', 'works_histories');

	foreach ($tables as $table) {
		$this->remove_column($table, "earliest_date_format");
		$this->remove_column($table, "latest_date_format");
	}

	$this->execute("DROP TRIGGER WorksHistoriesTableInsert");
	$this->execute("DROP TRIGGER WorksHistoriesTableDelete");
	$this->execute("DROP TRIGGER WorksHistoriesTableUpdate");

	$this->execute("CREATE TRIGGER WorksHistoriesTableInsert AFTER INSERT ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); INSERT INTO works_histories (work_id, user_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.user_id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");
	$this->execute("CREATE TRIGGER WorksHistoriesTableDelete AFTER DELETE ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; END");
	$this->execute("CREATE TRIGGER WorksHistoriesTableUpdate AFTER UPDATE ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; INSERT INTO works_histories (work_id, user_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.user_id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");
  }//down()
}
?>
