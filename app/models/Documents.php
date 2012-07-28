<?php

namespace app\models;

use lithium\util\Inflector;

class Documents extends \lithium\data\Model {

	public $belongsTo = array('Formats');

	public $validates = array();
	
}

Documents::applyFilter('create', function($self, $params, $chain) {

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
		
		//Check if the slug ends with an iterated number such as Slug-1
		if(preg_match_all("/.*?-(\d+)$/", $slug, $matches)) {
			//Get the base of the iterated slug
			$slug = substr($slug, 0, strripos($slug, '-'));
		}
		
		//Count the slugs that start with $slug
		$count = Documents::find('count', array(
		    'fields' => array('id'),
		    'conditions' => array('slug' => array('like' => "$slug%"))
		));
		
		$slug = $slug . ($count ? "-" . (++$count) : ''); //add slug-X only if $count > 0
	
		// Get the md5sum of the file
		$hash = md5_file($file_path);
		
		// Look up the format
		$format = Formats::first(array(
			'fields' => array('id'),
			'conditions' => array('mime_type' => $mime_type)
		));
		
		
		$format_id = $format->id;
				
		$params['data'] = compact('title', 'hash', 'file_date', 'format_id', 'slug');

		//Give the file a unique filename based on its md5sum
		$hash_name = $hash . '.' . $format->extension;
		$final_path = $target_dir . DIRECTORY_SEPARATOR . $hash_name;
		rename($file_path, $final_path);	
	
	}
	
	return $chain->next($self, $params, $chain);

});

?>
