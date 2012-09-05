<?php

/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2012, Fabricatorz (http://fabricatorz.com)
 * @license       http://sharism.org/agreement The Sharing Agreement
 */

/**
 * The filesystems file configures the li3_filesystem plugin and adds our local filesystem and stream based locations
 *
 * Some useful paths are:
 *	Libraries::get(true, 'path') . '/webroot'
 *	Libraries::get(true, 'resources') . '/tmp'
 *
 */

use lithium\core\Libraries;
use li3_filesystem\extensions\storage\FileSystem;

Filesystem::config(array(
	'documents' => array(
		'adapter' => 'File',
		'path' => Libraries::get(true, 'resources') . '/archive/files'
	),
	'packages' => array(
		'adapter' => 'File',
		'path' => Libraries::get(true, 'resources') . '/archive/packages',
		'url' => '/files/package'
	),
));

?>
