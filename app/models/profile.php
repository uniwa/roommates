<?php

class Profile extends AppModel {

	var $name = 'Profile';

	var $belongsTo = array('Preference' =>
                            array('className' => 'Preference',
                                  'foreignKey' => 'preference_id'),
                            'User');

	var $virtualFields = array(
		'age' => "YEAR(NOW()) - Profile.dob"
	);

	var $validate = array(
		'firstname' => array(
			'rule' => '/^[a-zA-ZαβγδεζηθικλμνξοπρστυφχψωΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΆάΈέΎύΊίΌόΏώϊϋΐΰςήΉ]+$/',
			'message' => 'Εισάγετε ένα έγκυρο όνομα.'),

		'lastname' => array(
			'rule' => '/^[a-zA-ZαβγδεζηθικλμνξοπρστυφχψωΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΆάΈέΎύΊίΌόΏώϊϋΐΰςήΉ]+$/',
			'message' => 'Εισάγετε ένα έγκυρο επώνυμο.'),

		'email' => array(
			'rule' => 'email',
			'message' => 'Εισάγετε μία έγκυρη ηλεκτρονική διεύθυνση.'),

		'dob' => array(
			'rule' => array('isValidDate'),
			'message' => 'Εισάγετε μια έγκυρη ημερομηνία γέννησης.'),

		'gender' => array(
			'rule' => array('inList', array(0, 1)),
			'message' => 'Εισάγετε ένα έγκυρο φύλο'),

		'phone' => array(
			'rule' => '/^[0-9]{10}$/i',
			'message' => 'Εισάγετε ένα έγκυρο δεκαψήφιο τηλέφωνο επικοινωνίας',
			'required' => false,
			'allowEmpty' => true),

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
			'message' => 'Υπήρξε κάποιο σφάλμα.',
	        	'required' => false,
	        	'allowEmpty' => true),

		'max_roommates' => array(
			'rule' => '/^[1-9]{1}$/i',
			'message' => 'Εισάγετε έναν έγκυρο αριθμό συγκατοίκων από 1 έως 9 για τον μέγιστο αριθμό συγκατοίκων της οικίας'),

		'visible' => array(
			'rule' => '/^[0-1]$/',
			'message' => 'Υπήρξε κάποιο σφάλμα.',
			'required' => false,
			'allowEmpty' => true),

		'get_mail' => array(
			'rule' => '/^[0-1]$/',
			'message' => 'Υπήρξε κάποιο σφάλμα.',
			'required' => false,
			'allowEmpty' => true),

		'we_are' => array(

			'rule1'=>array(
				'rule' => '/^[1-8]{1}$/i',
				'message' => 'Εισάγετε έναν έγκυρο αριθμό από 1 έως 8 για το πόσα άτομα κατοικούν αυτή τη στιγμή στην οικία'),

			'coupleIsMinTwo'=>array(
				'rule' => array('coupleIsMinTwo', 'couple'),
				'message' => 'Με βάση το επιλεγμένο πεδίο Συζώ, επιβάλλεται η εισαγωγή 2 ή περισσότερων ατόμων')
		),
);

    var $defaults = array(
        'age_min' => NULL,
        'age_max' => NULL,
        'pref_gender' => 2,
        'pref_smoker' => 2,
        'pref_pet' => 2,
        'pref_child' => 2,
        'pref_couple' => 2,
        /* house preferences - only fields that don't default to NULL */
        'pref_furnitured' => 2,
        'pref_has_photo' => 0,
        'pref_disability_facilities' => 0);

	function isValidDate($check) {
		$dob = (int)$check["dob"];
		return $dob >= date('Y')-80 && $dob <= date('Y')-17;
	}

	function coupleIsMinTwo($weare, $iscouple){
		$v1 = $weare["we_are"];
		$v2 = $this->data[$this->name][$iscouple];

		if( ($v2 == 1 && $v1 < 2) )
			return false;

		else return true;
	}
}

?>
