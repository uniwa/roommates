<h2>Δημιουργία Προφίλ</h2>
<?php
   echo $this->Form->create('Profile');

	echo $this->Form->input('firstname', array('label' => 'Όνομα'));
	echo $this->Form->input('lastname', array('label' => 'Επώνυμο'));
	echo $this->Form->input('email', array('label' => 'Email'));
	echo $this->Form->input('age', array('label' => 'Ηλικία'));

	echo $this->Form->radio('gender',  array('0' => 'Άνδρας', '1' => 'Γυναίκα'),array('legend'=>false));


	echo $this->Form->input('phone', array('label' => 'Τηλέφωνο'));
	echo $this->Form->input('smoker', array('label' =>'Καπνιστής'));
	echo $this->Form->input('pet', array('label' =>'Κατοικίδιο'));
	echo $this->Form->input('child', array('label' => 'Παιδί'));
	echo $this->Form->input('couple', array('label' => 'Ζευγάρι'));
	echo $this->Form->input('max_roommates', array('label' =>'Μέγιστος αριθμός συγκατοίκων'));
echo $this->Form->input('visible', array('checked' => true ,'label' =>'Να γίνομαι ορατός σε αναζητήσεις χρηστών με βάση τα στοιχεία του προφίλ μου'));

	echo $this->Form->input('id', array('type' => 'hidden'));

	echo $this->Form->end('Αποθήκευση');

?>

