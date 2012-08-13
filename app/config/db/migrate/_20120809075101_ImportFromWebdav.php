<?php

class ImportFromWebdav extends Ruckusing_BaseMigration {

  public function up() {
  
  	$mimeTypes = array(
  		'image/jpeg',
  		'image/tiff',
  		'photoshop'
  	);
  	
  	$grep_mimes = implode('|', $mimeTypes); // Format for grep
  
  	$webdav = '/srv/www/aiww.net/webdav/artworks-latest-2012-07-30';
  	
  	$target = '/srv/www/fakeweiwei.com/public/app/webroot/files';
  	
  	foreach (new DirectoryIterator($webdav) as $collection) {
  	
  		if($collection->isDot()) continue; // Ignore . and ..
  		
  		$collection_path = $webdav . DIRECTORY_SEPARATOR . $collection;
  	
  		if(is_dir($collection_path)) { // Only look in directories
	  		
	  		$collection_dir = $webdav . DIRECTORY_SEPARATOR . $collection;
  		
			chdir($collection_dir);
			exec("find *csv -type f", $csvs);
			
			if($csvs) {
			
				$annotations = array();
			
				foreach($csvs as $csv) {
					$row = 1;
					if (($handle = fopen("$csv", "r")) !== FALSE) {
						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
							$slug = $data[0];
							$file_id = $data[1];
							$artist = $data[2];
							$title = $data[3];
							$date = $data[4];
							$materials = $data[5];
							$measurements = $data[6];
							$annotation = $data[7];
							
							$row = compact('slug', 'artist', 'title', 'date', 'materials', 'measurements', 'annotation');
							
							$annotations["$file_id"] = $row;
						}
						fclose($handle);
					}
				}
				
				//Create a date object
				$this->execute("INSERT INTO dates (created, updated) VALUES (NOW(), NOW())");
				
				$date_id = mysql_insert_id();
				
				// Create a collection
				$this->execute("INSERT INTO collections (title,slug,date_id,class) VALUES('$collection', '$collection', '$date_id', 'collection')");
				
				$collection_id = mysql_insert_id();
				
				exec("find -type f -print0 | xargs -0 file -i | grep -i -E '($grep_mimes)'", $finds);
			
				//echo getcwd() . ":\n";
				//echo "find -type f -print0 | xargs -0 file -i | grep -i -E '($grep_mimes)'" . "\n\n";

				foreach($finds as $f) {
					$fileName = substr($f, 2, strripos($f, ':')-2);
					$fileType = substr($f, strripos($f, ':')+2);
					$mimeType = trim(substr($fileType, 0, strripos($fileType, ';')));

					// Keep track of the full file path
					$fileFullPath = $collection_dir . DIRECTORY_SEPARATOR . $fileName;

					// Break down the mimetype
					$parts = explode('/', $mimeType);
					$type = $parts[0]; //e.g., image
					$format = $parts[1]; //e.g., jpeg
			
					// Look up the date from the EXIF
					exec("/usr/bin/exiftool -j -AllDates '$fileFullPath'", $date_info);
					$exif_info = json_decode(implode($date_info));
					if($exif_info) {
						$dateOriginal = isset($exif_info[0]->DateTimeOriginal) ? $exif_info[0]->DateTimeOriginal : 0;
						$createDate = isset($exif_info[0]->CreateDate) ? $exif_info[0]->CreateDate : 0;
						$exif_date = $dateOriginal ? $dateOriginal : $createDate;
					}
					if(isset($exif_date) && $exif_date) {
						$file_time = strtotime($exif_date);
					}

					if (!isset($file_time)) {
						$file_time = time();
					}
	
					$file_date = date("Y-m-d H:i:s", $file_time);
			
					unset($date_info);
				
					// Look up keywords in the EXIF				
					exec("/usr/bin/exiftool -j -Keywords '$fileFullPath'", $keyword_info);
					$exif_info = json_decode(implode($keyword_info));
					$keywords = isset($exif_info[0]->Keywords) ? $exif_info[0]->Keywords : '';
				
					unset($keyword_info);
					
					// Look up the document ID in the EXIF			
					exec("/usr/bin/exiftool -j -DocumentID '$fileFullPath'", $document_info);
					$exif_info = json_decode(implode($document_info));
					$repository = isset($exif_info[0]->DocumentID) ? $exif_info[0]->DocumentID : '';
				
					unset($document_info);
				
					$keywords = is_array($keywords) ? implode(', ', $keywords) : $keywords;
			
					// Look up the image size in the EXIF	
					exec("/usr/bin/exiftool -j -ImageHeight $fileFullPath", $file_height);
					exec("/usr/bin/exiftool -j -ImageWidth $fileFullPath", $file_width);
			
					$height_info = json_decode(implode($file_height));
					$width_info = json_decode(implode($file_width));
			
					$height = isset($height_info[0]->ImageHeight) ? $height_info[0]->ImageHeight : 0;
					$width = isset($width_info[0]->ImageWidth) ? $width_info[0]->ImageWidth : 0;
					
					unset($file_height);
					unset($file_width);
			
					$ext = strrpos($fileName, '.');
					$file_title = substr($fileName, 0, $ext);
			
					// Get the file's hash
					$hash = md5_file($fileName);
					
					// Look up the format
					$formats = $this->select_one("SELECT * FROM formats WHERE mime_type = '$mimeType'");
					$format_id = $formats['id'];
					$extension = $formats['extension'];
					
					// Move the file into the target directory
					$hashName = "$hash.$extension";
					$finalPath = $target . DIRECTORY_SEPARATOR . $hashName;
					copy($fileFullPath, $finalPath);
					
					$thumb = $target . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR . "$hash.jpeg";
					$small = $target . DIRECTORY_SEPARATOR . 'small' . DIRECTORY_SEPARATOR . "$hash.jpeg";
					
					exec("/usr/bin/convert -define jpeg:size=260x260 $finalPath -thumbnail 260x260^ -gravity center -extent 260x260 $thumb");
					exec("/usr/bin/convert -resize 560x560 $finalPath $small");

					$this->execute("INSERT INTO documents (title, hash, repository, format_id, file_date, slug, date_created, date_modified, width, height) VALUES ('$file_title', '$hash', '$repository', '$format_id', '$file_date', '$file_title', NOW(), NOW(), '$width', '$height')");
					
					$document_id = mysql_insert_id();
					
					$artwork = $annotations["$file_title"];
					
					$slug = $artwork['slug'];
					$artist = $artwork['artist'];
					$title = mysql_real_escape_string($artwork['title']);
					$date = $artwork['date'];
					$materials = $artwork['materials'];
					$measurements = $artwork['measurements'];
					$annotation = mysql_real_escape_string($artwork['annotation']);
					
					if($date) {
						$date .= "-01-01 00:00:00";
					}
					
					if($slug) {
						$works = $this->select_one("SELECT id FROM works WHERE slug = '$slug'");
						$work_id = $works['id'];
						$this->execute("UPDATE works SET annotation = '$annotation' WHERE slug = '$slug'");
					} else {
						$this->execute("INSERT INTO works (artist, title, materials, earliest_date, latest_date, slug, date_created, date_modified, annotation) VALUES ('$artist', '$title', '$materials', '$date', '0', '$file_title', NOW(), NOW(), '$annotation')");
						
						$work_id = mysql_insert_id();
					}
					
					$this->execute("INSERT INTO works_documents (document_id, work_id) VALUES ('$document_id', '$work_id')");
					
					$this->execute("INSERT INTO collections_works (collection_id, work_id) VALUES ('$collection_id', '$work_id')");
					
					echo "$fileName: $hashName" . "\n";
				}
			
				unset($finds);
			
			}
			
			unset($csvs);
  		}
  	
  	}

  }//up()

  public function down() {

  }//down()
}
?>

