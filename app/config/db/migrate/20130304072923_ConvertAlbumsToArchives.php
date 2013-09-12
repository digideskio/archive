<?php

class ConvertAlbumsToArchives extends Ruckusing_Migration_Base {

  public function up() {

  	$this->execute("CREATE TEMPORARY TABLE tmp_albums as (SELECT * FROM albums)");	
	$this->drop_table("albums");

	//Create the Albums and Albums History Tables
	$tables = array('albums', 'albums_histories');

	foreach ($tables as $table) {

		$t = $this->create_table($table, array("id" => false));

		if ($table == 'albums') {
			$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "null" => false));
		}

		if ($table == 'albums_histories') {
			$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "auto_increment" => true));
			$t->column("album_id", "integer", array("unsigned" => true, "null" => false));
		}

		$t->column("title", "string", array("limit" => 1024, "null" => false));
		$t->column("remarks", "text", array("null" => false));

		if ($table == 'albums_histories' ) {
			$t->column("start_date", "integer", array("unsigned" => true));
			$t->column("end_date", "integer", array("unsigned" => true));
		}

		$t->finish();

	}

	$this->add_index("albums_histories", "album_id");  

	$this->execute("CREATE TRIGGER AlbumsHistoriesTableInsert AFTER INSERT ON albums FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO albums_histories (album_id, title, remarks, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.remarks, N, NULL); END");
	$this->execute("CREATE TRIGGER AlbumsHistoriesTableDelete AFTER DELETE ON albums FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE albums_histories SET end_date = N WHERE album_id = OLD.id AND end_date IS NULL; END");
	$this->execute("CREATE TRIGGER AlbumsHistoriesTableUpdate AFTER UPDATE ON albums FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE albums_histories SET end_date = N WHERE album_id = OLD.id AND end_date IS NULL; INSERT INTO albums_histories (album_id, title, remarks, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.remarks, N, NULL); END");	

  	//Copy fields from Albums into Archives

	$this->execute("CREATE TEMPORARY TABLE tmp_albums_works as (SELECT * FROM albums_works)");

	$albums = $this->select_all("SELECT * FROM tmp_albums");

	if($albums) {

		foreach ($albums as $row) {

			$album_id = $row['id'];
			$title = mysql_real_escape_string($row['title']);
			$remarks = mysql_real_escape_string($row['description']);
			$slug = $row['slug'];
			$date_created = $row['date_created'];
			$date_modified = $row['date_modified'];

			/*$conflicts = $this->select_one("SELECT count(*) FROM archives WHERE slug = '$slug'"); 

			if($conflicts){
				$i = 0;
				newSlug = '';
				while($conflicts){
					$i++;
					$newSlug = "{$slug}-{$i}";
					$conflicts = $this->select_one("SELECT count(*) FROM archives WHERE slug = '$newSlug'"); 
				}
				$slug = $newSlug;
			}*/

			$archive_id = $this->execute("INSERT INTO archives (name, controller, slug, date_created, date_modified) VALUES ('$title', 'albums', '$slug', '$date_created', '$date_modified')");

			$archive_id = mysql_insert_id();
			
			$this->execute("INSERT INTO albums (id, title, remarks) VALUES ('$archive_id', '$title', '$remarks')");

			$this->execute("UPDATE albums_works SET album_id = '$archive_id' WHERE id in (SELECT id from tmp_albums_works WHERE album_id = '$album_id')");

		}

	}

  }//up()

  public function down() {

	$this->execute("DROP TRIGGER AlbumsHistoriesTableInsert");
	$this->execute("DROP TRIGGER AlbumsHistoriesTableDelete");
	$this->execute("DROP TRIGGER AlbumsHistoriesTableUpdate");	

  	$this->execute("CREATE TEMPORARY TABLE tmp_albums as (SELECT * FROM albums)");	
	$this->drop_table("albums");
	$this->drop_table("albums_histories");

	$t = $this->create_table("albums");

	$t->column("title", "string", array("null" => false));
	$t->column("description", "text", array("null" => false));
	$t->column("slug", "string", array("null" => false));
	$t->column("date_created", "datetime", array("null" => false));
	$t->column("date_modified", "timestamp", array("null" => false));
	$t->finish();

	$this->add_index("albums", "slug", array("unique" => true));

	$albums = $this->execute("select tmp_albums.id as id, title, remarks as description, slug, date_created, date_modified from tmp_albums left join archives on tmp_albums.id = archives.id");


	if($albums) {

		foreach($albums as $row) {

			$id = $row['id'];
			$title = mysql_real_escape_string($row['title']);
			$description = mysql_real_escape_string($row['description']);
			$slug = $row['slug'];
			$date_created = $row['date_created'];
			$date_modified = $row['date_modified'];

			$this->execute("INSERT INTO albums (id, title, description, slug, date_created, date_modified) VALUES ('$id', '$title', '$description', '$slug', '$date_created', '$date_modified')");

		}
	}

	$this->execute("DELETE FROM archives WHERE controller = 'albums'");
	

  }//down()

}
?>
