<?php

namespace app\models;

class ArchitecturesDocuments extends \lithium\data\Model {

	public $belongsTo = array(
		'Architectures' => array(
			"to" => "app\models\Architectures",
			"key" => "architecture_id",
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
	
	public function preview($entity) {
		
		$document = Documents::find('first', array(
			'conditions' => array('id' => $entity->document_id),
		));
		
		if($document) {
		
			return $document->thumbnail();
		
		}
		
	}
}

?>
