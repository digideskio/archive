<?php

namespace app\models;

class CollectionsWorks extends \lithium\data\Model {

	public $belongsTo = array('Collections', 'Works');

	public $validates = array();
}

?>
