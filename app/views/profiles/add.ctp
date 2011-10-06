<h2>Δημιουργία Προφίλ</h2>
<?php
	echo $this->Form->create('Profile', array('action'=>'add'));

	echo $this->Form->input('firstname', array('label' => 'Όνομα'));
	echo $this->Form->input('lastname', array('label' => 'Επώνυμο'));
	echo $this->Form->input('email', array('label' => 'Email'));
	//echo $this->Form->input('age', array('label' => 'Ηλικία'));
	echo $form->input('dob', array('label' => 'Ημερομηνία γέννησης', 'type' => 'select', 'options' => $available_birth_dates));


	echo $this->Form->radio('Profile.gender', array('0' => 'Άνδρας', '1' => 'Γυναίκα'), array('legend'=>false));

	echo $this->Form->input('Profile.phone', array('label' => 'Τηλέφωνο'));
	echo $this->Form->input('Profile.smoker', array('label' =>'Είμαι καπνιστής'));
	echo $this->Form->input('Profile.pet', array('label' =>'Έχω κατοικίδιο'));
	echo $this->Form->input('Profile.child', array('label' => 'Έχω παιδί'));
	echo $this->Form->input('Profile.couple', array('label' => 'Συζώ'));
	echo $this->Form->input('Profile.we_are', array('label' => 'Είμαστε'));

	echo $this->Form->input('Profile.max_roommates', array('label' =>'Μέγιστος επιθυμητός αριθμός συγκατοίκων'));
	echo $this->Form->input('Profile.visible', array('checked' => true ,'label' =>'Να γίνομαι ορατός σε αναζητήσεις χρηστών με βάση τα στοιχεία του προφίλ μου'));
?>


<h2>Κριτήρια Επιλογής Συγκατοίκου</h2>

<?php

	$gender_labels = array('άνδρας', 'γυναίκα', 'αδιάφορο');
	$yni_options = array('όχι', 'ναι', 'αδιάφορο');


	echo $this->Form->input('Preference.age_min', array('label' => 'Ηλικία από'));
	echo $this->Form->input('Preference.age_max', array('label' => 'έως'));

	echo $this->Form->input('Preference.mates_min', array('label' => 'Ελάχιστος αριθμός συγκατοίκων'));

	echo $this->Form->input('Preference.pref_gender', array('label' => 'Φύλο', 'type' => 'select', 'options' => $gender_labels, 'selected' => 2));
	echo $this->Form->input('Preference.pref_smoker', array('label' => 'Είναι καπνιστής', 'type' => 'select', 'options' => $yni_options, 'selected' => 2));
	echo $this->Form->input('Preference.pref_pet', array('label' => 'Έχει κατοικίδιο', 'type' => 'select', 'options' => $yni_options, 'selected' => 2));
	echo $this->Form->input('Preference.pref_child', array('label' => 'Έχει παιδί', 'type' => 'select', 'options' => $yni_options, 'selected' => 2));
	echo $this->Form->input('Preference.pref_couple', array('label' => 'Συζεί', 'type' => 'select', 'options' => $yni_options, 'selected' => 2));

	echo "<br />\n";
	echo $this->Form->end('Υποβολή');
?>




