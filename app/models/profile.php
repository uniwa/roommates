<?php

class Profile extends AppModel {
    var $name = 'Profile';
    var $hasMany = array('House');

    var $belongsTo = array(
        'Preference' => array(
            'className'    => 'Preference',
            'foreignKey'    => 'preference_id'
        )
    );

    var $virtualFields = array(
        'age' => "YEAR(NOW()) - Profile.dob"
    );

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

	'dob' => array(
		'rule' => array('isValidDate'),
		'message' => 'Βάλτε μια αποδεκτή ημερομηνία γέννησης.'),

	'agemin' => array(
		'rule' => array('between', 18, 98),
		'message' => 'Enter age between 18 and 99'),

	'agemax' => array(
		'rule' => array('between', 19, 99),
		'message' => 'Enter age between 18 and 99'),

	'gender' => array(
		'rule' => array('inList', array(0, 1)),
		'message' => 'Εισάγετε ένα έγκυρο φύλο'),

	'phone' => array(
		'rule' => '/^[0-9]{10}$/i',
		'message' => 'Εισάγετε ένα έγκυρο δεκαψήφιο τηλέφωνο επικοινωνίας'),
		
	'max_roommates' => array(
		'rule' => '/^[1-9]{1}$/i',
		'message' => 'Εισάγετε έναν έγκυρο αριθμό συγκατοίκων [1,9]')
	);

    function isValidDate($check) {
        $dob = (int)$check["dob"];
        return $dob >= date('Y')-80 && $dob <= date('Y')-17;
    }
}

?>
