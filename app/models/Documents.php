<?php

namespace app\models;

use lithium\util\Inflector;

use Imagine;

class Documents extends \lithium\data\Model {

	public $belongsTo = array(
		"Formats" => array(
			"to" => "app\models\Formats",
			"key" => "format_id",
		),
	);

	public $hasMany = array(
		"WorksDocuments", 
		"ArchitecturesDocuments", 
		"ExhibitionsDocuments", 
		"PublicationsDocuments"
	);

	public $validates = array();
	
	public function year($entity) {
	
	$year = (
		$entity->file_date != 
		'0000-00-00 00:00:00'
	) ? date_format(date_create($entity->file_date), 'Y') : '';
	
	return $year;
		
	}

	public function view($entity, array $options = null) {
		
		$action = isset($options['action']) ? $options['action'] : 'thumb';

		if ($action == 'download') {

			$format = Formats::find('first', array(
				'conditions' => array('id' => $entity->format_id),
			));

			$extension = $format->extension;

		} else {
			$extension = 'jpeg';
		}

		return $action . '/' . $entity->slug . '.'  . $extension;

	}

	public function file($entity, array $options = null) {
		
		$size = isset($options['size']) ? $options['size'] : '';

		if ($size && $size != 'small' && $size != 'thumb') {
			$size = 'thumb';
		}

		if (!$size) {
			$format = Formats::find('first', array(
				'conditions' => array('id' => $entity->format_id),
			));
			$extension = $format->extension;
		} else {
			$extension = 'jpeg';
			$size .= DIRECTORY_SEPARATOR;
		}

		return $size . $entity->hash . '.' . $extension;
	}

	public function resolution($entity) {

		if( $entity->width && $entity->height ) {

			return "$entity->width × $entity->height px";
	
		} else {

			return "No resolution is set on this document";
	
		}

	}

	public function size($entity, $options = null) {

		if($entity->width && $entity->height) {

			$dpi = isset($options['dpi']) ? (int) $options['dpi'] : 300;
			$width = number_format($entity->width * 2.54 / $dpi, 2);
			$height = number_format($entity->height * 2.54 / $dpi, 2);

			return "$width × $height cm @ $dpi dpi";

		} else {

			return "No print size is set on this document";

		}
	}
	
}

Documents::applyFilter('delete', function($self, $params, $chain) {

	$document_id = $params['entity']->id;
		
	//Delete any relationships
	WorksDocuments::find('all', array(
		'conditions' => array('document_id' => $document_id)
	))->delete();

	ArchitecturesDocuments::find('all', array(
		'conditions' => array('document_id' => $document_id)
	))->delete();
	
	ExhibitionsDocuments::find('all', array(
		'conditions' => array('document_id' => $document_id)
	))->delete();

	PublicationsDocuments::find('all', array(
		'conditions' => array('document_id' => $document_id)
	))->delete();

	return $chain->next($self, $params, $chain);

});

Documents::applyFilter('save', function($self, $params, $chain) {

	// Check if this is a new record
	if(!$params['entity']->exists()) {
	
		// Set the date created
		$params['data']['date_created'] = date("Y-m-d H:i:s");
		
		if ($params['data'] && isset($params['data']['file_path']) && isset($params['data']['file_name'])) {

			$file_path = $params['data']['file_path'];
			$file_name = $params['data']['file_name'];
			$target_dir = substr($file_path, 0, strrpos($file_path, '/'));
		
			// Get the mimetype of the file
			exec("/usr/bin/file -bi $file_path", $mime_info);
			$mimes = explode(';',$mime_info[0]);
			$mime_type = array_shift($mimes);

			$args = explode('/', $mime_type);
			$type = $args[0]; //e.g., image
			$format = $args[1]; //e.g., jpeg

			// Get the creation date based on the type of file
			if($type == 'image') {
				exec("/usr/bin/exiftool -j -AllDates $file_path", $file_info);
				$exif_info = json_decode(implode($file_info));
				if($exif_info) {
					$dateOriginal = isset($exif_info[0]->DateTimeOriginal) ? $exif_info[0]->DateTimeOriginal : 0;
					$createDate = isset($exif_info[0]->CreateDate) ? $exif_info[0]->CreateDate : 0;
					$exif_date = $dateOriginal ? $dateOriginal : $createDate;
				}
				if(isset($exif_date) && $exif_date) {
					$file_time = strtotime($exif_date);
				} 
				
				exec("/usr/bin/exiftool -j -ImageHeight $file_path", $file_height);
				exec("/usr/bin/exiftool -j -ImageWidth $file_path", $file_width);
				
				$height_info = json_decode(implode($file_height));
				$width_info = json_decode(implode($file_width));
				
				$height = isset($height_info[0]->ImageHeight) ? $height_info[0]->ImageHeight : 0;
				$width = isset($width_info[0]->ImageWidth) ? $width_info[0]->ImageWidth : 0;
			} else if ($format == 'pdf') {
				exec("/usr/bin/pdfinfo $file_path | grep CreationDate | cut -d ':' -f 2-", $file_info);

				if(is_array($file_info) && (count($file_info) > 0)) {
					$file_time = strtotime($file_info[0]);
				} 
			}
		
			unset($file_info);

			if (!isset($file_time)) {
				$file_time = time();
			}
		
			$file_date = date("Y-m-d H:i:s", $file_time);
		
			// Create a title based on the filename
		
			$ext = strrpos($file_name, '.');
			$title = substr($file_name, 0, $ext);
			
			//create a slug based on the title
			$slug = Inflector::slug($title);
		
			$conditions = array('slug' => $slug);
			$conflicts = Documents::count(compact('conditions'));

			if($conflicts){
				$i = 0;
				$newSlug = '';
				while($conflicts){
					$i++;
					$newSlug = "{$slug}-{$i}";
					$conditions = array('slug' => $newSlug);
					$conflicts = Documents::count(compact('conditions'));
				}
				// Out of conflict.
				$slug = $newSlug;
			}
		
			// Get the md5sum of the file
			$hash = md5_file($file_path);
			
			// Look up the format
			$format = Formats::first(array(
				'conditions' => array('mime_type' => $mime_type)
			));
			
			
			$format_id = $format->id;
					
			$params['data'] = compact('title', 'hash', 'file_date', 'format_id', 'slug', 'width', 'height');

			//Give the file a unique filename based on its md5sum
			$hash_name = $hash . '.' . $format->extension;
			$final_path = $target_dir . DIRECTORY_SEPARATOR . $hash_name;
			rename($file_path, $final_path);
			
			$image_path = $final_path;
			
			//create a preview for pdf
			if ($format->extension == 'pdf') {
				$image_path = $target_dir . DIRECTORY_SEPARATOR . $hash . '.jpeg';
				exec("/usr/bin/convert ".$final_path."[0] $image_path", $preview_info);
			}
			
			//Make paths for the image previews
			$small = $target_dir . DIRECTORY_SEPARATOR . 'small';
			$thumb = $target_dir . DIRECTORY_SEPARATOR . 'thumb';
			
			if (!file_exists($small))
				@mkdir($small);
				
			if (!file_exists($thumb))
				@mkdir($thumb);
			
			try {
				$imagine = new Imagine\Imagick\Imagine();
			
				$twosixty	= new Imagine\Image\Box(260, 260);
				$fivesixty	= new Imagine\Image\Box(560, 560);
				$inset		= Imagine\Image\ImageInterface::THUMBNAIL_INSET;
				$outbound	= Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
			
				$imagine->open($image_path)->thumbnail($twosixty, $outbound)->save(
					$thumb . DIRECTORY_SEPARATOR . $hash . '.jpeg'
				);
			
				$imagine->open($image_path)->thumbnail($fivesixty, $inset)->save(
					$small . DIRECTORY_SEPARATOR . $hash . '.jpeg'
				);
		
			} catch (Imagine\Exception\Exception $e) {
				error_log($e);
			}
			
		}
	}
	
	// Set the date modified
	$params['data']['date_modified'] = date("Y-m-d H:i:s");
	
	return $chain->next($self, $params, $chain);
});


?>
