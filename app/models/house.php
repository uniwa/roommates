<?php

class House extends AppModel {
    var $name = 'House';
    var $hasOne = array('HouseType', 'Floor', 'HeatingType');
}

?>
