<?php

namespace app\extensions\helper;

use \lithium\template\helper\Html;

use \lithium\data\entity\Record;

class Artwork extends \lithium\template\Helper {

	public function caption(Record $artwork, $options = array()) {

    	$years = $artwork->archive->years();

		$title = $artwork->archive->name;

		if (isset($options['link']) && $options['link']) {
			$html = new Html();
			$title = $html->link(
				$artwork->archive->name,
				'/works/view/'.$artwork->archive->slug
			);	
		} else {
			$title = $this->escape($title);
		}
    
    	$caption = array_filter(array(
    		'<em>' . $title .'</em>',
    		$this->escape($years),
    		$this->escape($artwork->dimensions()), 
			$this->escape($artwork->measurement_remarks)
    	));
    	
    	return implode(', ', $caption) . '.';

	}

}

?>
