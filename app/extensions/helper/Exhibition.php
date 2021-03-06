<?php

namespace app\extensions\helper;

use \lithium\template\helper\Html;

class Exhibition extends \lithium\template\Helper {

	public function location($archive, $exhibition, $options = array()) {

		$venue = $exhibition->venue ? '<strong>' . $this->escape($exhibition->venue) . '</strong>' : '';
		$location = array_filter(array(
			$venue,
			$this->escape($exhibition->city),
			$this->escape($exhibition->country)
		));
		return implode(', ', $location);

	}

}

?>
