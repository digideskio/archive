<?php

namespace app\models;

class WorksHistories extends \app\models\Works {

	public $belongsTo = array("Works", "Users");

}

?>
