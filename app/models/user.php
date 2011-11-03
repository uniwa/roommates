<?php
class User extends AppModel{

	var $name = 'User';
	var $hasMany = array("House");
	var $hasOne = array('Profile', 'RealEstate');
}
?>
