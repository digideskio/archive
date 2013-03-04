<?php

namespace app\models;

class AlbumsWorks extends \lithium\data\Model {

	public $belongsTo = array('Albums', 'Works');

	public $validates = array();
}

?>
