<?php

namespace app\extensions\helper;

use	\lithium\template\Helper;

class Link extends \lithium\template\Helper {

	private static $_video_hosts = array(
		'www.youtube.com',
		'youtube.com',
		'youtu.be',
		'vimeo.com',
	);

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

	public function isVideo($link) {
		$url = $link->url;
		$video_hosts = self::$_video_hosts;

		$isVideo = false;

		if (!empty($url)) {
			$host = parse_url($url, PHP_URL_HOST);
			if ($host) {
				$isVideo = in_array($host, $video_hosts);
			}
		}

		return $isVideo;
	}

}


?>
