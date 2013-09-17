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

}

?>
