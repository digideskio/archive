<?php

class ChangeHistoryStartEndDatesToUnixTimestamps extends Ruckusing_Migration_Base {

  public function up() {
  	
	$this->execute("DROP TRIGGER WorksHistoriesTableInsert");
	$this->execute("DROP TRIGGER WorksHistoriesTableDelete");
	$this->execute("DROP TRIGGER WorksHistoriesTableUpdate");


	$this->execute("ALTER TABLE `works_histories` CHANGE `start_date` `start_datetime` datetime");
	$this->execute("ALTER TABLE `works_histories` ADD `start_date` int(11)");
	$this->execute("UPDATE `works_histories` SET `start_date`=UNIX_TIMESTAMP(start_datetime)");
	$this->execute("ALTER TABLE `works_histories` DROP `start_datetime`");

	$this->execute("ALTER TABLE `works_histories` CHANGE `end_date` `end_datetime` datetime");
	$this->execute("ALTER TABLE `works_histories` ADD `end_date` int(11)");
	$this->execute("UPDATE `works_histories` SET `end_date`=UNIX_TIMESTAMP(end_datetime)");
	$this->execute("ALTER TABLE `works_histories` DROP `end_datetime`");

	$this->execute("CREATE TRIGGER WorksHistoriesTableInsert AFTER INSERT ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO works_histories (work_id, user_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, earliest_date_format, latest_date_format, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.user_id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");
	$this->execute("CREATE TRIGGER WorksHistoriesTableDelete AFTER DELETE ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; END");
	$this->execute("CREATE TRIGGER WorksHistoriesTableUpdate AFTER UPDATE ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; INSERT INTO works_histories (work_id, user_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, earliest_date_format, latest_date_format, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.user_id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");

  }//up()

  public function down() {

	$this->execute("DROP TRIGGER WorksHistoriesTableInsert");
	$this->execute("DROP TRIGGER WorksHistoriesTableDelete");
	$this->execute("DROP TRIGGER WorksHistoriesTableUpdate");

	$this->execute("ALTER TABLE `works_histories` CHANGE `start_date` `start_unixtime` int(11)");
	$this->execute("ALTER TABLE `works_histories` ADD `start_date` DATETIME");
	$this->execute("UPDATE `works_histories` SET `start_date`=FROM_UNIXTIME(start_unixtime)");
	$this->execute("ALTER TABLE `works_histories` DROP `start_unixtime`");

	$this->execute("ALTER TABLE `works_histories` CHANGE `end_date` `end_unixtime` int(11)");
	$this->execute("ALTER TABLE `works_histories` ADD `end_date` DATETIME");
	$this->execute("UPDATE `works_histories` SET `end_date`=FROM_UNIXTIME(end_unixtime)");
	$this->execute("ALTER TABLE `works_histories` DROP `end_unixtime`");

	$this->execute("CREATE TRIGGER WorksHistoriesTableInsert AFTER INSERT ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); INSERT INTO works_histories (work_id, user_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, earliest_date_format, latest_date_format, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.user_id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");
	$this->execute("CREATE TRIGGER WorksHistoriesTableDelete AFTER DELETE ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; END");
	$this->execute("CREATE TRIGGER WorksHistoriesTableUpdate AFTER UPDATE ON works FOR EACH ROW BEGIN DECLARE N DATETIME; SET N = now(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; INSERT INTO works_histories (work_id, user_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, earliest_date_format, latest_date_format, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.user_id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");


  }//down()
}
?>
