<?php

namespace app\extensions\helper;

use \lithium\template\helper\Html;

use \lithium\data\entity\Record;

class Artwork extends \lithium\template\Helper {

	public function caption(Record $artwork, $options = array()) {

		if (!empty($artwork->archive)) {
			$years = $artwork->archive->years();
			$title = $artwork->archive->name;
		} else {
			$years = '';
			$title = '';
		}

		$artists = array();

		if (!empty($artwork->components)) {
			$components = $artwork->components;

			foreach ($components as $c) {
				if ($c->type == 'persons_works') {
					array_push($artists, $c->person->archive->name);
				}
			}
		}

		$display_artists = $this->escape(implode(', ', $artists));

		if (isset($options['link']) && $options['link']) {
			$html = new Html();
			$title = $html->link(
				$artwork->archive->name,
				'/works/view/'.$artwork->archive->slug
			);	
		} else {
			$title = $this->escape($title);
		}

		$display_title = $title ? '<em>' . $title . '</em>' : '';

		if (isset($options['materials']) && $options['materials']) {
			$materials = $artwork->materials;
		} else {
			$materials = '';
		}
    
		$caption = array_filter( array(
			$display_artists,
			$display_title,
			$this->escape($materials),
			$this->escape($years),
			$this->escape($artwork->dimensions()),
			$this->escape($artwork->measurement_remarks)
    	));
    	
    	return implode(', ', $caption) . '.';

	}

}

?>
