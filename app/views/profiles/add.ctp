<h2>Δημιουργία Προφίλ</h2>
<?php
   echo $this->Form->create('Profile', array('action'=>'add'));

	echo $this->Form->input('Profile.firstname', array('label' => 'Όνομα'));
	echo $this->Form->input('Profile.lastname', array('label' => 'Επώνυμο'));
	echo $this->Form->input('Profile.email', array('label' => 'Email'));
	echo $this->Form->input('Profile.age', array('label' => 'Ηλικία'));

	echo $this->Form->radio('Profile.gender',  array('0' => 'Άνδρας', '1' => 'Γυναίκα'),array('legend'=>false));



	echo $this->Form->input('Profile.phone', array('label' => 'Τηλέφωνο'));
	echo $this->Form->input('Profile.smoker', array('label' =>'Καπνιστής'));
	echo $this->Form->input('Profile.pet', array('label' =>'Κατοικίδιο'));
	echo $this->Form->input('Profile.child', array('label' => 'Παιδί'));
	echo $this->Form->input('Profile.couple', array('label' => 'Ζευγάρι'));
	echo $this->Form->input('Profile.max_roommates', array('label' =>'Μέγιστος αριθμός συγκατοίκων'));



echo $this->Form->input('Profile.visible', array('checked' => true ,'label' =>'Να γίνομαι ορατός σε αναζητήσεις χρηστών με βάση τα στοιχεία του προφίλ μου'));
?>


<h2>Κριτήρια Επιλογής Συγκατοίκου</h2>

<?php

    $sexoptions = array('Άνδρας', 'Γυναίκα', 'Αδιάφορο');
    $options = array('Ναι', 'Όχι', 'Αδιάφορο');

	echo $this->Form->input('Preference.age_min', array('label' => 'Ηλικία από'));
	echo $this->Form->input('Preference.age_max', array('label' => 'έως'));

	echo $this->Form->input('Preference.mates_min', array('label' => 'Αριθμός Συγκατοίκων από'));
	echo $this->Form->input('Preference.mates_max', array('label' => 'έως'));

/*
    echo 'Φύλο'. $this->Form->select('sex', $sexoptions);
    echo 'Καπνιστής'. $this->Form->select('smoker', $options);
    echo 'Κατοικίδιο'. $this->Form->select('pet', $options);
    echo 'Παιδί'. $this->Form->select('child', $options);
    echo 'Ζευγάρι'. $this->Form->select('couple', $options);
*/

    echo $this->Form->end('Υποβολή');
?>




