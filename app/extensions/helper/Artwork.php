<?php

namespace app\extensions\helper;

use \lithium\template\helper\Html;

class Artwork extends \lithium\template\Helper {

	public function caption($archive, $work, $options = array()) {

    	$years = $archive->years();

		$title = $work->title;

		if (isset($options['link']) && $options['link']) {
			$title = Html::link($work->title, '/works/view/'.$archive->slug);	
		}
    
    	$caption = array_filter(array(
    		$work->artist,
    		'<em>'.$title.'</em>',
    		$years,
    		$work->dimensions(), 
			$work->measurement_remarks
    	));
    	
    	return implode(', ', $caption) . '.';

	}

}

?>
