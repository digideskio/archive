<?php

namespace app\models;

class ExhibitionsDocuments extends \lithium\data\Model {

	public $belongsTo = array(
		'Exhibitions' => array(
			"to" => "app\models\Exhibitions",
			"key" => "exhibition_id",
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
