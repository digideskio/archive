<?php

namespace app\models;

class Roles extends \lithium\data\Model {

	public $hasMany = array('Users');

	public $validates = array();

//    protected $_schema = array(
//		'id'                   => array('type' => 'id'),
//		'name'                 => array('type' => 'string', 'null' => false),
//		'description'          => array('type' => 'string', 'null' => false),
//    );

}

?>
