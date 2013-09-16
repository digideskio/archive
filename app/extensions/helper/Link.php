<?php

namespace app\extensions\helper;

use	\lithium\template\Helper;
use \lithium\template\Helper\Html;

class Link extends \lithium\template\Helper {

	private static $_video_hosts = array(
		'www.youtube.com',
		'youtube.com',
		'youtu.be',
		'vimeo.com',
	);

	public function caption($link, $options = array()) {

		if (!empty($link->url)) {
			$url = $link->url;

			$icon = $this->isVideo($link) ? 'icon-film' : 'icon-bookmark';

			$html_helper = new Html();

			$html  = "<p>";
			$html .= "<i class='$icon'></i>&nbsp;";
			$html .= "<strong>";
			$html .= $html_helper->link($url, $url);
			$html .= "</strong>";
			$html .= "</p>";

			return $html;
		}

	}

	public function isVideo($link) {

		$isVideo = false;

		if (!empty($link->url)) {
			$url = $link->url;
			$host = parse_url($url, PHP_URL_HOST);
			if ($host) {
				$isVideo = in_array($host, self::$_video_hosts);
			}
		}

		return $isVideo;
	}

}


?>
