<?php

namespace app\models;

class ArchivesDocuments extends \lithium\data\Model {

	public $belongsTo = array(
		'Archives' => array(
			"to" => "app\models\Archives",
			"key" => "archive_id",
		),
		'Documents' => array(
			"to" => "app\models\Documents",
			"key" => "document_id"
		),
		'Formats' => array(
			"from" => "app\models\Documents",
			"to" => "app\models\Formats",
			"key" => array (
				"format_id" => "id"
			),
		),
	);

	public $validates = array();
}

?>
