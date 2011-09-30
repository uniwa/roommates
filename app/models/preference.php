<?php

class Preference extends AppModel {
    var $name = 'Preference';
    //var $hasOne = array('Profile');
    //var $validate = array();

    var $hasOne = array(
        'Profile' => array(
        'className' => 'Profile',
        'conditions' => array('Profile.visible' => '1'),
        'foreignKey' => 'preference_id',
        'dependent' => true
        )
    ); 
}

?>
