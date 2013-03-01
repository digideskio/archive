<?php

namespace app\extensions\helper;

class Artwork extends \lithium\template\Helper {

	public function caption($archive, $work) {

    	$years = $archive->years();
    
    	$caption = array_filter(array(
    		$work->artist,
    		'<em>'.$work->title.'</em>',
    		$years,
    		$work->dimensions(), 
			$work->measurement_remarks
    	));
    	
    	return implode(', ', $caption) . '.';

	}

}

?>
