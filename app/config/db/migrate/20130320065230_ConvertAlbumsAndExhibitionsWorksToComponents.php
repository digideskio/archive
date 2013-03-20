<?php

class ConvertAlbumsAndExhibitionsWorksToComponents extends Ruckusing_BaseMigration {

  public function up() {

	$this->execute("INSERT INTO components (archive_id1, archive_id2, type, date_created, date_modified) SELECT album_id, work_id, 'albums_works', GREATEST(Albums.date_created, Works.date_created) as date_created, GREATEST(Albums.date_modified, Works.date_modified) as date_modified FROM albums_works, archives Albums, archives Works WHERE Albums.id = album_id AND Works.id = work_id");

	$this->execute("INSERT INTO components (archive_id1, archive_id2, type, date_created, date_modified) SELECT exhibition_id, work_id, 'exhibitions_works', GREATEST(Exhibitions.date_created, Works.date_created) as date_created, GREATEST(Exhibitions.date_modified, Works.date_modified) as date_modified FROM exhibitions_works, archives Exhibitions, archives Works WHERE Exhibitions.id = exhibition_id AND Works.id = work_id");

	$this->drop_table("albums_works");
	$this->drop_table("exhibitions_works");

  }//up()

  public function down() {

	$t = $this->create_table("albums_works");

	$t->column("album_id", "integer", array(
		"unsigned" => true, 
		"null" => false
	));
	$t->column("work_id", "integer", array(
		"unsigned" => true, 
		"null" => false
	));
	
	$t->finish();

	$t = $this->create_table("exhibitions_works");

	$t->column("exhibition_id", "integer", array(
		"unsigned" => true, 
		"null" => false
	));
	$t->column("work_id", "integer", array(
		"unsigned" => true, 
		"null" => false
	));
	$t->finish();

	$this->execute("INSERT INTO albums_works (album_id, work_id) SELECT archive_id1, archive_id2 FROM components where type = 'albums_works'");
	$this->execute("INSERT INTO exhibitions_works (exhibition_id, work_id) SELECT archive_id1, archive_id2 FROM components where type = 'exhibitions_works'");


  	$this->execute("DELETE FROM components");

  }//down()
}
?>
