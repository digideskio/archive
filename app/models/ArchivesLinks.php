<?php

namespace app\models;

use lithium\util\Validator;

class ArchivesLinks extends \lithium\data\Model {

	public $belongsTo = array('Archives', 'Links');

	public $validates = array(
		'archive_id' => array(
			array('notEmpty', 'message' => "You can't leave this blank.")
		),
	);
}

ArchivesLinks::applyFilter('save', function($self, $params, $chain) {

	$success = false;

	$url = isset($params['data']['url']) ? $params['data']['url'] : null;
	$title = isset($params['data']['title']) ? $params['data']['title'] : '';

	if ($url) {
		$link = Links::first(array(
			'conditions' => array('url' => $url)
		));

		if (!empty($link)) {
            $archive_id = $params['data']['archive_id'];

            // Check if this archive/link combo already exists
            $archives_link = ArchivesLinks::find('first', array(
                'conditions' => array(
                    'archive_id' => $archive_id,
                    'link_id' => $link->id
                )
            ));

            if (empty($archives_link)) {
			    $success = true;
            }
		}

		if (empty($link)) {
			$link = Links::create();
			$success = $link->save(compact('url', 'title'));
		}

		if ($success) {
			$params['data']['link_id'] = $link->id;
		}
	}

	if (!$url || $success) {
		return $chain->next($self, $params, $chain);
	} else {
		return false;
	}

});

?>
