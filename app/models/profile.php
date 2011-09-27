<?php

class Profile extends AppModel {
    var $name = 'Profile';
    var $validate = array(
	'firstname' => array(
		'rule' => 'alphaNumeric',
		'message' => 'Enter a valid first name'),

	'lastname' => array(
		'rule' => 'alphaNumeric',
		'message' => 'Enter a valid last name'),

	'email' => array(
		'rule' => 'email',
		'message' => 'Enter a valid email'),

	'age' => array(
		'rule' => array('between', 18, 99),
		'message' => 'Enter age between 18 and 99'),

	'agemin' => array(
		'rule' => array('between', 18, 98),
		'message' => 'Enter age between 18 and 99'),

	'agemax' => array(
		'rule' => array('between', 19, 99),
		'message' => 'Enter age between 18 and 99'),

	'sex' => array(
		'rule' => array('inList', array('Male', 'Female'))),

	'phone' => array(
		'rule' => array('decimal', 10),
		'message' => 'Please enter 10 digits'),
		
	'max_roommates' => array(
		'rule' => array('range', -1, 11),
		'message' => 'Please enter a number between 0 and 10')
	);

}

?>
