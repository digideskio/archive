<?php namespace app\models;

class ViewWorksArtists extends \lithium\data\Model {

	public $belongsTo = array(
		'Works' => array (
			'to' => 'app\models\Works',
			'key' => 'work_id'
	));

	protected $_meta = array(
		'key' => 'work_id'
	);

	protected $_schema = array(
		'work_id'              => array('type' => 'integer', 'null' => 'false'),
		'artist_sort'          => array('type' => 'string', 'null' => false),
	);

}

?>
