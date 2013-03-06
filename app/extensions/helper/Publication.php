<?php

namespace app\extensions\helper;

class Publication extends \lithium\template\Helper {

	public function citation($archive, $publication) {

    	$years = $archive->dates();
    	
		$byline = $publication->byline();
    	$author_years = $years ? $byline . ' (' . $years . ')' : $byline;
    	$title = '<em>' . $publication->title . '</em>';
    	$title = $publication->url ? "<a href='$publication->url'>$title</a>" : $title;
		$publication = $publication->pages ? $publication->publisher . ', ' . $publication->pages : $publication->publisher;
		
		$citation = array_filter(array(
			$author_years,
			$title,
			$publication
		));
    	
    	return implode('. ', $citation) . '.';
		

	}

}

?>
