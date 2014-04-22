<?php

namespace app\extensions\helper;

use \lithium\template\helper\Html;

use \lithium\data\entity\Record;

class Artwork extends \lithium\template\Helper {

	public function caption(Record $artwork, $options = array()) {

        $defaults = array(
            'link' => false,
            'materials' => false,
            'separator' => ', '
        );

        $options += $defaults;

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

		if ($options['link'] && !empty($artwork->archive)) {
			$html = new Html();
			$title = $html->link(
				$artwork->archive->name,
				'/works/view/'.$artwork->archive->slug
			);
		} else {
			$title = $this->escape($title);
		}

		$display_title = $title ? '<em>' . $title . '</em>' : '';

		if ($options['materials']) {
			$materials = $artwork->materials;
		} else {
			$materials = '';
		}

		$separator = $options['separator'];

		$caption = array_filter(array(
			$display_artists,
			$display_title,
			$this->escape($materials),
			$this->escape($years),
			$this->escape($artwork->dimensions()),
			$this->escape($artwork->measurement_remarks)
    	));

    	return implode($separator, $caption) . '.';

	}

	public function notes(Record $artwork, $options = array()) {

        $defaults = array(
            'annotation' => true,
            'separator' => '<br/>'
        );

        $options += $defaults;

		$a = $artwork;

        $showAnnotation = !empty($a->annotation) && $options['annotation'] === true;

		$annotation = $showAnnotation ? '<em>' . $this->escape($a->annotation) . '</em>' : '';
		$quantity = $a->quantity ? 'Quantity: ' . $this->escape($a->quantity) : '';
		$remarks =  $a->remarks ? nl2br($this->escape($a->remarks)) : '';

		$edition = $a->attribute('edition') ? 'Edition: ' . $this->escape($a->attribute('edition')) : '';
		$signed = $a->attribute('signed') ? '<span class="label label-info">Signed</span>' : '';
		$framed = $a->attribute('framed') ? '<span class="label label-inverse">Framed</span>' : '';
		$certification = $a->attribute('certification') ? '<span class="label label-success">Certification</span>' : '';

		$info = array_filter(array(
			$annotation,
			$edition,
			$quantity,
			$remarks,
			$signed,
			$framed,
			$certification
		));

		$separator = $options['separator'];

		return implode($separator, $info);

	}

	public function inventory(Record $artwork, $options = array()) {

        $defaults = array(
            'separator' => '<br/>'
        );

        $options += $defaults;

		$a = $artwork;

		$buy_price = $a->attribute('buy_price') ? 'Purchase Price: ' . $this->escape($a->attribute('buy_price')) . ' <small>' . $this->escape($a->attribute('buy_price_per')) . '</small>' : '';
		$sell_price = $a->attribute('sell_price') ? 'Sale Price: ' . $this->escape($a->attribute('sell_price')) . ' <small>' . $this->escape($a->attribute('sell_price_per')) . '</small>' : '';

		$sell_date = $a->attribute('sell_date') ? 'Date of Sale: ' . $this->escape($a->attribute('sell_date'))  : '';

		$packing_type = $a->attribute('packing_type') ? 'Packing Type: ' . $this->escape($a->attribute('packing_type'))  : '';
		$pack_price = $a->attribute('pack_price') ? 'Packing Cost: ' . $this->escape($a->attribute('pack_price')) . ' <small>' . $this->escape($a->attribute('pack_price_per')) . '</small>' : '';
		$in_time = $a->attribute('in_time') ? 'Received Time: ' . $this->escape($a->attribute('in_time'))  : '';
		$in_from = $a->attribute('in_from') ? 'Sent From: ' . $this->escape($a->attribute('in_from'))  : '';
		$in_operator = $a->attribute('in_operator') ? 'Received By: ' . $this->escape($a->attribute('in_operator'))  : '';

		$info = array_filter(array(
			$buy_price,
			$sell_price,
			$sell_date,
			$packing_type,
			$pack_price,
			$in_time,
			$in_from,
			$in_operator
		));

		$separator = $options['separator'];

		return implode($separator, $info);

	}


}

?>
