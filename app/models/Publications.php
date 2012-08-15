<?php

namespace app\models;

use lithium\util\Inflector;
use lithium\util\Validator;

class Publications extends \app\models\Archives {
    
    public function citation($entity) {
    	$years = Publications::years($entity);
    	
    	$author = $entity->author;
    	$author_years = $years ? $author . ' (' . $years . ')' : $author;
    	$title = '<em>' . $entity->title . '</em>';
    	$title = $entity->url ? "<a href='$entity->url'>$title</a>" : $title;
		$publication = $entity->pages ? $entity->publisher . ', ' . $entity->pages : $entity->publisher;
		
		$citation = array_filter(array(
			$author_years,
			$title,
			$publication
		));
    	
    	return implode('. ', $citation) . '.';
    }
    
}

?>
