<?php

namespace app\models;

use li3_filesystem\extensions\storage\FileSystem;

class Packages extends \lithium\data\Model {

	public $belongsTo = array('Albums');

	//TODO validate the album_id
	public $validates = array(
		'album_id' => array(
			array('notEmpty', 'message' => 'No Album was specified for the Package.')
		),
		'filesystem' => array(
			array('notEmpty', 'message' => 'No Filesystem was specified for the Package.')
		),
		'slug' => array(
			array('notEmpty', 'message' => 'No Filename was suggested for the Package.')
		),
	);

	public function url($entity) {
		$filename = $entity->name;
		$filesystem = $entity->filesystem;

		$config = FileSystem::config($filesystem);

		$url = $config['url'];

		return $url . '/' . $filename;
	}

	public function path($entity) {
		$filename = $entity->name;
		$filesystem = $entity->filesystem;

		$config = FileSystem::config($filesystem);

		$path = $config['path'];

		return $path . DIRECTORY_SEPARATOR . $filename;
	}

	public function directory($entity) {
		$filesystem = $entity->filesystem;

		$config = FileSystem::config($filesystem);

		return $config['path'];
	}
}

Packages::applyFilter('save', function($self, $params, $chain) {
	// Custom pre-dispatch logic goes here
	date_default_timezone_set('UTC');

	// Check if this is a new record
	if(!$params['entity']->exists()) {

		// Set the date created
		$params['data']['date_created'] = date("Y-m-d H:i:s");

		//Set the filename
		$params['data']['name'] = $params['data']['slug'] . '_' . date("Y-m-d_His") . ".zip";

	}

	// Set the date modified
	$params['data']['date_modified'] = date("Y-m-d H:i:s");

	return $chain->next($self, $params, $chain);

});
?>
