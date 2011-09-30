<?php

class Preference extends AppModel {
    var $name = 'Preference';
    var $hasOne = array('Profile');
    //var $validate = array();
}

?>
