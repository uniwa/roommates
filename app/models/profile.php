<?php

class Profile extends AppModel {
    var $name = 'Profile';
    var $validate = array(
	'firstname' => array(
		'rule' => 'alphaNumeric',
		'message' => 'Εισάγετε ένα έγκυρο όνομα.'),

	'lastname' => array(
		'rule' => 'alphaNumeric',
		'message' => 'Εισάγετε ένα έγκυρο επίθετο.'),

	'email' => array(
		'rule' => 'email',
		'message' => 'Εισάγετε μία έγκυρη ηλεκτρονική διεύθυνση.'),

	'age' => array(
		'rule' => array('comparison', '>=', 18),
		'message' => 'Αποδεκτή ηλικία από 18 και άνω'),

	'sex' => array(
		'rule' => array('inList', array(0, 1)),
		'message' => 'Εισάγετε ένα έγκυρο φύλο'),

	'phone' => array(
		'rule' => '/^[0-9]{10}$/i',
		'message' => 'Εισάγετε ένα έγκυρο δεκαψήφιο τηλέφωνο επικοινωνίας'),
		
	'max_roommates' => array(
		'rule' => '/^[1-9]{1}$/i',
		'message' => 'Εισάγετε έναν έγκυρο αριθμό συγκατοίκων [1,9]')
	);

}

?>
