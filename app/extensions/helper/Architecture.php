<?php

namespace app\extensions\helper;

class Architecture extends \lithium\template\Helper {

	public function caption($archive, $architecture) {

    	$years = $archive->years();
    	$status = $architecture->status ? '(' . $architecture->status . ')' : '';
    
    	$caption = array_filter(array(
			$architecture->architect,
    		'<em>'.$architecture->title.'</em>',
    		$years,
    		$architecture->location,
    		$architecture->city,
    		$architecture->country,
    		$status
    	));
    	
    	return implode(', ', $caption) . '.';

	}

}

?>
