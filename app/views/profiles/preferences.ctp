<h1>Κριτήρια Επιλογής Συγκατοικου</h1>
<?php
	echo $this->Form->create('Profile', array('action'=>'preferences'));

	$minoptions = array('label' => 'από');
	$maxoptions = array('label' => 'μέχρι');
  
	$genderoptions = array('Ανδρας', 'Γυναίκα', 'Αδιάφορο');
	$options = array('Ναι', 'Οχι', 'Αδιάφορο');


	echo "Ηλικία\n<br />";
	echo $this->Form->input('min_age', $minoptions);
	echo $this->Form->input('max_age', $maxoptions)."\n";

	echo 'Φύλο'. $this->Form->select('gender', $sexoptions) ."\n";
	echo 'Καπνιστής'. $this->Form->select('smoker', $options) ."\n";
	echo 'Κατοικίδιο'. $this->Form->select('pet', $options) ."\n";
	echo 'Παιδί'. $this->Form->select('child', $options) ."\n";
	echo 'Ζευγάρι'. $this->Form->select('couple', $options) ."\n";
	
	echo "<br /><br />";

	echo "Αριθμός Συγκατοίκων\n<br />";
	echo $this->Form->input('min_roommates', $minoptions);
	echo $this->Form->input('max_roommates', $maxoptions)."\n";


	echo $this->Form->end('Αποθήκευση Κριτηρίων');
?>
