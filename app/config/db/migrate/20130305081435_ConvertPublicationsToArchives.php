<?php

class ConvertPublicationsToArchives extends Ruckusing_Migration_Base {

  public function up() {

  	$this->execute("CREATE TEMPORARY TABLE tmp_publications as (SELECT * FROM publications)");	
	$this->drop_table("publications");

	//Create the Publications and Publication History Tables
	$tables = array('publications', 'publications_histories');

	foreach ($tables as $table) {

		$t = $this->create_table($table, array("id" => false));

		if ($table == 'publications') {
			$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "null" => false));
		}

		if ($table == 'publications_histories') {
			$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "auto_increment" => true));
			$t->column("publication_id", "integer", array("unsigned" => true, "null" => false));
		}

		$t->column("title", "string", array("limit" => 1024, "null" => false));
		$t->column("remarks", "text", array("null" => false));
		$t->column("storage_location", "string", array("null" => false));
		$t->column("storage_number", "string", array("null" => false));
		$t->column("publication_number", "string", array("null" => false));
		$t->column("subject", "string", array("null" => false));
		$t->column("attributes", "text", array("null" => false));
		$t->column("language", "string", array("null" => false));

		$t->column("access_date", "string", array("null" => false));
		$t->column("address", "string", array("limit" => 1024, "null" => false));
		$t->column("annotation", "text", array("null" => false));
		$t->column("author", "string", array("null" => false));
		$t->column("book_title", "string", array("limit" => 1024, "null" => false));
		$t->column("chapter", "string", array("limit" => 1024, "null" => false));
		$t->column("edition", "string", array("null" => false));
		$t->column("editor", "string", array("null" => false));
		$t->column("format", "string", array("null" => false));
		$t->column("how_published", "string", array("null" => false));
		$t->column("identifier", "string", array("null" => false));
		$t->column("institution", "string", array("null" => false));
		$t->column("isbn", "string", array("null" => false));
		$t->column("journal", "string", array("limit" => 1024, "null" => false));
		$t->column("location", "string", array("null" => false));
		$t->column("note", "text", array("null" => false));
		$t->column("number", "string", array("null" => false));
		$t->column("organization", "string", array("null" => false));
		$t->column("original_date", "string", array("null" => false));
		$t->column("pages", "string", array("null" => false));
		$t->column("publisher", "string", array("null" => false));
		$t->column("school", "string", array("null" => false));
		$t->column("series", "string", array("null" => false));
		$t->column("translator", "string", array("null" => false));
		$t->column("url", "string", array("null" => false));
		$t->column("volume", "string", array("null" => false));

		if ($table == 'publications_histories' ) {
			$t->column("start_date", "integer", array("unsigned" => true));
			$t->column("end_date", "integer", array("unsigned" => true));
		}

		$t->finish();

	}

	$this->add_index("publications_histories", "publication_id");  

	$this->execute("CREATE TRIGGER PublicationsHistoriesTableInsert AFTER INSERT ON publications FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO publications_histories (publication_id, title, remarks, storage_location, storage_number, publication_number, subject, attributes, language, access_date, address, annotation, author, book_title, chapter, edition, editor, format, how_published, identifier, institution, isbn, journal, location, note, number, organization, original_date, pages, publisher, school, series, translator, url, volume, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.remarks, NEW.storage_location, NEW.storage_number, NEW.publication_number, NEW.subject, NEW.attributes, NEW.language, NEW.access_date, NEW.address, NEW.annotation, NEW.author, NEW.book_title, NEW.chapter, NEW.edition, NEW.editor, NEW.format, NEW.how_published, NEW.identifier, NEW.institution, NEW.isbn, NEW.journal, NEW.location, NEW.note, NEW.number, NEW.organization, NEW.original_date, NEW.pages, NEW.publisher, NEW.school, NEW.series, NEW.translator, NEW.url, NEW.volume, N, NULL); END");
	$this->execute("CREATE TRIGGER PublicationsHistoriesTableDelete AFTER DELETE ON publications FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE publications_histories SET end_date = N WHERE publication_id = OLD.id AND end_date IS NULL; END");
	$this->execute("CREATE TRIGGER PublicationsHistoriesTableUpdate AFTER UPDATE ON publications FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE publications_histories SET end_date = N WHERE publication_id = OLD.id AND end_date IS NULL; INSERT INTO publications_histories (publication_id, title, remarks, storage_location, storage_number, publication_number, subject, attributes, language, access_date, address, annotation, author, book_title, chapter, edition, editor, format, how_published, identifier, institution, isbn, journal, location, note, number, organization, original_date, pages, publisher, school, series, translator, url, volume, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.remarks, NEW.storage_location, NEW.storage_number, NEW.publication_number, NEW.subject, NEW.attributes, NEW.language, NEW.access_date, NEW.address, NEW.annotation, NEW.author, NEW.book_title, NEW.chapter, NEW.edition, NEW.editor, NEW.format, NEW.how_published, NEW.identifier, NEW.institution, NEW.isbn, NEW.journal, NEW.location, NEW.note, NEW.number, NEW.organization, NEW.original_date, NEW.pages, NEW.publisher, NEW.school, NEW.series, NEW.translator, NEW.url, NEW.volume, N, NULL); END");	

  	//Copy fields from Albums into Archives

	$this->execute("CREATE TEMPORARY TABLE tmp_publications_documents as (SELECT * FROM publications_documents)");
	$this->execute("CREATE TEMPORARY TABLE tmp_publications_links as (SELECT * FROM publications_links)");

	$publications = $this->select_all("SELECT * FROM tmp_publications");

	if($publications) {

		foreach ($publications as $row) {

			$publication_id = $row['id'];
			echo("$publication_id ");

			$title = mysql_real_escape_string($row['title']);
			$author = mysql_real_escape_string($row['author']);
			$publisher = mysql_real_escape_string($row['publisher']);
			$pages = mysql_real_escape_string($row['pages']);
			$subject = mysql_real_escape_string($row['subject']);
			$remarks = mysql_real_escape_string($row['remarks']);
			$interview = mysql_real_escape_string($row['interview']);
			$publication_number = mysql_real_escape_string($row['publication_number']);
			$storage_location = mysql_real_escape_string($row['storage_location']);
			$storage_number = mysql_real_escape_string($row['storage_number']);
			$location = ''; // was not being used // mysql_real_escape_string($row['location']);
			$address = mysql_real_escape_string($row['address']);
			$format = mysql_real_escape_string($row['format']);
			$language = mysql_real_escape_string($row['language']);
			$editor = mysql_real_escape_string($row['editor']);

			$classification = mysql_real_escape_string($row['type']);
			$earliest_date = $row['earliest_date']; 
			$latest_date = $row['latest_date'];
			$earliest_date_format = $row['earliest_date_format'];
			$latest_date_format = $row['latest_date_format'];
			$date_created = $row['date_created'];
			$date_modified = $row['date_modified'];
			$slug = $row['slug'];

			$language_code = '';

			if ($language) {

				$result = $this->select_one("SELECT code FROM languages WHERE '$language' LIKE CONCAT('%', name, '%') LIMIT 1");

				if ($result) {
					$language_code = $result['code'];
				}

			}

			$type = $interview ? 'Interview' : '';

			$archive_id = $this->execute("INSERT INTO archives (name, language_code, controller, classification, type, slug, earliest_date, latest_date, earliest_date_format, latest_date_format, date_created, date_modified) VALUES ('$title', '$language_code', 'publications', '$classification', '$type', '$slug', '$earliest_date', '$latest_date', '$earliest_date_format', '$latest_date_format', '$date_created', '$date_modified')");

			$archive_id = mysql_insert_id();
			
			$this->execute("INSERT INTO publications (id, title, author, editor, publisher, pages, subject, language, remarks, storage_location, storage_number, publication_number, address, format) VALUES ('$archive_id', '$title', '$author', '$editor', '$publisher', '$pages', '$subject', '$language', '$remarks', '$storage_location', '$storage_number', '$publication_number', '$address', '$format')");

			$this->execute("UPDATE publications_documents SET publication_id = '$archive_id' WHERE id in (SELECT id from tmp_publications_documents WHERE publication_id = '$publication_id')");
			$this->execute("UPDATE publications_links SET publication_id = '$archive_id' WHERE id in (SELECT id from tmp_publications_links WHERE publication_id = '$publication_id')");

		}

		//Fix the history dates
		$this->execute("UPDATE archives_histories SET start_date = UNIX_TIMESTAMP(date_modified) WHERE controller='publications'");
		$this->execute("UPDATE publications_histories left join archives_histories on publications_histories.publication_id = archives_histories.archive_id set publications_histories.start_date = archives_histories.start_date");

	}

  }//up()

  public function down() {

	$this->execute("DROP TRIGGER PublicationsHistoriesTableInsert");
	$this->execute("DROP TRIGGER PublicationsHistoriesTableDelete");
	$this->execute("DROP TRIGGER PublicationsHistoriesTableUpdate");	

  	$this->execute("CREATE TEMPORARY TABLE tmp_publications as (SELECT * FROM publications)");	
	$this->drop_table("publications");
	$this->drop_table("publications_histories");

	$t = $this->create_table("publications");
	$t->column("title", "string", array('limit' => 2048));
	$t->column("author", "string", array("null" => false));
	$t->column("publisher", "string", array("null" => false));
	$t->column("earliest_date", "date", array("null" => false));
	$t->column("latest_date", "date", array("null" => false));
	$t->column("earliest_date_format", "string" , array('limit' => 5, 'null' => false));
	$t->column("latest_date_format", "string", array('limit' => 5, 'null' => false));
	$t->column("pages", "string", array("null" => false));
	$t->column("subject", "string", array('limit' => 2048, "null" => "false"));
	$t->column("remarks", "text", array("null" => false));
	$t->column("language", "string", array("null" => false));
	$t->column("interview", "integer", array('limit' => 1, "null" => "false"));
	$t->column("storage_location", "string", array("null" => false));
	$t->column("storage_number", "string", array("null" => false));
	$t->column("publication_number", "string", array("null" => false));
	$t->column("slug", "string", array("null" => false));
	$t->column("editor", "string", array("null" => FALSE));
	$t->column("location", "string", array("null" => false));
	$t->column("type", "string", array("null" => false));
	$t->column("address", "string", array("null" => false));
	$t->column("format", "string", array("null" => false));
	$t->column("subject_date", "string", array("null" => false));
	$t->column("date_created", "datetime", array("null" => false));
	$t->column("date_modified", "timestamp", array("null" => false));
	$t->finish();

	$this->add_index("publications", "slug", array("unique" => true));

	$publications = $this->execute("select tmp_publications.id as id, title, author, publisher, earliest_date, latest_date, earliest_date_format, latest_date_format, pages, subject, remarks, language, classification, type, storage_location, storage_number, publication_number, slug, editor, location, address, format, date_created, date_modified from tmp_publications left join archives on tmp_publications.id = archives.id");


	if($publications) {

		foreach($publications as $row) {

			$id = $row['id'];

			$title = mysql_real_escape_string($row['title']);
			$author = mysql_real_escape_string($row['author']);
			$publisher = mysql_real_escape_string($row['publisher']);
			$earliest_date = mysql_real_escape_string($row['earliest_date']);
			$latest_date = mysql_real_escape_string($row['latest_date']);
			$earliest_date_format = mysql_real_escape_string($row['earliest_date_format']);
			$latest_date_format = mysql_real_escape_string($row['latest_date_format']);
			$pages = mysql_real_escape_string($row['pages']);
			$subject = mysql_real_escape_string($row['subject']);
			$remarks = mysql_real_escape_string($row['remarks']);
			$language = mysql_real_escape_string($row['language']);
			$classification = mysql_real_escape_string($row['classification']);
			$type = mysql_real_escape_string($row['type']);
			$storage_location = mysql_real_escape_string($row['storage_location']);
			$storage_number = mysql_real_escape_string($row['storage_number']);
			$publication_number = mysql_real_escape_string($row['publication_number']);
			$slug = $row['slug'];
			$editor = mysql_real_escape_string($row['editor']);
			$location = mysql_real_escape_string($row['location']);
			$address = mysql_real_escape_string($row['address']);
			$format = mysql_real_escape_string($row['format']);
			$date_created = $row['date_created'];
			$date_modified = $row['date_modified'];

			$interview = ($type == "Interview") ? '1' : '0';

			$this->execute("INSERT INTO publications (id, title, author, publisher, earliest_date, latest_date, earliest_date_format, latest_date_format, pages, subject, remarks, language, type, interview, storage_location, storage_number, publication_number, slug, editor, address, format, date_created, date_modified) VALUES ('$id', '$title', '$author', '$publisher', '$earliest_date', '$latest_date', '$earliest_date_format', '$latest_date_format', '$pages', '$subject', '$remarks', '$language', '$classification', '$interview', '$storage_location', '$storage_number', '$publication_number', '$slug', '$editor', '$address', '$format', '$date_created', '$date_modified')");

		}
	}

	$this->execute("DELETE FROM archives WHERE controller = 'publications'");
	

  }//down()

}
?>
