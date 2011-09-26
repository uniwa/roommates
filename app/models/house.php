<?php

class House extends AppModel {
    var $name = 'House';
    var $hasOne = 'HouseType';
    var $hasOne = 'Floor';
    var $hasOne = 'HeatingType';
}

?>
