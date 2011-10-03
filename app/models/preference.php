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
		'rule' => array('between', 18, 98),
		'message' => 'Εισάγετε ηλικία μεταξύ 18 και 99'),

	'age_max' => array(
		'rule' => array('between', 19, 99),
		'message' => 'Εισάγετε ηλικία μεταξύ 18 και 99'),

	'pref_gender' => array(
		'rule' => array('inList', array(0, 1, 2)),
		'message' => 'Εισάγετε ένα έγκυρο φύλο'),
		
	'mates_max' => array(
		'rule' => '/^[1-9]{1}$/i',
		'message' => 'Εισάγετε έγκυρο άνω φράγμα συγκατοίκων [1,9]'),

	'mates_min' => array(
		'rule' => '/^[1-9]{1}$/i',
		'message' => 'Εισάγετε έγκυρo κάτω φράγμα συγκατοίκων [1,9]'),

	'pref_smoker' => array(
		'rule' => array('inList', array(0, 1, 2)),
		'message' => 'Εισάγετε μία έγκυρη απάντηση'),
 
	'pref_pet' => array(
		'rule' => array('inList', array(0, 1, 2)),
		'message' => 'Εισάγετε μία έγκυρη απάντηση'),

	'pref_child' => array(
		'rule' => array('inList', array(0, 1, 2)),
		'message' => 'Εισάγετε μία έγκυρη απάντηση'),

	'pref_couple' => array(
		'rule' => array('inList', array(0, 1, 2)),
		'message' => 'Εισάγετε μία έγκυρη απάντηση'));
}
?>
