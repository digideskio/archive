<?php

class SetNotNullDefaults extends Ruckusing_Migration_Base {

  public function up() {
  
		$this->change_column("architectures", "title", "string", array("null" => false));

		$this->change_column("architectures", "client", "string", array("null" => false));
		$this->change_column("architectures", "project_lead", "string", array("null" => false));
		$this->change_column("architectures", "status", "string", array("null" => false));
		$this->change_column("architectures", "location", "string", array("null" => false));
		$this->change_column("architectures", "city", "string", array("null" => false));
		$this->change_column("architectures", "country", "string", array("null" => false));
		$this->change_column("architectures", "remarks", "text", array("null" => false));
		$this->change_column("architectures", "earliest_date", "date", array("null" => false));
		$this->change_column("architectures", "latest_date", "date", array("null" => false));
		$this->change_column("architectures", "slug", "string", array("null" => false));
		$this->change_column("architectures", "date_created", "datetime", array("null" => false));
		$this->change_column("architectures", "date_modified", "datetime", array("null" => false));

		$this->change_column("collections", "title", "string", array("null" => false));
		$this->change_column("collections", "description", "text", array("null" => false));
		$this->change_column("collections", "slug", "string", array("null" => false));

		$this->change_column("collections", "class", "string", array("null" => false));

		$this->change_column("documents", "title", "string", array("null" => false));
		$this->change_column("documents", "hash", "string", array('limit' => 32, "null" => false));
		$this->change_column("documents", "repository", "string", array("null" => false));
		$this->change_column("documents", "file_date", "date", array("null" => false));
		$this->change_column("documents", "credit", "string", array("null" => false));
		$this->change_column("documents", "remarks", "text", array("null" => false));
		$this->change_column("documents", "slug", "string", array("null" => false));
		$this->change_column("documents", "date_created", "datetime", array("null" => false));
		$this->change_column("documents", "date_modified", "timestamp", array("null" => false));

		$this->change_column("documents", "width", "integer", array("null" => false));
		$this->change_column("documents", "height", "integer", array("null" => false));


		$this->change_column("exhibitions", "curator", "string", array("null" => false));
		$this->change_column("exhibitions", "venue", "string", array("null" => false));
		$this->change_column("exhibitions", "city", "string", array("null" => false));
		$this->change_column("exhibitions", "country", "string", array("null" => false));
		$this->change_column("exhibitions", "remarks", "text", array("null" => false));
		$this->change_column("exhibitions", "type", "string", array("null" => false));


		$this->change_column("formats", "extension", "string", array('limit' => 128, "null" => false));
		$this->change_column("formats", "mime_type", "string", array('limit' => 128, "null" => false));


		$this->change_column("publications", "title", "string", array('limit' => 2048, "null" => false));
		$this->change_column("publications", "author", "string", array("null" => false));
		$this->change_column("publications", "publisher", "string", array("null" => false));
		$this->change_column("publications", "earliest_date", "date", array("null" => false));
		$this->change_column("publications", "latest_date", "date", array("null" => false));
		$this->change_column("publications", "pages", "string", array("null" => false));
		$this->change_column("publications", "url", "string", array('limit' => 2048, "null" => false));
		$this->change_column("publications", "subject", "string", array('limit' => 2048, "null" => false));
		$this->change_column("publications", "remarks", "text", array("null" => false));
		$this->change_column("publications", "language", "string", array("null" => false));
		$this->change_column("publications", "interview", "integer", array('limit' => 1, "null" => false));
		$this->change_column("publications", "location", "string", array("null" => false));
		$this->change_column("publications", "location_code", "string", array("null" => false));
		$this->change_column("publications", "publication_number", "string", array("null" => false));
		$this->change_column("publications", "slug", "string", array("null" => false));
		$this->change_column("publications", "date_created", "datetime", array("null" => false));
		$this->change_column("publications", "date_modified", "timestamp", array("null" => false));


		$this->change_column("roles", "name", "string", array("null" => false));


		$this->change_column("users", "username", "string", array("null" => false));
		$this->change_column("users", "name", "string", array("null" => false));
		$this->change_column("users", "email", "string", array("limit" => 255, "null" => false));
		$this->change_column("users", "password", "string", array("null" => false));




		$this->change_column("works", "artist", "string", array("null" => false));
		$this->change_column("works", "title", "string", array("null" => false));
		$this->change_column("works", "classification", "string", array("null" => false));
		$this->change_column("works", "materials", "text", array("null" => false));
		$this->change_column("works", "quantity", "string", array("null" => false));
		$this->change_column("works", "location", "string", array("null" => false));
		$this->change_column("works", "lender", "string", array("null" => false));
		$this->change_column("works", "remarks", "text", array("null" => false));
		$this->change_column("works", "earliest_date", "datetime", array("null" => false));
		$this->change_column("works", "latest_date", "datetime", array("null" => false));
		$this->change_column("works", "creation_number", "string", array("null" => false));
		$this->change_column("works", "height", "float", array("null" => false));
		$this->change_column("works", "width", "float", array("null" => false));
		$this->change_column("works", "depth", "float", array("null" => false));
		$this->change_column("works", "diameter", "float", array("null" => false));
		$this->change_column("works", "weight", "float", array("null" => false));
		$this->change_column("works", "running_time", "string", array("null" => false));
		$this->change_column("works", "measurement_remarks", "text", array("null" => false));
		$this->change_column("works", "slug", "string", array("null" => false));
		$this->change_column("works", "date_created", "datetime", array("null" => false));
		$this->change_column("works", "date_modified", "timestamp", array("null" => false));

		$this->change_column("works", "annotation", "text", array("null" => false));


		$this->change_column("dates", "start", "datetime", array("null" => false));
		$this->change_column("dates", "end", "datetime", array("null" => false));

		$this->change_column("dates", "created", "datetime", array("null" => false));
		$this->change_column("dates", "updated", "timestamp", array("null" => false));		
		
		
		
		

  }//up()

  public function down() {

  }//down()
}
?>
