<?php

class Preference extends AppModel {

    var $name = 'Preference';

    var $hasOne = array('Profile' => array('className' => 'Profile',
					   'conditions' => array('Profile.visible' => '1'),
					   'foreignKey' => 'preference_id',
					   'dependent' => true)
    );


   var $validate = array(

	'agemin' => array(
		'rule' => array('between', 18, 98),
		'message' => 'Εισάγετε μία ηλικία μεταξύ 18 και 99'),

	'agemax' => array(
		'rule' => array('between', 19, 99),
		'message' => 'Εισάγετε μία ηλικία μεταξύ 18 και 99'),

	'gender' => array(
		'rule' => array('inList', array(0, 1, 2)),
		'message' => 'Εισάγετε ένα έγκυρο φύλο'),
		
	'mates_max' => array(
		'rule' => '/^[1-9]{1}$/i',
		'message' => 'Εισάγετε έναν έγκυρο αριθμό συγκατοίκων [1,9]'),

	'mates_min' => array(
		'rule' => '/^[1-9]{1}$/i',
		'message' => 'Εισάγετε έναν έγκυρο αριθμό συγκατοίκων [1,9]')
	);
 
}

?>
