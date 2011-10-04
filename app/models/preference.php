<?php

class Preference extends AppModel {

    var $name = 'Preference';

    var $hasOne = array('Profile' => array('className' => 'Profile',
					   'conditions' => array('Profile.visible' => '1'),
					   'foreignKey' => 'preference_id',
					   'dependent' => true)
    );


   var $validate = array(

	'age_min' => array(
		'rule' => array('range', 17, 100),
		'message' => 'Εισάγετε ηλικία μεταξύ 18 και 99',
		'required' => false,
		'allowEmpty' => true),

	'age_max' => array(
		'rule' => array('range', 17, 100 ),
		'message' => 'Εισάγετε ηλικία μεταξύ 18 και 99',
		'required' => false,
		'allowEmpty' => true),		

	'pref_gender' => array(
		'rule' => array('inList', array(0, 1, 2)),
		'message' => 'Εισάγετε ένα έγκυρο φύλο',
		'required' => false,
		'allowEmpty' => true),
		

	'mates_min' => array(
		'rule' => '/^[1-9]{1}$/i',
		'message' => 'Εισάγετε έγκυρo κάτω φράγμα συγκατοίκων [1,9]',
		'required' => false,
		'allowEmpty' => true),

	'pref_smoker' => array(
		'rule' => array('inList', array(0, 1, 2)),
		'message' => 'Εισάγετε μία έγκυρη απάντηση',
		'required' => false,
		'allowEmpty' => true),
 
	'pref_pet' => array(
		'rule' => array('inList', array(0, 1, 2)),
		'message' => 'Εισάγετε μία έγκυρη απάντηση',
		'required' => false,
		'allowEmpty' => true),

	'pref_child' => array(
		'rule' => array('inList', array(0, 1, 2)),
		'message' => 'Εισάγετε μία έγκυρη απάντηση',
		'required' => false, 
		'allowEmpty' => true),

	'pref_couple' => array(
		'rule' => array('inList', array(0, 1, 2)),
		'message' => 'Εισάγετε μία έγκυρη απάντηση',
		'required' => false,
		'allowEmpty' => true));
}
?>
