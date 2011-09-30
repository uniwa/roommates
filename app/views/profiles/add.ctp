<h2>Δημιουργία Προφίλ</h2>
<?php
   echo $this->Form->create('Profile', array('action'=>'add'));


	echo $this->Form->input('firstname', array('label' => 'Όνομα'));
	echo $this->Form->input('lastname', array('label' => 'Επώνυμο'));
	echo $this->Form->input('email', array('label' => 'Email'));
	//echo $this->Form->input('age', array('label' => 'Ηλικία'));a
    echo $form->input('dob', array('label' => 'Ημερομηνία γέννησης', 'type' => 'select',
                    'options' => $available_birth_dates));


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

    $genderLabels = array('Άνδρας', 'Γυναίκα', 'Αδιάφορο');
    $options = array('Ναι', 'Όχι', 'Αδιάφορο');

	echo $this->Form->input('Preference.age_min', array('label' => 'Ηλικία από'));
	echo $this->Form->input('Preference.age_max', array('label' => 'έως'));

	echo $this->Form->input('Preference.mates_min', array('label' => 'Αριθμός Συγκατοίκων από'));
	echo $this->Form->input('Preference.mates_max', array('label' => 'έως'));

    echo $this->Form->input('Preference.pref_gender', array('label' => 'Φύλο', 'type' => 'select', 'options' => $genderLabels));
    echo $this->Form->input('Preference.pref_smoker', array('label' => 'Καπνιστής', 'type' => 'select', 'options' => $options));
    echo $this->Form->input('Preference.pref_pet', array('label' => 'Κατοικίδιο', 'type' => 'select', 'options' => $options));
    echo $this->Form->input('Preference.pref_child', array('label' => 'Παιδί', 'type' => 'select', 'options' => $options));
    echo $this->Form->input('Preference.pref_couple', array('label' => 'Ζευγάρι', 'type' => 'select', 'options' => $options));


    echo $this->Form->end('Υποβολή');
?>




