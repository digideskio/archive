<?php

namespace app\models;

class WorksLinks extends \lithium\data\Model {

	public $belongsTo = array('Works', 'Links');

	public $validates = array();
}

WorksLinks::applyFilter('save', function($self, $params, $chain) {

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

	if ($success) {
		return $chain->next($self, $params, $chain);
	} else {
		return false;
	}

});

?>
