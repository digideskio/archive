<?php

namespace app\models;

class WorksLinks extends \lithium\data\Model {

	public $belongsTo = array('Works', 'Links');

	public $validates = array();
}

?>
