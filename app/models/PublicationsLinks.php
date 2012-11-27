<?php

namespace app\models;

class PublicationsLinks extends \lithium\data\Model {

	public $belongsTo = array('Publications', 'Links');

	public $validates = array();
}

PublicationsLinks::applyFilter('save', function($self, $params, $chain) {

	$success = false;

	$url = isset($params['data']['url']) ? $params['data']['url'] : null;

	if ($url) {
		$link = Links::first(array(
			'conditions' => array('url' => $url)
		));

		if ($link) {
			$success = true;
		}

		if (!$link) {
			$link = Links::create();
			$success = $link->save($params['data']);
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
