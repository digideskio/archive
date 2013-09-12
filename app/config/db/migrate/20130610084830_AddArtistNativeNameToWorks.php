<?php

class AddArtistNativeNameToWorks extends Ruckusing_Migration_Base {

  public function up() {

	//Drop our triggers
	$this->execute("DROP TRIGGER WorksHistoriesTableInsert");
	$this->execute("DROP TRIGGER WorksHistoriesTableDelete");
	$this->execute("DROP TRIGGER WorksHistoriesTableUpdate");

	$this->execute("ALTER TABLE works ADD COLUMN artist_native_name VARCHAR(255) NOT NULL AFTER artist");
	$this->execute("ALTER TABLE works_histories ADD COLUMN artist_native_name VARCHAR(255) NOT NULL AFTER artist");

	$tables = array('works', 'works_histories');

	foreach ($tables as $table) {

		$works = $this->select_all("SELECT * FROM $table WHERE attributes != ''");

		if ($works) {
			
			foreach ($works as $row) {

				$id = $row['id'];

				$attributes_json = $row['attributes'];
				$attributes = $attributes_json ? json_decode($attributes_json, true) : array();

				$artist_native_name = isset($attributes['artist_native_name']) ? mysql_real_escape_string($attributes['artist_native_name']) : ''; 

				if ($artist_native_name) {

					$this->execute("UPDATE $table SET artist_native_name = '$artist_native_name' WHERE id = '$id'");

					unset($attributes['artist_native_name']);

					$attributes_json = json_encode($attributes);

					$attributes_esc = mysql_real_escape_string($attributes_json);

					$this->execute("UPDATE $table SET attributes = '$attributes_esc' WHERE id = '$id'");
				}
			}
		}

	}

	//Finally, create the triggers for Works Histories
		
	$this->execute("CREATE TRIGGER WorksHistoriesTableInsert AFTER INSERT ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO works_histories (work_id, title, artist, artist_native_name, creation_number, materials, techniques, color, format, shape, size, state, location, quantity, annotation, inscriptions, height, width, depth, length, circumference, diameter, volume, weight, area, base, running_time, measurement_remarks, attributes, remarks, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.artist, NEW.artist_native_name, NEW.creation_number, NEW.materials, NEW.techniques, NEW.color, NEW.format, NEW.shape, NEW.size, NEW.state, NEW.location, NEW.quantity, NEW.annotation, NEW.inscriptions, NEW.height, NEW.width, NEW.depth, NEW.length, NEW.circumference, NEW.diameter, NEW.volume, NEW.weight, NEW.area, NEW.base, NEW.running_time, NEW.measurement_remarks, NEW.attributes, NEW.remarks, N, NULL); END");
	$this->execute("CREATE TRIGGER WorksHistoriesTableDelete AFTER DELETE ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; END");
	$this->execute("CREATE TRIGGER WorksHistoriesTableUpdate AFTER UPDATE ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; INSERT INTO works_histories (work_id, title, artist, artist_native_name, creation_number, materials, techniques, color, format, shape, size, state, location, quantity, annotation, inscriptions, height, width, depth, length, circumference, diameter, volume, weight, area, base, running_time, measurement_remarks, attributes, remarks, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.artist, NEW.artist_native_name, NEW.creation_number, NEW.materials, NEW.techniques, NEW.color, NEW.format, NEW.shape, NEW.size, NEW.state, NEW.location, NEW.quantity, NEW.annotation, NEW.inscriptions, NEW.height, NEW.width, NEW.depth, NEW.length, NEW.circumference, NEW.diameter, NEW.volume, NEW.weight, NEW.area, NEW.base, NEW.running_time, NEW.measurement_remarks, NEW.attributes, NEW.remarks, N, NULL); END");	
  }//up()

  public function down() {

	//Drop our triggers
	$this->execute("DROP TRIGGER WorksHistoriesTableInsert");
	$this->execute("DROP TRIGGER WorksHistoriesTableDelete");
	$this->execute("DROP TRIGGER WorksHistoriesTableUpdate");

	$tables = array('works', 'works_histories');

	foreach ($tables as $table) {

		$works = $this->select_all("SELECT * FROM $table WHERE artist_native_name != ''");

		if ($works) {
			
			foreach ($works as $row) {

				$id = $row['id'];

				$artist_native_name = mysql_real_escape_string($row['artist_native_name']);

				$attributes_json = $row['attributes'];
				$attributes = $attributes_json ? json_decode($attributes_json, true) : array();

				$attributes['artist_native_name'] = $artist_native_name;

				$attributes_json = json_encode($attributes);

				$attributes_esc = mysql_real_escape_string($attributes_json);

				$this->execute("UPDATE $table SET attributes = '$attributes_esc' WHERE id = '$id'");

			}
		}

	}

  	$this->remove_column('works', 'artist_native_name');
  	$this->remove_column('works_histories', 'artist_native_name');

	//Finally, create the triggers for Works Histories
		
	$this->execute("CREATE TRIGGER WorksHistoriesTableInsert AFTER INSERT ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO works_histories (work_id, title, artist, creation_number, materials, techniques, color, format, shape, size, state, location, quantity, annotation, inscriptions, height, width, depth, length, circumference, diameter, volume, weight, area, base, running_time, measurement_remarks, attributes, remarks, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.artist, NEW.creation_number, NEW.materials, NEW.techniques, NEW.color, NEW.format, NEW.shape, NEW.size, NEW.state, NEW.location, NEW.quantity, NEW.annotation, NEW.inscriptions, NEW.height, NEW.width, NEW.depth, NEW.length, NEW.circumference, NEW.diameter, NEW.volume, NEW.weight, NEW.area, NEW.base, NEW.running_time, NEW.measurement_remarks, NEW.attributes, NEW.remarks, N, NULL); END");
	$this->execute("CREATE TRIGGER WorksHistoriesTableDelete AFTER DELETE ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; END");
	$this->execute("CREATE TRIGGER WorksHistoriesTableUpdate AFTER UPDATE ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; INSERT INTO works_histories (work_id, title, artist, creation_number, materials, techniques, color, format, shape, size, state, location, quantity, annotation, inscriptions, height, width, depth, length, circumference, diameter, volume, weight, area, base, running_time, measurement_remarks, attributes, remarks, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.artist, NEW.creation_number, NEW.materials, NEW.techniques, NEW.color, NEW.format, NEW.shape, NEW.size, NEW.state, NEW.location, NEW.quantity, NEW.annotation, NEW.inscriptions, NEW.height, NEW.width, NEW.depth, NEW.length, NEW.circumference, NEW.diameter, NEW.volume, NEW.weight, NEW.area, NEW.base, NEW.running_time, NEW.measurement_remarks, NEW.attributes, NEW.remarks, N, NULL); END");	


  }//down()
}
?>
