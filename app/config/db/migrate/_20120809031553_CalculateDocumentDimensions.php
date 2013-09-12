<?php

class CalculateDocumentDimensions extends Ruckusing_Migration_Base {

  public function up() {
  
  	$target_dir = '';
  
  	$result = $this->select_all("SELECT *, documents.id as document_id FROM documents LEFT JOIN formats ON documents.format_id = formats.id WHERE formats.mime_type LIKE 'image%'");
  	
  	if($result) {
  	
  		foreach($result as $row) {
  		
  			$id = $row['document_id'];
  			$hash = $row['hash'];
  			$ext = $row['extension'];
  		
  			$file = $target_dir . DIRECTORY_SEPARATOR . $hash . '.' . $ext;
  			
  			$size = getimagesize($file);
  			
  			$width = $size[0];
  			$height = $size[1];
  			
  			echo "$id $hash.$ext $width x $height\n";
  			
  			$this->execute("UPDATE documents set width = '$width', height = '$height' WHERE id = '$id'");
  			
  		}
  		
  	}

  }//up()

  public function down() {

  }//down()
}
?>
