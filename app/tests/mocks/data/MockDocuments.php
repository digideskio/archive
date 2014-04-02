<?php

namespace app\tests\mocks\data;

use lithium\data\collection\RecordSet;

class MockDocuments extends \app\models\Documents {

	// TODO figure out how to mock relations
	protected static function _relations() {

	}

    public static function find($type = 'all', array $options = array()) {
        switch ($type) {
            case 'first':
                return new RecordSet(array('data' => array(
                    'id' => 1,
                    'title' => 'Title One',
                    'hash' => 'c4ca4238a0b923820dcc509a6f75849b',
					'slug' => 'Title-One',
                    'format_id' => 792, //tiff
                )));
            break;
            case 'all':
            default :
                return new RecordSet(array('data' => array(
                    array(
                    'id' => 1,
                    'title' => 'Title One',
                    'hash' => 'c4ca4238a0b923820dcc509a6f75849b',
					'slug' => 'Title-One',
                    'format_id' => 792, //tiff
                    )
                )));
            break;
        }
    }
}

?>
