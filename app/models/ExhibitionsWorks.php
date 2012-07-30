<?php

namespace app\models;

class ExhibitionsWorks extends \lithium\data\Model {

	public $belongsTo = array('Exhibitions', 'Works');

	public $validates = array();
}

?>
