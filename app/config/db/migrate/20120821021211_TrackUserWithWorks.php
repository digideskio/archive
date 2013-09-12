<?php

class TrackUserWithWorks extends Ruckusing_Migration_Base {

  public function up() {

		$this->add_column("works", "user_id", "integer", array(
			"unsigned" => true
		));

		$this->add_column("works_histories", "user_id", "integer", array(
			"unsigned" => true
		));

		$this->execute("DROP TRIGGER WorksHistoriesTableInsert");
		$this->execute("DROP TRIGGER WorksHistoriesTableDelete");
		$this->execute("DROP TRIGGER WorksHistoriesTableUpdate");


		$this->execute("CREATE TRIGGER WorksHistoriesTableInsert AFTER INSERT ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); INSERT INTO works_histories (work_id, user_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.user_id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");
		$this->execute("CREATE TRIGGER WorksHistoriesTableDelete AFTER DELETE ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; END");
		$this->execute("CREATE TRIGGER WorksHistoriesTableUpdate AFTER UPDATE ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; INSERT INTO works_histories (work_id, user_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.user_id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");
 
 }//up()

  public function down() {

		$this->remove_column("works", "user_id");

		$this->execute("DROP TRIGGER WorksHistoriesTableInsert");
		$this->execute("DROP TRIGGER WorksHistoriesTableDelete");
		$this->execute("DROP TRIGGER WorksHistoriesTableUpdate");

		$this->execute("CREATE TRIGGER WorksHistoriesTableInsert AFTER INSERT ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); INSERT INTO works_histories (work_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");
		$this->execute("CREATE TRIGGER WorksHistoriesTableDelete AFTER DELETE ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; END");
		$this->execute("CREATE TRIGGER WorksHistoriesTableUpdate AFTER UPDATE ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; INSERT INTO works_histories (work_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");

  }//down()
}
?>
