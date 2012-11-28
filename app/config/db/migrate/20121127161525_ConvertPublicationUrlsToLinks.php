<?php

class ConvertPublicationUrlsToLinks extends Ruckusing_BaseMigration {

  public function up() {

	$result = $this->select_all("SELECT id, title, url, date_created, date_modified FROM publications WHERE url != ''");

	if($result) {
		
		foreach ($result as $row) {
			
			$publication_id = $row['id'];
			$title = mysql_real_escape_string($row['title']);
			$url = mysql_real_escape_string($row['url']);
			$date_created = $row['date_created'];
			$date_modified = $row['date_modified'];

			//FIXME don't create a new URL if it already exists in the links table
			$this->execute("INSERT INTO links (title, url, date_created, date_modified) VALUES ('$title', '$url', '$date_created', '$date_modified')");

			$link_id = mysql_insert_id();

			$this->execute("INSERT INTO publications_links (publication_id, link_id) VALUES ('$publication_id', '$link_id')");

		}
	}

	$this->execute('ALTER TABLE publications DROP COLUMN url');

  }//up()

  public function down() {

	$this->execute("ALTER TABLE publications ADD COLUMN url varchar(2048) not null AFTER pages");

	$result = $this->select_all("SELECT * FROM publications_links");

	if ($result) {
		
		foreach ($result as $row) {
			$publication_id = $row['publication_id'];
			$link_id = $row['link_id'];

			$link = $this->select_one("SELECT * FROM links WHERE id = '$link_id'");
			$url = $link['url'];

			$this->execute("UPDATE publications SET url = '$url' WHERE id = '$publication_id'");
		}

		$this->execute("DELETE FROM links WHERE id in (SELECT link_id FROM publications_links)");
	}


  }//down()
}
?>
