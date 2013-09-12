<?php

class CreateWorksHistoriesTable extends Ruckusing_Migration_Base {

  public function up() {

		$t = $this->create_table("works_histories");//, array("id" => false));
      
		$t->column("work_id", "integer", array(
			"unsigned" => true, 
			"null" => false
		));

		$t->column("artist", "string", array("null" => false));
		$t->column("title", "string", array("null" => false));
		$t->column("classification", "string", array("null" => false));
		$t->column("materials", "text", array("null" => false));
		$t->column("quantity", "string", array("null" => false));
		$t->column("location", "string", array("null" => false));
		$t->column("lender", "string", array("null" => false));
		$t->column("remarks", "text", array("null" => false));
		$t->column("earliest_date", "date", array("null" => false));
		$t->column("latest_date", "date", array("null" => false));
		$t->column("creation_number", "string", array("null" => false));
		$t->column("height", "float", array("null" => false));
		$t->column("width", "float", array("null" => false));
		$t->column("depth", "float", array("null" => false));
		$t->column("diameter", "float", array("null" => false));
		$t->column("weight", "float", array("null" => false));
		$t->column("running_time", "string", array("null" => false));
		$t->column("measurement_remarks", "text", array("null" => false));
		$t->column("slug", "string", array("null" => false));
		$t->column("annotation", 'text', array("null" => false));
		$t->column("date_created", "datetime", array("null" => false));
		$t->column("date_modified", "datetime", array("null" => false));

		$t->column("start_date", "datetime");
		$t->column("end_date", "datetime");

		$t->finish();

		$this->execute("CREATE TRIGGER WorksHistoriesTableInsert AFTER INSERT ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); INSERT INTO works_histories (work_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");
		$this->execute("CREATE TRIGGER WorksHistoriesTableDelete AFTER DELETE ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; END");
		$this->execute("CREATE TRIGGER WorksHistoriesTableUpdate AFTER UPDATE ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; INSERT INTO works_histories (work_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");

  }//up()

  public function down() {
	$this->drop_table("works_histories");

	$this->execute("DROP TRIGGER WorksHistoriesTableInsert");
	$this->execute("DROP TRIGGER WorksHistoriesTableDelete");
	$this->execute("DROP TRIGGER WorksHistoriesTableUpdate");
  }//down()
}
?>
