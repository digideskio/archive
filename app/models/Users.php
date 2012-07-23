<?php

namespace app\models;

use lithium\security\Password;
use lithium\util\Validator;

class Users extends \lithium\data\Model {

	public $belongsTo = array('Roles');

	public $validates = array(
		'username' => array(
			//array('uniqueUsername', 'message' => 'This username is already taken.'),
			array('notEmpty', 'message' => 'Please enter a username.'),
			array('alphaNumeric', 'skipEmpty' => true, 'message' => 'Alphanumeric characters only.'),
		),
        'password' => array(
            array('notEmpty', 'message'=>'Please enter a password.')
        ),
        'name' => array(
            array('notEmpty', 'message'=>'Please enter a full name.')
        ),
        'email' => array(
            array('notEmpty', 'message'=>'Include an email address.'),
            array('email', 'skipEmpty' => true, 'message' => 'The email address must be valid.')
        ),
    );
}

Validator::add('uniqueUsername', function($value, $rule, $options) {
	$conflicts = Users::count(array('username' => $value));
	if($conflicts) return false;
	return true;
});

//$entity - The record or document object to be saved in the database.
//$data - Any data that should be assigned to the record before it is saved
//$options - callbacks, validate, events, whitelist

Users::applyFilter('save', function($self, $params, $chain) {
	//Save the password from the record
	$record_password = $params['entity']->password;
	
	// If data is passed to the save function, set it in the record.  
    // This makes it possible to validate the data before continuing the save process.
    if ($params['data']) {
        $params['entity']->set($params['data']);
        $params['data'] = array();
    }
    
    //Examine the new password
    $data_password = $params['entity']->password;
    
    //Keep the current password if the record exists but the submitted password is blank
    if($params['entity']->exists() && !$data_password) {
    	$params['entity']->password = $record_password;
    }

	//Hash the password if the record does not exist and the password is set OR
	//if the record does exist but the password has changed
    if ( (!$params['entity']->exists() && $data_password) || ($data_password && $data_password != $record_password) ) {
        $params['entity']->password = Password::hash($params['entity']->password);
    }
    
    return $chain->next($self, $params, $chain);
});

?>
