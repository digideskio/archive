<?php

namespace app\extensions\helper;

class Architecture extends \lithium\template\Helper {

	public function caption($archive, $architecture) {

    	$years = $archive->years();
    	$status = $architecture->status ? '(' . $architecture->status . ')' : '';
    
    	$caption = array_filter(array(
			$this->escape($architecture->architect),
    		'<em>' . $this->escape($archive->name) . '</em>',
    		$this->escape($years),
    		$this->escape($architecture->location),
    		$this->escape($architecture->city),
    		$this->escape($architecture->country),
    		$this->escape($status)
    	));
    	
    	return implode(', ', $caption) . '.';

	}

}

?>
