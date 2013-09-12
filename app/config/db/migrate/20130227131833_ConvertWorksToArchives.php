<?php

class ConvertWorksToArchives extends Ruckusing_Migration_Base {

  public function up() {

	//Copy fields from Works into Archives

	$works = $this->select_all("SELECT * FROM works");

	if($works) {

		foreach ($works as $row) {

			$id = $row['id'];
			$name = mysql_real_escape_string($row['title']);
			$controller = 'works';
			$classification = mysql_real_escape_string($row['classification']);
			$catalog_level = 'item';
			$slug = $row['slug'];
			$earliest_date = $row['earliest_date'];
			$latest_date = $row['latest_date'];
			$earliest_date_format = $row['earliest_date_format'];
			$latest_date_format = $row['latest_date_format'];
			$date_created = $row['date_created'];
			$date_modified = $row['date_modified'];
			$user_id = $row['user_id'];
		
			$this->execute("INSERT INTO archives (id, name, controller, classification,  catalog_level, slug, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified, user_id) VALUES ('$id', '$name', '$controller', '$classification', '$catalog_level', '$slug', '$earliest_date', '$latest_date', '$earliest_date_format', '$latest_date_format', '$date_created', '$date_modified', '$user_id')");

		}

	}

	//The previous inserts created entries in the archives_histories table, but we want to re-construct this manually from the works_histories table	

	$works_histories = $this->select_all("SELECT * FROM works_histories");

	if($works_histories) {

		$this->execute("DELETE FROM archives_histories WHERE controller = 'works'");

		foreach ($works_histories as $row) {

			$id = $row['id'];
			$archive_id = $row['work_id'];
			$name = mysql_real_escape_string($row['title']);
			$controller = 'works';
			$classification = mysql_real_escape_string($row['classification']);
			$catalog_level = 'item';
			$slug = $row['slug'];
			$earliest_date = $row['earliest_date'];
			$latest_date = $row['latest_date'];
			$earliest_date_format = $row['earliest_date_format'];
			$latest_date_format = $row['latest_date_format'];
			$date_created = $row['date_created'];
			$date_modified = $row['date_modified'];
			$user_id = $row['user_id'];
			$start_date = $row['start_date'];
			$end_date = $row['end_date'];
			
			$this->execute("INSERT INTO archives_histories (id, archive_id, name, controller, classification,  catalog_level, slug, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified, user_id, start_date, end_date) VALUES ('$id', '$archive_id', '$name', '$controller', '$classification', '$catalog_level', '$slug', '$earliest_date', '$latest_date', '$earliest_date_format', '$latest_date_format', '$date_created', '$date_modified', '$user_id', '$start_date', '$end_date')");

		}

	}

	//Re-do the Works table

	$this->execute("CREATE TEMPORARY TABLE tmp_works AS (SELECT * FROM works)");
	$this->execute("CREATE TEMPORARY TABLE tmp_works_histories AS (SELECT * FROM works_histories)");

	$this->execute("DROP TRIGGER WorksHistoriesTableInsert");
	$this->execute("DROP TRIGGER WorksHistoriesTableDelete");
	$this->execute("DROP TRIGGER WorksHistoriesTableUpdate");

	$tables = array('works', 'works_histories');

	foreach ($tables as $table) {
		$this->execute("DROP TABLE $table");

		$t = $this->create_table($table, array("id" => false));

		if ($table == 'works') {
			$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "null" => false));
		}

		if ($table == 'works_histories' ) {
			$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "auto_increment" => true));
			$t->column("work_id", "integer", array("unsigned" => true, "null" => false));
		}

		$t->column("title", "string", array("limit" => 1024, "null" => false));
		$t->column("artist", "string", array("null" => false));
		$t->column("creation_number", "string", array("null" => false));
		$t->column("materials", "string", array("limit" => 1024, "null" => false));
		$t->column("techniques", "string", array("limit" => 1024, "null" => false));
		$t->column("color", "string", array("null" => false));
		$t->column("format", "string", array("null" => false));
		$t->column("shape", "string", array("null" => false));
		$t->column("size", "string", array("null" => false));
		$t->column("state", "string", array("null" => false));
		$t->column("location", "string", array("null" => false));
		$t->column("quantity", "string", array("null" => false));
		$t->column("annotation", "text", array("null" => false));
		$t->column("inscriptions", "text", array("null" => false));
		$t->column("height", "float", array("null" => false));
		$t->column("width", "float", array("null" => false));
		$t->column("depth", "float", array("null" => false));
		$t->column("length", "float", array("null" => false));
		$t->column("circumference", "float", array("null" => false));
		$t->column("diameter", "float", array("null" => false));
		$t->column("volume", "float", array("null" => false));
		$t->column("weight", "float", array("null" => false));
		$t->column("area", "float", array("null" => false));
		$t->column("base", "float", array("null" => false));
		$t->column("running_time", "string", array("null" => false));
		$t->column("measurement_remarks", "text", array("null" => false));
		$t->column("attributes", "text", array("null" => false));
		$t->column("remarks", "text", array("null" => false));

		if ($table == 'works_histories' ) {
			$t->column("start_date", "integer", array("unsigned" => true));
			$t->column("end_date", "integer", array("unsigned" => true));
		}

		$t->finish();
		
		if ($table == 'works') {

			$tmp_works = $this->select_all("SELECT * FROM tmp_works");

		} elseif ($table == 'works_histories') {

			$tmp_works = $this->select_all("SELECT * FROM tmp_works_histories");

		}	

		if($tmp_works) {
		
			foreach ($tmp_works as $row) {

				$id = $row['id'];
				$title = mysql_real_escape_string($row['title']);
				$artist = mysql_real_escape_string($row['artist']);
				$creation_number = mysql_real_escape_string($row['creation_number']);
				$materials = mysql_real_escape_string($row['materials']);
				$quantity = mysql_real_escape_string($row['quantity']);
				$location = mysql_real_escape_string($row['location']);
				$annotation = mysql_real_escape_string($row['annotation']);
				$height = $row['height'];
				$width = $row['width'];
				$depth = $row['depth'];
				$diameter = $row['diameter'];
				$weight = $row['weight'];
				$running_time = mysql_real_escape_string($row['running_time']);
				$measurement_remarks = mysql_real_escape_string($row['measurement_remarks']);
				$remarks = mysql_real_escape_string($row['remarks']);
				
				if ($table == 'works') {
					$this->execute("INSERT INTO works (id, title, artist, creation_number, materials, quantity, location, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, remarks) VALUES ('$id', '$title', '$artist', '$creation_number', '$materials', '$quantity', '$location', '$height', '$width', '$depth', '$diameter', '$weight', '$running_time', '$measurement_remarks', '$annotation', '$remarks') ");
				} elseif ($table == 'works_histories') {

					$work_id = $row['work_id'];
					$start_date = $row['start_date'];
					$end_date = $row['end_date'];

					$this->execute("INSERT INTO works_histories (id, work_id, title, artist, creation_number, materials, quantity, location, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, remarks, start_date, end_date) VALUES ('$id', '$work_id', '$title', '$artist', '$creation_number', '$materials', '$quantity', '$location', '$height', '$width', '$depth', '$diameter', '$weight', '$running_time', '$measurement_remarks', '$annotation', '$remarks', '$start_date', '$end_date') ");

				}
				
			}

		}
	}

	//Any end dates that are 0 should instead be NULL

	$this->execute("UPDATE archives_histories SET end_date = NULL where end_date = 0");
	$this->execute("UPDATE works_histories SET end_date = NULL where end_date = 0");

	//Finally, create the triggers for Works Histories
		
	$this->execute("CREATE TRIGGER WorksHistoriesTableInsert AFTER INSERT ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO works_histories (work_id, title, artist, creation_number, materials, techniques, color, format, shape, size, state, location, quantity, annotation, inscriptions, height, width, depth, length, circumference, diameter, volume, weight, area, base, running_time, measurement_remarks, attributes, remarks, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.artist, NEW.creation_number, NEW.materials, NEW.techniques, NEW.color, NEW.format, NEW.shape, NEW.size, NEW.state, NEW.location, NEW.quantity, NEW.annotation, NEW.inscriptions, NEW.height, NEW.width, NEW.depth, NEW.length, NEW.circumference, NEW.diameter, NEW.volume, NEW.weight, NEW.area, NEW.base, NEW.running_time, NEW.measurement_remarks, NEW.attributes, NEW.remarks, N, NULL); END");
	$this->execute("CREATE TRIGGER WorksHistoriesTableDelete AFTER DELETE ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; END");
	$this->execute("CREATE TRIGGER WorksHistoriesTableUpdate AFTER UPDATE ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; INSERT INTO works_histories (work_id, title, artist, creation_number, materials, techniques, color, format, shape, size, state, location, quantity, annotation, inscriptions, height, width, depth, length, circumference, diameter, volume, weight, area, base, running_time, measurement_remarks, attributes, remarks, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.artist, NEW.creation_number, NEW.materials, NEW.techniques, NEW.color, NEW.format, NEW.shape, NEW.size, NEW.state, NEW.location, NEW.quantity, NEW.annotation, NEW.inscriptions, NEW.height, NEW.width, NEW.depth, NEW.length, NEW.circumference, NEW.diameter, NEW.volume, NEW.weight, NEW.area, NEW.base, NEW.running_time, NEW.measurement_remarks, NEW.attributes, NEW.remarks, N, NULL); END");	

  }//up()

  public function down() {

	$this->execute("CREATE TEMPORARY TABLE tmp_works AS (SELECT * FROM works)");
	$this->execute("CREATE TEMPORARY TABLE tmp_works_histories AS (SELECT * FROM works_histories)");

	$this->execute("DROP TRIGGER WorksHistoriesTableInsert");
	$this->execute("DROP TRIGGER WorksHistoriesTableDelete");
	$this->execute("DROP TRIGGER WorksHistoriesTableUpdate");	

	$tables = array('works', 'works_histories');

	foreach ($tables as $table) {
		$this->execute("DROP TABLE $table");

		$t = $this->create_table($table, array("id" => false));

		$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "auto_increment" => true));

		if ($table == 'works_histories' ) {
			$t->column("work_id", "integer", array("unsigned" => true, "null" => false));
		}

		$t->column("artist", "string", array("null" => false));
		$t->column("title", "string", array("null" => false));
		$t->column("classification", "string", array("null" => false));
		$t->column("materials", "text", array("null" => false));
		$t->column("quantity", "string", array("null" => false));
		$t->column("location", "string", array("null" => false));
		$t->column("lender", "string", array("null" => false));
		$t->column("remarks", "text", array("null" => false));
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

		$t->column("earliest_date", "date", array("null" => false));
		$t->column("latest_date", "date", array("null" => false));
		$t->column("earliest_date_format", "string" , array("limit" => 5, "null" => false));
		$t->column("latest_date_format", "string", array("limit" => 5, "null" => false));

		$t->column("date_created", "datetime", array("null" => false));
		$t->column("date_modified", "timestamp", array("null" => false));

		$t->column("user_id", "integer", array("unsigned" => true));

		if ($table == 'works_histories' ) {
			$t->column("start_date", "integer", array("unsigned" => true));
			$t->column("end_date", "integer", array("unsigned" => true));
		}

		$t->finish();

		if ($table == 'works') {

			$tmp_works = $this->select_all("SELECT * FROM archives LEFT JOIN tmp_works ON archives.id = tmp_works.id");

		} elseif ($table == 'works_histories') {

			$tmp_works = $this->select_all("SELECT * FROM archives_histories left join tmp_works_histories on archives_histories.start_date = tmp_works_histories.start_date");

		}	

		foreach ($tmp_works as $row) {

			$id = $row['id'];
			$title = mysql_real_escape_string($row['title']);
			$artist = mysql_real_escape_string($row['artist']);
			$classification = mysql_real_escape_string($row['classification']);
			$materials = mysql_real_escape_string($row['materials']);
			$quantity = mysql_real_escape_string($row['quantity']);
			$location = mysql_real_escape_string($row['location']);
			$remarks = mysql_real_escape_string($row['remarks']);
			$creation_number = mysql_real_escape_string($row['creation_number']);
			$height = $row['height'];
			$width = $row['width'];
			$depth = $row['depth'];
			$diameter = $row['diameter'];
			$weight = $row['weight'];
			$running_time = mysql_real_escape_string($row['running_time']);
			$measurement_remarks = mysql_real_escape_string($row['measurement_remarks']);
			$slug = mysql_real_escape_string($row['slug']);
			$annotation = mysql_real_escape_string($row['annotation']);

			$earliest_date = mysql_real_escape_string($row['earliest_date']);
			$latest_date = mysql_real_escape_string($row['latest_date']);
			$earliest_date_format = mysql_real_escape_string($row['earliest_date_format']);
			$latest_date_format = mysql_real_escape_string($row['latest_date_format']);
			$date_created = mysql_real_escape_string($row['date_created']);
			$date_modified = mysql_real_escape_string($row['date_created']);

			$user_id = $row['user_id'];

			if ($table == 'works') {
				$this->execute("INSERT INTO works (id, artist, title, classification, materials, quantity, location, remarks, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, slug, annotation, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified, user_id) VALUES ('$id', '$artist', '$title', '$classification', '$materials', '$quantity', '$location', '$remarks', '$creation_number', '$height', '$width', '$depth', '$diameter', '$weight', '$running_time', '$measurement_remarks', '$slug','$annotation', '$earliest_date', '$latest_date', '$earliest_date_format', '$latest_date_format', '$date_created', '$date_modified', '$user_id')");
			} elseif ($table == 'works_histories') {

				$work_id = $row['work_id'];
				$start_date = $row['start_date'];
				$end_date = $row['end_date'];

				$this->execute("INSERT INTO works_histories (work_id, artist, title, classification, materials, quantity, location, remarks, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, slug, annotation, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified, user_id, start_date, end_date) VALUES ('$work_id', '$artist', '$title', '$classification', '$materials', '$quantity', '$location', '$remarks', '$creation_number', '$height', '$width', '$depth', '$diameter', '$weight', '$running_time', '$measurement_remarks', '$slug','$annotation', '$earliest_date', '$latest_date', '$earliest_date_format', '$latest_date_format', '$date_created', '$date_modified', '$user_id', '$start_date', '$end_date') ");

			}	
		}
	}

	//Any end dates that are 0 should instead be NULL

	$this->execute("UPDATE works_histories SET end_date = NULL where end_date = 0");

	//Re-create the triggers

	$this->execute("CREATE TRIGGER WorksHistoriesTableInsert AFTER INSERT ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO works_histories (work_id, user_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, earliest_date_format, latest_date_format, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.user_id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");
		$this->execute("CREATE TRIGGER WorksHistoriesTableDelete AFTER DELETE ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; END");
		$this->execute("CREATE TRIGGER WorksHistoriesTableUpdate AFTER UPDATE ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; INSERT INTO works_histories (work_id, user_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, earliest_date_format, latest_date_format, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, annotation, slug, date_created, date_modified, start_date, end_date) VALUES (NEW.id, NEW.user_id, NEW.artist, NEW.title, NEW.classification, NEW.materials, NEW.quantity, NEW.location, NEW.lender, NEW.remarks, NEW.earliest_date, NEW.latest_date, NEW.earliest_date_format, NEW.latest_date_format, NEW.creation_number, NEW.height, NEW.width, NEW.depth, NEW.diameter, NEW.weight, NEW.running_time, NEW.measurement_remarks, NEW.annotation, NEW.slug, NEW.date_created, NEW.date_modified, N, NULL); END");

	$this->execute("DELETE FROM archives WHERE controller = 'works'");
	$this->execute("DELETE FROM archives_histories WHERE controller = 'works'");

  }//down()
}
?>
