<h1>Edit Profile</h1>
<?php
	echo $this->Form->create('Profile');

	echo $this->Form->input('firstname');
	echo $this->Form->input('lastname');
	echo $this->Form->input('email');
	$options = array(18, 19, 20);
	echo 'age'. $this->Form->select('age', $options) ."\n";
	$options = array('M' => 'Male', 'F' => 'Female');
	echo 'sex'. $this->Form->select('sex', $options) ."\n";
	echo $this->Form->input('phone');
	echo $this->Form->input('smoker');
	echo $this->Form->input('pet');
	echo $this->Form->input('child');
	echo $this->Form->input('couple');
	echo $this->Form->input('max_roommates');
	echo $this->Form->input('visible');

	echo $this->Form->input('id', array('type' => 'hidden'));

	echo $this->Form->end('Save Profile');

	?>



