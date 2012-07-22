<?php

namespace app\tests\mocks\data;

use lithium\data\collection\RecordSet;
use lithium\security\Password;

class MockUsers extends \app\models\Users {
    public static function find($type = 'all', array $options = array()) {
        $user_pass = Password::hash('password');
        switch ($type) {
            case 'first':
                return new RecordSet(array('data' => array(
                    'id' => 1,
                    'username' => 'user1',
                    'name' => 'User One',
                    'email' => 'one@example.com',
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
                    'password' => $user_pass
                    )
                )));
            break;
        }
    }
}

?>
