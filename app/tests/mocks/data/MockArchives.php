<?php

namespace app\tests\mocks\data;

use lithium\data\collection\RecordSet;
use lithium\security\Password;

class MockArchives extends \app\models\Archives {

	// TODO figure out how to mock relations
	protected static function _relations() {

	}

    public static function find($type = 'all', array $options = array()) {
        switch ($type) {
            case 'first':
                return new RecordSet(array('data' => array(
                    'id' => 1,
                    'title' => 'Archive Title',
                    'slug' => 'Archive-Slug',
                    'earliest_date' => '1999-11-12',
                    'latest_date' => '2001-03-04',
                )));
            break;
            case 'all':
            default :
                return new RecordSet(array('data' => array(
					array(
						'id' => 1,
						'title' => 'Archive Title',
						'slug' => 'Archive-Slug',
						'earliest_date' => '1999-11-12',
						'latest_date' => '2001-03-04',
					),
                )));
            break;
        }
    }
}

?>
