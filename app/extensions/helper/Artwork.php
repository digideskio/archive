<?php

namespace app\extensions\helper;

use \lithium\template\helper\Html;

class Artwork extends \lithium\template\Helper {

	public function caption($archive, $work, $options = array()) {

    	$years = $archive->years();

		$title = $work->title;

		if (isset($options['link']) && $options['link']) {
			$html = new Html();
			$title = $html->link($work->title, '/works/view/'.$archive->slug);	
		} else {
			$title = $this->escape($title);
		}
    
    	$caption = array_filter(array(
    		$this->escape($work->artist),
    		'<em>' . $title .'</em>',
    		$this->escape($years),
    		$this->escape($work->dimensions()), 
			$this->escape($work->measurement_remarks)
    	));
    	
    	return implode(', ', $caption) . '.';

	}

	public function artists($archive, $work, $options = array()) {

		$artist = '';
		$artist_native_name = '';

		if (isset($options['link']) && $options['link'] == true) {
			$html = new Html();
			$artist_search_path = "/works/search?condition=artist&query=";

			if ($work->artist != '') {
				$artist_query = urlencode($work->artist);
				$artist = $html->link($work->artist, $artist_search_path . $artist_query);
			}

			if ($work->artist_native_name != '') {
				$artist_query = urlencode($work->artist_native_name);
				$artist_native_name = $html->link($work->artist_native_name, $artist_search_path . $artist_query);
			}
		} else {
			$artist = $this->escape($work->artist);
			$artist_native_name = $this->escape($work->artist_native_name);
		}
		
		if ($artist_native_name != '') {
			$artist_native_name = "(" . $artist_native_name . ")";
		}

		$artists = array_filter(array(
			$artist,
			$artist_native_name
		));

		return implode(' ', $artists);

	}

}

?>
