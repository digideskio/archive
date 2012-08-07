<?php

class AddClassAndDatesToCollections extends Ruckusing_BaseMigration {

  public function up() {
  
  		date_default_timezone_set('UTC');
		
		$this->add_column("collections", "class", "string");
		
		$this->add_column("collections", "date_id", "integer", array(
			"unsigned" => true, 
			"null" => false
		));
		
		$result = $this->select_all("SELECT * FROM collections");

		if($result) {
			
			foreach($result as $row) {
			
				$collection_id = $row['id'];
				
				$date_created = $row['date_created'];
				$date_modified = $row['date_modified'];
				
				$this->execute("INSERT INTO dates (created, updated) VALUES ('$date_created', '$date_modified')");
				
				$date_id = mysql_insert_id();
				
				$this->execute("UPDATE collections SET date_id = '$date_id' WHERE id = '$collection_id'");
			
			}
		}
		
		$this->execute("UPDATE collections SET class = 'collection'");
		
		$this->remove_column("collections", "date_created");
		$this->remove_column("collections", "date_modified");
		
		

  }//up()

  public function down() {
  
  		date_default_timezone_set('UTC');
  
		$this->add_column("collections", "date_created", "datetime");
		$this->add_column("collections", "date_modified", "timestamp");
		
		$result = $this->select_all("SELECT * FROM collections");

		if($result) {
			
			foreach($result as $row) {
			
				$collection_id = $row['id'];
				
				$date_id = $row['date_id'];
				
				$dates = $this->select_one("SELECT * FROM dates WHERE id = '$date_id'");
				
				$date_created = $dates['created'];
				$date_modified = $dates['updated'];
				
				$this->execute("UPDATE collections SET date_created = '$date_created', date_modified = '$date_modified' WHERE id = '$collection_id'");
				
				$this->execute("DELETE FROM dates WHERE id = '$date_id'");
				
			}
			
		}
  
  		$this->remove_column("collections", "class");
  		$this->remove_column("collections", "date_id");
  
		

  }//down()
}
?>
