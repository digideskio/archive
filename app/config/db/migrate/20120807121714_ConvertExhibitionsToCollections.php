<?php

class ConvertExhibitionsToCollections extends Ruckusing_BaseMigration {

  public function up() {
  
  		date_default_timezone_set('UTC');
		
		$this->add_column("exhibitions", "collection_id", "integer", array(
			"unsigned" => true, 
			"null" => false
		));
		
		$result = $this->select_all("SELECT * FROM exhibitions");

		if($result) {
			
			foreach($result as $row) {
			
				$exhibition_id = $row['id'];
				$title = $row['title'];
				$slug = $row['slug'];
				$class = 'exhibition';
				
				$start = $row['earliest_date'];
				$end = $row['latest_date'];
				
				$this->execute("INSERT INTO dates (start, end, created, updated) VALUES ('$start', '$end', NOW(), NOW())");
				
				$date_id = mysql_insert_id();
				
				$this->execute("INSERT INTO collections (title, slug, class, date_id) VALUES ('$title', '$slug', '$class', '$date_id')");
				
				$collection_id = mysql_insert_id();
				
				$this->execute("UPDATE exhibitions SET collection_id = '$collection_id' WHERE id = '$exhibition_id'");
				
				$exhibition_works = $this->select_all("SELECT * FROM exhibitions_works WHERE exhibition_id = '$exhibition_id'");
				
				foreach($exhibition_works as $ew) {
					$work_id = $ew['work_id'];
					$this->execute("INSERT INTO collections_works set collection_id = '$collection_id', work_id = '$work_id'");
				}
				
			}
		}
		
		$this->remove_column("exhibitions", "earliest_date");
		$this->remove_column("exhibitions", "latest_date");
		$this->remove_column("exhibitions", "slug");
		$this->remove_column("exhibitions", "title");
		
		$this->drop_table("exhibitions_works");

  }//up()

  public function down() {
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
  
  		date_default_timezone_set('UTC');
  
		$this->add_column("exhibitions", "earliest_date", "datetime");
		$this->add_column("exhibitions", "latest_date", "datetime");
		$this->add_column("exhibitions", "title", "string");
		$this->add_column("exhibitions", "slug", "string");
		
		$class = 'exhibition';
		
		$result = $this->select_all("SELECT * FROM collections WHERE class = '$class'");

		if($result) {
			
			foreach($result as $row) {
			
				$collection_id = $row['id'];
				$slug = $row['slug'];
				$title = $row['title'];
				
				$date_id = $row['date_id'];
				
				$dates = $this->select_one("SELECT * FROM dates WHERE id = '$date_id'");
				
				$earliest_date = $dates['start'];
				$latest_date = $dates['end'];
				$latest_date = $dates['end'];
				
				$exhibition = $this->select_one("SELECT * FROM exhibitions WHERE collection_id = '$collection_id'");
				$exhibition_id = $exhibition['id'];
				
				$this->execute("UPDATE exhibitions SET earliest_date = '$earliest_date', latest_date = '$latest_date', slug = '$slug', title = '$title' WHERE id = '$exhibition_id'");
				
				$this->execute("DELETE FROM dates WHERE id = '$date_id'");
				
				$collection_works = $this->select_all("SELECT * FROM collections_works WHERE collection_id = '$collection_id'");
				
				foreach($collection_works as $cw) {
					$work_id = $cw['work_id'];
					$this->execute("INSERT INTO exhibitions_works set exhibition_id = '$exhibition_id', work_id = '$work_id'");
				}
				
				$this->execute("DELETE FROM collections_works WHERE collection_id = '$collection_id'");
				
			}
			
		}
  
		$this->execute("DELETE FROM collections WHERE class = '$class'");  
		$this->remove_column("exhibitions", "collection_id");
		

  }//down()
}
?>
