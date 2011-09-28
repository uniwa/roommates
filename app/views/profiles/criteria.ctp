<h1>Κριτήρια Επιλογής Συγκατοικου</h1>
<?php
	echo $this->Form->create('Profile', array('action'=>'criteria'));

	$minoptions = array('label' => 'από');
	$maxoptions = array('label' => 'μέχρι');
  
	$sexoptions = array('Ανδρας', 'Γυναίκα', 'Αδιάφορο');
	$options = array('Ναι', 'Οχι', 'Αδιάφορο');


	echo "Ηλικία\n<br />";
	echo $this->Form->input('min_age', $minoptions);
	echo $this->Form->input('max_age', $maxoptions)."\n";

	echo 'Φυλο'. $this->Form->select('sex', $sexoptions) ."\n";
	echo 'Καπνιστής'. $this->Form->select('smoker', $options) ."\n";
	echo 'Κατοικίδιο'. $this->Form->select('pet', $options) ."\n";
	echo 'Παιδί'. $this->Form->select('child', $options) ."\n";
	echo 'Ζευγάρι'. $this->Form->select('couple', $options) ."\n";
	
	echo "<br /><br />";

	echo "Αριθμος Συγκατοικων\n<br />";
	echo $this->Form->input('min_roommates', $minoptions);
	echo $this->Form->input('max_roommates', $maxoptions)."\n";


	echo $this->Form->end('Αποθηκευση Κριτηριων');
?>
