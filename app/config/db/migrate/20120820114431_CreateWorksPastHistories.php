<?php

class CreateWorksPastHistories extends Ruckusing_BaseMigration {

  public function up() {

	$result = $this->select_all("SELECT * FROM works");

	if($result) {

		foreach($result as $row) {

			array_map('mysql_real_escape_string', $row);

			$work_id = $row['id'];

			$artist = mysql_real_escape_string($row['artist']);
			$title = mysql_real_escape_string($row['title']);
			$classification = mysql_real_escape_string($row['classification']);
			$materials = mysql_real_escape_string($row['materials']);
			$quantity = mysql_real_escape_string($row['quantity']); 
			$location = mysql_real_escape_string($row['location']); 
			$lender = mysql_real_escape_string($row['lender']); 
			$remarks = mysql_real_escape_string($row['remarks']); 
			$earliest_date = $row['earliest_date']; 
			$latest_date = $row['latest_date']; 
			$creation_number = mysql_real_escape_string($row['creation_number']); 
			$height = $row['height']; 
			$width = $row['width']; 
			$depth = $row['depth']; 
			$diameter = $row['diameter']; 
			$weight = $row['weight'];
			$running_time = mysql_real_escape_string($row['running_time']); 
			$measurement_remarks = mysql_real_escape_string($row['measurement_remarks']); 
			$slug = $row['slug']; 
			$date_created = $row['date_created'];
			$date_modified = $row['date_modified'];

			$this->execute("INSERT INTO works_histories (work_id, artist, title, classification, materials, quantity, location, lender, remarks, earliest_date, latest_date, creation_number, height, width, depth, diameter, weight, running_time, measurement_remarks, slug, date_created, date_modified, start_date, end_date) VALUES ('$work_id', '$artist', '$title', '$classification', '$materials', '$quantity', '$location', '$lender', '$remarks', '$earliest_date', '$latest_date', '$creation_number', '$height', '$width', '$depth', '$diameter', '$weight', '$running_time', '$measurement_remarks', '$slug', '$date_created', '$date_modified', '$date_created', NULL)");

		}

	}

  }//up()

  public function down() {

	$this->execute("DELETE FROM works_histories");

  }//down()
}
?>
