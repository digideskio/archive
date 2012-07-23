<?php

namespace app\models;

class Roles extends \lithium\data\Model {

	public $hasMany = array('Users');

	public $validates = array();
}

?>
