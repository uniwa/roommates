<?php
class User extends AppModel{

	var $name = 'User';

	var $belongsTo = array( 

		'Profile' => array(
			 'className' => 'Profile',
			 'foreignKey' => 'profile_id'
		 )
	 );
}
?>
