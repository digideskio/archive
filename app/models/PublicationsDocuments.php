<?php

namespace app\models;

class PublicationsDocuments extends \lithium\data\Model {

	public $belongsTo = array(
		'Publications' => array(
			"to" => "app\models\Publications",
			"key" => "publication_id",
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
