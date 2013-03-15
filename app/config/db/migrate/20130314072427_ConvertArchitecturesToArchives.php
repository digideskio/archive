<?php

class ConvertArchitecturesToArchives extends Ruckusing_BaseMigration {

  public function up() {

  	$this->execute("CREATE TEMPORARY TABLE tmp_architectures as (SELECT * FROM architectures)");	
	$this->drop_table("architectures");
	$this->drop_table("architectures_links"); //never used

	//Create the Architecture and Architecture History Tables
	$tables = array('architectures', 'architectures_histories');

	foreach ($tables as $table) {

		$t = $this->create_table($table, array("id" => false));

		if ($table == 'architectures') {
			$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "null" => false));
		}

		if ($table == 'architectures_histories') {
			$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "auto_increment" => true));
			$t->column("architecture_id", "integer", array("unsigned" => true, "null" => false));
		}

		$t->column("title", "string", array("limit" => 1024, "null" => false));
		$t->column("architect", "string", array("null" => false));
		$t->column("creation_number", "string", array("null" => false));
		$t->column("client", "string", array("null" => false));
		$t->column("project_lead", "string", array("null" => false));
		$t->column("consultants", "string", array("null" => false));
		$t->column("partners", "string", array("null" => false));
		$t->column("address", "string", array("limit" => 1024, "null" => false));
		$t->column("location", "string", array("null" => false));
		$t->column("city", "string", array("null" => false));
		$t->column("country", "string", array("null" => false));
		$t->column("status", "string", array("null" => false));
		$t->column("materials", "string", array("null" => false));
		$t->column("techniques", "string", array("null" => false));
		$t->column("annotation", "text", array("null" => false));
		$t->column("area", "float", array("null" => false));
		$t->column("grounds", "float", array("null" => false));
		$t->column("interior", "float", array("null" => false));
		$t->column("height", "float", array("null" => false));
		$t->column("stories", "integer", array("null" => false));
		$t->column("rooms", "integer", array("null" => false));
		$t->column("measurement_remarks", "text", array("null" => false));
		$t->column("attributes", "text", array("null" => false));
		$t->column("remarks", "text", array("null" => false));

		if ($table == 'architectures_histories' ) {
			$t->column("start_date", "integer", array("unsigned" => true));
			$t->column("end_date", "integer", array("unsigned" => true));
		}

		$t->finish();

	}

	$this->execute("CREATE TRIGGER ArchitecturesHistoriesTableInsert AFTER INSERT ON architectures FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO architectures_histories (architecture_id, title, architect, creation_number, client, project_lead, consultants, partners, address, location, city, country, status, materials, techniques, annotation, area, grounds, interior, height, stories, rooms, measurement_remarks, attributes, remarks, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.architect, NEW.creation_number, NEW.client, NEW.project_lead, NEW.consultants, NEW.partners, NEW.address, NEW.location, NEW.city, NEW.country, NEW.status, NEW.materials, NEW.techniques, NEW.annotation, NEW.area, NEW.grounds, NEW.interior, NEW.height, NEW.stories, NEW.rooms, NEW.measurement_remarks, NEW.attributes, NEW.remarks, N, NULL); END");
	$this->execute("CREATE TRIGGER ArchitecturesHistoriesTableDelete AFTER DELETE ON architectures FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE architectures_histories SET end_date = N WHERE architecture_id = OLD.id AND end_date IS NULL; END");
	$this->execute("CREATE TRIGGER ArchitecturesHistoriesTableUpdate AFTER UPDATE ON architectures FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE architectures_histories SET end_date = N WHERE architecture_id = OLD.id AND end_date IS NULL; INSERT INTO architectures_histories (architecture_id, title, architect, creation_number, client, project_lead, consultants, partners, address, location, city, country, status, materials, techniques, annotation, area, grounds, interior, height, stories, rooms, measurement_remarks, attributes, remarks, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.architect, NEW.creation_number, NEW.client, NEW.project_lead, NEW.consultants, NEW.partners, NEW.address, NEW.location, NEW.city, NEW.country, NEW.status, NEW.materials, NEW.techniques, NEW.annotation, NEW.area, NEW.grounds, NEW.interior, NEW.height, NEW.stories, NEW.rooms, NEW.measurement_remarks, NEW.attributes, NEW.remarks, N, NULL); END");	

	//Copy fields from architectures into archives
	$this->execute("CREATE TEMPORARY TABLE tmp_architectures_documents as (SELECT * FROM architectures_documents)");

	$architectures = $this->select_all("SELECT * FROM tmp_architectures");

	if($architectures) {

		foreach ($architectures as $row) {

			$architecture_id = $row['id'];
			echo("$architecture_id ");

			$title = mysql_real_escape_string($row['title']);
			$client = mysql_real_escape_string($row['client']);
			$project_lead = mysql_real_escape_string($row['project_lead']);
			$status = mysql_real_escape_string($row['status']);
			$location = mysql_real_escape_string($row['location']);
			$city = mysql_real_escape_string($row['city']);
			$country = mysql_real_escape_string($row['country']);
			$remarks = mysql_real_escape_string($row['remarks']);
			$earliest_date = $row['earliest_date'];
			$latest_date = $row['latest_date'];
			$slug = $row['slug'];
			$date_created = $row['date_created'];
			$date_modified = $row['date_modified'];
			$earliest_date_format = $row['earliest_date_format'];
			$latest_date_format = $row['latest_date_format'];
			$architect = mysql_real_escape_string($row['architects']);
			$consultants = mysql_real_escape_string($row['consultants']);
			$materials = mysql_real_escape_string($row['materials']);
			$area = $row['area'];
			$annotation = mysql_real_escape_string($row['annotation']);

			$archive_id = $this->execute("INSERT INTO archives (name, controller, slug, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified) VALUES ('$title', 'architectures', '$slug', '$earliest_date', '$latest_date', '$earliest_date_format', '$latest_date_format', '$date_created', '$date_modified')");

			$archive_id = mysql_insert_id();

			$this->execute("INSERT INTO architectures (id, title, client, project_lead, status, location, city, country, remarks, architect, consultants, materials, area, annotation) VALUES ('$archive_id', '$title', '$client', '$project_lead', '$status', '$location', '$city', '$country', '$remarks', '$architect', '$consultants', '$materials', '$area', '$annotation')");

			$this->execute("UPDATE architectures_documents SET architecture_id = '$archive_id' WHERE id in (SELECT id from tmp_architectures_documents WHERE architecture_id = '$architecture_id')");
		}

		//Fix the history dates
		$this->execute("UPDATE archives_histories SET start_date = UNIX_TIMESTAMP(date_modified) WHERE controller='architectures'");
		$this->execute("UPDATE architectures_histories left join archives_histories on architectures_histories.architecture_id = archives_histories.archive_id set architectures_histories.start_date = archives_histories.start_date");

	}

  }//up()

  public function down() {

	$this->execute("DROP TRIGGER ArchitecturesHistoriesTableInsert");
	$this->execute("DROP TRIGGER ArchitecturesHistoriesTableDelete");
	$this->execute("DROP TRIGGER ArchitecturesHistoriesTableUpdate");	

  	$this->execute("CREATE TEMPORARY TABLE tmp_architectures as (SELECT * FROM architectures)");	
	$this->drop_table("architectures");
	$this->drop_table("architectures_histories");

	$t = $this->create_table("architectures");
	$t->column("title", "string", array("limit" => 2048, "null" => false));
	$t->column("client", "string", array("null" => false));
	$t->column("project_lead", "string", array("null" => false));
	$t->column("status", "string", array("null" => false));
	$t->column("location", "string", array("null" => false));
	$t->column("city", "string", array("null" => false));
	$t->column("country", "string", array("null" => false));
	$t->column("remarks", "text", array("null" => false));
	$t->column("earliest_date", "date", array("null" => false));
	$t->column("latest_date", "date", array("null" => false));
	$t->column("earliest_date_format", "string" , array('limit' => 5, 'null' => false));
	$t->column("latest_date_format", "string", array('limit' => 5, 'null' => false));
	$t->column("slug", "string", array("null" => false));
	$t->column("date_created", "datetime", array("null" => false));
	$t->column("date_modified", "timestamp", array("null" => false));
	$t->column("architects", "string", array("null" => false));
	$t->column("consultants", "string", array("null" => false));
	$t->column("materials", "string", array("null" => false));
	$t->column("area", "float", array("null" => false));
	$t->column("annotation", "text", array("null" => false));
	$t->finish();

	$this->add_index("architectures", "slug", array("unique" => true));

	$architectures = $this->execute("select tmp_architectures.id as id, title, client, project_lead, status, location, city, country, remarks, earliest_date, latest_date, earliest_date_format, latest_date_format, slug, date_created, date_modified, architect, consultants, materials, area, annotation from tmp_architectures left join archives on tmp_architectures.id = archives.id");

	if ($architectures) {

		foreach ($architectures as $row) {

			$id = $row['id'];

			$title = mysql_real_escape_string($row['title']);
			$client = mysql_real_escape_string($row['client']);
			$project_lead = mysql_real_escape_string($row['project_lead']);
			$status = mysql_real_escape_string($row['status']);
			$location = mysql_real_escape_string($row['location']);
			$city = mysql_real_escape_string($row['city']);
			$country = mysql_real_escape_string($row['country']);
			$remarks = mysql_real_escape_string($row['remarks']);
			$earliest_date = $row['earliest_date'];
			$latest_date = $row['latest_date'];
			$slug = $row['slug'];
			$date_created = $row['date_created'];
			$date_modified = $row['date_modified'];
			$earliest_date_format = $row['earliest_date_format'];
			$latest_date_format = $row['latest_date_format'];
			$architects = mysql_real_escape_string($row['architect']);
			$consultants = mysql_real_escape_string($row['consultants']);
			$materials = mysql_real_escape_string($row['materials']);
			$area = $row['area'];
			$annotation = mysql_real_escape_string($row['annotation']);

			$this->execute("INSERT INTO architectures (id, title, client, project_lead, status, location, city, country, remarks, earliest_date, latest_Date, slug, date_created, date_modified, earliest_date_format, latest_date_format, architects, consultants, materials, area, annotation) VALUES ('$id', '$title', '$client', '$project_lead', '$status', '$location', '$city', '$country', '$remarks', '$earliest_date', '$latest_date', '$slug', '$date_created', '$date_modified', '$earliest_date_format', '$latest_date_format', '$architects', '$consultants', '$materials', '$area', '$annotation')");

		}

	}

	$this->execute("DELETE FROM archives WHERE controller = 'architectures'");
	$this->execute("DELETE FROM archives_histories WHERE controller = 'architectures'");

  }//down()
}
?>
