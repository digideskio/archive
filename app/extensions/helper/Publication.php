<?php

namespace app\extensions\helper;

class Publication extends \lithium\template\Helper {

	public function citation($archive, $publication) {

    	$years = $this->escape($archive->dates());
		$byline = $this->escape($publication->byline());
    	$author_years = $years ? $byline . ' (' . $years . ')' : $byline;

		$title = $publication->title;

		$publisher = $this->escape($publication->publisher);
		$pages = $this->escape($publication->pages);

		$publication = $pages ? $publisher . ', ' . $pages : $publisher;
		
		$citation = array_filter(array(
			$author_years,
			"<em>" . $title . "</em>",
			$publication
		));
    	
    	return implode('. ', $citation) . '.';
		

	}

}

?>
