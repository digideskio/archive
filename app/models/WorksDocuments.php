<?php

namespace app\models;

class WorksDocuments extends \lithium\data\Model {

	public $belongsTo = array(
		'Works' => array(
			"to" => "app\models\Works",
			"key" => "work_id",
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
		
		return $document->thumbnail();
		
	}
}

?>
