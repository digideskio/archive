<?php

namespace app\extensions\helper;

use	\lithium\template\Helper;

class Link extends \lithium\template\Helper {

	public function caption($link, $options = array()) {

		$url = $link->url;
		$elision = $link->elision();

		$html  = "<p>";
		$html .= "<i class='icon-check'></i>&nbsp;";
		$html .= "<a href='$url'>";
		$html .= "<strong>$elision</strong>";
		$html .= "</a>";
		$html .= "</p>";

		return $html;

	}

}


?>
