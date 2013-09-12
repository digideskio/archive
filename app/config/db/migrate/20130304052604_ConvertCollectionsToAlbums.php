<?php

class ConvertCollectionsToAlbums extends Ruckusing_Migration_Base {

  public function up() {

  	$this->rename_table("collections", "albums");	
  	$this->rename_table("collections_works", "albums_works");	
	$this->rename_column("albums_works", "collection_id", "album_id");
	$this->rename_column("packages", "collection_id", "album_id");

  }//up()

  public function down() {

	$this->rename_table("albums", "collections");
	$this->rename_table("albums_works", "collections_works");
	$this->rename_column("collections_works", "album_id", "collection_id");
	$this->rename_column("packages", "album_id", "collection_id");

  }//down()
}
?>
