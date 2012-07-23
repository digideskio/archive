<?php

namespace app\tests\mocks\data;

use lithium\data\collection\RecordSet;
use lithium\security\Password;

class MockUsers extends \app\models\Users {

	// TODO figure out how to mock relations
	protected static function _relations() {
	
	}
	
    public static function find($type = 'all', array $options = array()) {
        $user_pass = Password::hash('password');
        switch ($type) {
            case 'first':
                return new RecordSet(array('data' => array(
                    'id' => 1,
                    'username' => 'user1',
                    'name' => 'User One',
                    'email' => 'one@example.com',
                    'role_id' => '3',
                    'password' => $user_pass
                )));
            break;
            case 'all':
            default :
                return new RecordSet(array('data' => array(
                    array(
                    'id' => 1,
                    'username' => 'user1',
                    'name' => 'User One',
                    'email' => 'one@example.com',
                    'role_id' => '3',
                    'password' => $user_pass
                    )
                )));
            break;
        }
    }
}

?>
