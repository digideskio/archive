<?php

class ConvertExhibitionsToArchives extends Ruckusing_Migration_Base {

  public function up() {

  	$this->execute("CREATE TEMPORARY TABLE tmp_exhibitions as (SELECT * FROM exhibitions)");	
	$this->drop_table("exhibitions");

	//Create the Exhibitions and Exhibition History Tables
	$tables = array('exhibitions', 'exhibitions_histories');

	foreach ($tables as $table) {

		$t = $this->create_table($table, array("id" => false));

		if ($table == 'exhibitions') {
			$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "null" => false));
		}

		if ($table == 'exhibitions_histories') {
			$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "auto_increment" => true));
			$t->column("exhibition_id", "integer", array("unsigned" => true, "null" => false));
		}

		$t->column("title", "string", array("limit" => 1024, "null" => false));
		$t->column("remarks", "text", array("null" => false));
		$t->column("annotation", "text", array("null" => false));
		$t->column("attributes", "text", array("null" => false));

		$t->column("address", "string", array("limit" => 1024, "null" => false));
		$t->column("location", "string", array("null" => false));
		$t->column("city", "string", array("null" => false));
		$t->column("country", "string", array("null" => false));
		$t->column("venue", "string", array("null" => false));

		$t->column("organizer", "string", array("null" => false));
		$t->column("curator", "string", array("null" => false));
		$t->column("sponsor", "string", array("null" => false));

		if ($table == 'exhibitions_histories' ) {
			$t->column("start_date", "integer", array("unsigned" => true));
			$t->column("end_date", "integer", array("unsigned" => true));
		}

		$t->finish();

	}

	$this->add_index("exhibitions_histories", "exhibition_id");  

	$this->execute("CREATE TRIGGER ExhibitionsHistoriesTableInsert AFTER INSERT ON exhibitions FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO exhibitions_histories (exhibition_id, title, remarks, annotation, attributes, address, location, city, country, venue, organizer, curator, sponsor, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.remarks, NEW.annotation, NEW.attributes, NEW.address, NEW.location, NEW.city, NEW.country, NEW.venue, NEW.organizer, NEW.curator, NEW.sponsor, N, NULL); END");
	$this->execute("CREATE TRIGGER ExhibitionsHistoriesTableDelete AFTER DELETE ON exhibitions FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE exhibitions_histories SET end_date = N WHERE exhibition_id = OLD.id AND end_date IS NULL; END");
	$this->execute("CREATE TRIGGER ExhibitionsHistoriesTableUpdate AFTER UPDATE ON exhibitions FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE exhibitions_histories SET end_date = N WHERE exhibition_id = OLD.id AND end_date IS NULL; INSERT INTO exhibitions_histories (exhibition_id, title, remarks, annotation, attributes, address, location, city, country, venue, organizer, curator, sponsor, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.remarks, NEW.annotation, NEW.attributes, NEW.address, NEW.location, NEW.city, NEW.country, NEW.venue, NEW.organizer, NEW.curator, NEW.sponsor, N, NULL); END");	

  	//Copy fields from Exhibitions into Archives

	$this->execute("CREATE TEMPORARY TABLE tmp_exhibitions_documents as (SELECT * FROM exhibitions_documents)");
	$this->execute("CREATE TEMPORARY TABLE tmp_exhibitions_works as (SELECT * FROM exhibitions_works)");
	$this->execute("CREATE TEMPORARY TABLE tmp_exhibitions_links as (SELECT * FROM exhibitions_links)");

	$exhibitions = $this->select_all("SELECT * FROM tmp_exhibitions");

	if($exhibitions) {

		foreach ($exhibitions as $row) {

			$exhibition_id = $row['id'];
			echo("$exhibition_id ");

			$title = mysql_real_escape_string($row['title']);
			$curator = mysql_real_escape_string($row['curator']);
			$venue = mysql_real_escape_string($row['venue']);
			$city = mysql_real_escape_string($row['city']);
			$country = mysql_real_escape_string($row['country']);
			$remarks = mysql_real_escape_string($row['remarks']);
			$type = mysql_real_escape_string($row['type']);

			$earliest_date = $row['earliest_date']; 
			$latest_date = $row['latest_date'];
			$earliest_date_format = $row['earliest_date_format'];
			$latest_date_format = $row['latest_date_format'];
			$date_created = $row['date_created'];
			$date_modified = $row['date_modified'];
			$slug = $row['slug'];

			$conflicts = $this->select_one("SELECT count(*) as conflicts FROM archives WHERE slug = '$slug'");
			$count = $conflicts['conflicts'];

			if($count) {
				$slug = $slug . '-exhibition';
			}

			$classification = 'Exhibition';

			$archive_id = $this->execute("INSERT INTO archives (name, controller, classification, type, slug, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified) VALUES ('$title', 'exhibitions', '$classification', '$type', '$slug', '$earliest_date', '$latest_date', '$earliest_date_format', '$latest_date_format', '$date_created', '$date_modified')");

			$archive_id = mysql_insert_id();
			
			$this->execute("INSERT INTO exhibitions (id, title, curator, venue, city, country, remarks) VALUES ('$archive_id', '$title', '$curator', '$venue', '$city', '$country', '$remarks')");

			$this->execute("UPDATE exhibitions_documents SET exhibition_id = '$archive_id' WHERE id in (SELECT id from tmp_exhibitions_documents WHERE exhibition_id = '$exhibition_id')");
			$this->execute("UPDATE exhibitions_works SET exhibition_id = '$archive_id' WHERE id in (SELECT id from tmp_exhibitions_works WHERE exhibition_id = '$exhibition_id')");
			$this->execute("UPDATE exhibitions_links SET exhibition_id = '$archive_id' WHERE id in (SELECT id from tmp_exhibitions_links WHERE exhibition_id = '$exhibition_id')");

		}

		//Fix the history dates
		$this->execute("UPDATE archives_histories SET start_date = UNIX_TIMESTAMP(date_modified) WHERE controller='exhibitions'");
		$this->execute("UPDATE exhibitions_histories left join archives_histories on exhibitions_histories.exhibition_id = archives_histories.archive_id set exhibitions_histories.start_date = archives_histories.start_date");

	}

  }//up()

  public function down() {

	$this->execute("DROP TRIGGER ExhibitionsHistoriesTableInsert");
	$this->execute("DROP TRIGGER ExhibitionsHistoriesTableDelete");
	$this->execute("DROP TRIGGER ExhibitionsHistoriesTableUpdate");	

  	$this->execute("CREATE TEMPORARY TABLE tmp_exhibitions as (SELECT * FROM exhibitions)");	
	$this->drop_table("exhibitions");
	$this->drop_table("exhibitions_histories");

	$t = $this->create_table("exhibitions");
	$t->column("title", "string", array('limit' => 2048));

	$t->column("curator", "string", array("null" => false));
	$t->column("venue", "string", array("null" => false));
	$t->column("city", "string", array("null" => false));
	$t->column("country", "string", array("null" => false));
	$t->column("remarks", "text", array("null" => false));
	$t->column("type", "string", array("null" => false));

	$t->column("earliest_date", "date", array("null" => false));
	$t->column("latest_date", "date", array("null" => false));
	$t->column("earliest_date_format", "string" , array('limit' => 5, 'null' => false));
	$t->column("latest_date_format", "string", array('limit' => 5, 'null' => false));

	$t->column("slug", "string", array("null" => false));
	$t->column("date_created", "datetime", array("null" => false));
	$t->column("date_modified", "timestamp", array("null" => false));
	$t->finish();

	$this->add_index("exhibitions", "slug", array("unique" => true));

	$exhibitions = $this->execute("select tmp_exhibitions.id as id, title, curator, venue, city, country, remarks, type, earliest_date, latest_date, earliest_date_format, latest_date_format, slug, date_created, date_modified from tmp_exhibitions left join archives on tmp_exhibitions.id = archives.id");


	if($exhibitions) {

		foreach($exhibitions as $row) {

			$id = $row['id'];

			$title = mysql_real_escape_string($row['title']);
			$curator = mysql_real_escape_string($row['curator']);
			$venue = mysql_real_escape_string($row['venue']);
			$city = mysql_real_escape_string($row['city']);
			$country = mysql_real_escape_string($row['country']);
			$remarks = mysql_real_escape_string($row['remarks']);
			$type = mysql_real_escape_string($row['type']);

			$earliest_date = $row['earliest_date']; 
			$latest_date = $row['latest_date'];
			$earliest_date_format = $row['earliest_date_format'];
			$latest_date_format = $row['latest_date_format'];
			$date_created = $row['date_created'];
			$date_modified = $row['date_modified'];
			$slug = $row['slug'];

			$this->execute("INSERT INTO exhibitions (id, title, curator, venue, city, country, remarks, type, earliest_date, latest_date, earliest_date_format, latest_date_format, slug, date_created, date_modified) VALUES ('$id', '$title', '$curator', '$venue', '$city', '$country', '$remarks', '$type', '$earliest_date', '$latest_date', '$earliest_date_format', '$latest_date_format', '$slug', '$date_created', '$date_modified')");

		}
	}

	$this->execute("DELETE FROM archives WHERE controller = 'exhibitions'");
	$this->execute("DELETE FROM archives_histories WHERE controller = 'exhibitions'");

  }//down()
}
?>
