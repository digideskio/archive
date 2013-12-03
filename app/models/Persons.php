<?php

namespace app\models;

class Persons extends \lithium\data\Model {

	public $validates = array(
		'id' => array(
			array('notEmpty', 'message' => 'This field may not be empty.')
		),
	);
}

?>
