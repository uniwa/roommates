<?php

class Profile extends AppModel {

	var $name = 'Profile';

	var $hasMany = array('House');

	var $belongsTo = array('Preference' => array('className'    => 'Preference',
						     'foreignKey'   => 'preference_id')
	);

	var $virtualFields = array(
		'age' => "YEAR(NOW()) - Profile.dob"
	);

	var $validate = array(
		'firstname' => array(
			//'rule' => '[\p{L}-[\p{isBasicLatin}\p{isGreek}]]', /*'[\p{Greek}]',*//*'/^[a-z]{2,}$/i',*/
			'rule' => '/^[a-zA-Z]{2,}$/i',
			//'rule' => '/^[α-ωΑ-Ω]{2,}$/i',
			'message' => 'Εισάγετε ένα έγκυρο όνομα.'),
	
		'lastname' => array(
			'rule' => '/^[a-zA-Z]{2,}$/i',
			'message' => 'Εισάγετε ένα έγκυρο επίθετο.'),
	
		'email' => array(
			'rule' => 'email',
			'message' => 'Εισάγετε μία έγκυρη ηλεκτρονική διεύθυνση.'),
	
		'dob' => array(
			'rule' => array('isValidDate'),
			'message' => 'Εισάγετε μια αποδεκτή ημερομηνία γέννησης.'),

		'gender' => array(
			'rule' => array('inList', array(0, 1)),
			'message' => 'Εισάγετε ένα έγκυρο φύλο'),
	
		'phone' => array(
			'rule' => '/^[0-9]{10}$/i',
			'message' => 'Εισάγετε ένα έγκυρο δεκαψήφιο τηλέφωνο επικοινωνίας'),
			
		'smoker' => array(
			'rule' => '/^[0-1]$/',
			'message' => 'Υπήρξε κάποιο σφάλμα.',
			'required' => false,
			'allowEmpty' => true),
	
		'pet' => array(
			'rule' => '/^[0-1]$/',
			'message' => 'Υπήρξε κάποιο σφάλμα.',
			'required' => false,
			'allowEmpty' => true),
	
		'child' => array(
			'rule' => '/^[0-1]$/',
			'message' => 'Υπήρξε κάποιο σφάλμα.',
			'required' => false,
			'allowEmpty' => true),
	
		'couple' => array(
			'rule' => '/^[0-1]$/',
			'rule' => '/^[0-1]$/',
	        	'required' => false,
	        	'allowEmpty' => true),
	
		'max_roommates' => array(
			'rule' => '/^[1-9]{1}$/i',
			'message' => 'Εισάγετε έναν έγκυρο αριθμό συγκατοίκων [1,9]'),
	
		'visible' => array(
			'rule' => '/^[0-1]$/',
			'message' => 'Υπήρξε κάποιο σφάλμα.',
			'required' => false,
			'allowEmpty' => true),
	);

	function isValidDate($check) {
		$dob = (int)$check["dob"];
		return $dob >= date('Y')-80 && $dob <= date('Y')-17;
	}
}

?>
