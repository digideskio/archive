<?php

namespace app\models;

class ArchivesHistories extends \app\models\Archives {

	public $belongsTo = array("Archives", "Users");

	public $hasOne = array(
		'WorksHistories' => array (
			'to' => 'app\models\WorksHistories',
			'key' => array(
				'start_date' => 'start_date',
				'archive_id' => 'work_id'
		)),
		'ArchitecturesHistories' => array (
			'to' => 'app\models\ArchitecturesHistories',
			'key' => array(
				'start_date' => 'start_date',
				'archive_id' => 'architecture_id'
		)),
		'AlbumsHistories' => array (
			'to' => 'app\models\AlbumsHistories',
			'key' => array(
				'start_date' => 'start_date',
				'archive_id' => 'album_id'
		)),
		'ExhibitionsHistories' => array (
			'to' => 'app\models\ExhibitionsHistories',
			'key' => array(
				'start_date' => 'start_date',
				'archive_id' => 'exhibition_id'
		)),
		'PublicationsHistories' => array (
			'to' => 'app\models\PublicationsHistories',
			'key' => array(
				'start_date' => 'start_date',
				'archive_id' => 'publication_id'
		)),
	);

	public $validates = array();

	/**
	 * Sample schema and query. For now we rely on the schema being lazy-loaded
	 */

	protected $_schema = array(
		'id'                   => array('type' => 'integer', 'null' => 'false'),
		'name'                 => array('type' => 'string', 'null' => false),
		'native_name'          => array('type' => 'string', 'null' => false),
		'language_code'        => array('type' => 'string', 'null' => false),
		'controller'           => array('type' => 'string', 'null' => false),
		'classification'       => array('type' => 'string', 'null' => false),
		'type'                 => array('type' => 'string', 'null' => false),
		'catalog_level'        => array('type' => 'string', 'null' => false),
		'description'          => array('type' => 'string', 'null' => false),
		'slug'                 => array('type' => 'string', 'null' => false),
		'earliest_date'        => array('type' => 'date', 'null' => false, 'default' => ''),
		'latest_date'          => array('type' => 'date', 'null' => false, 'default' => ''),
		'earliest_date_format' => array('type' => 'string', 'null' => false),
		'latest_date_format'   => array('type' => 'string', 'null' => false),
		'date_created'         => array('type' => 'date', 'null' => false),
		'date_modified'        => array('type' => 'date', 'null' => false),
		'user_id'              => array('type' => 'integer', 'null' => 'false'),
		'parent_id'            => array('type' => 'integer', 'null' => 'false'),
		'start_date'           => array('type' => 'integer', 'null' => 'false'),
		'end_date'             => array('type' => 'integer', 'null' => 'false'),
		'published'            => array('type' => 'boolean', 'null' => 'false'),
	);

	protected $_query = array(
		'fields' => array(
			'id',
			'name',
			'native_name',
			'language_code',
			'controller',
			'classification',
			'type',
			'catalog_level',
			'description',
			'slug',
			'earliest_date',
			'latest_date',
			'earliest_date_format',
			'latest_date_format',
			'date_created',
			'date_modified',
			'user_id',
			'parent_id',
			'start_date',
			'end_date',
		    'published'
		)
	);
}

?>
