
<h1>Επεξεργασία κατοικίας</h1>
<?php
echo $form->create('House', array('action' => 'edit'));

echo $form->input('house_type_id', array('label' => 'Τύπος κατοικίας'));
echo $form->input('address', array('label' => 'Διεύθυνση'));
echo $form->input('postal_code', array('label' => 'Ταχυδρομικός κώδικας'));
echo $form->input('area', array('label' => 'Εμβαδό'));
echo $form->input('floor_id', array('label' => 'Όροφος'));
echo $form->input('bedroom_num', array('label' => 'Αριθμός δωματίων'));
echo $form->input('bathroom_num', array('label' => 'Αριθμός μπάνιων'));
echo $form->input('price', array('label' => 'Τιμή'));
echo $form->input('availability_date', array('label' => 'Διαθέσιμο από', 
                    'dateFormat' => 'DMY', 'minYear' => date('Y'), 'maxYear' => date('Y') + 5));
echo $form->input('construction_year', array('label' => 'Έτος κατασκευής', 'type' => 'select', 
                    'options' => $available_constr_years, 'empty' => 'Άγνωστο'));
echo $form->input('solar_heater', array('label' => 'Ηλιακός θερμοσίφωνας'));
echo $form->input('furnitured', array('label' => 'Επιπλομένο'));
echo $form->input('heating_type_id', array('label' => 'Είδος θέρμανσης', 'empty' => 'Επιλέξτε...'));
echo $form->input('aircondition', array('label' => 'Κλιματισμός'));
echo $form->input('garden', array('label' => 'Κήπος'));
echo $form->input('parking', array('label' => 'Θέση πάρκινγκ'));
echo $form->input('shared_pay', array('label' => 'Κοινόχρηστα'));
echo $form->input('security_doors', array('label' => 'Πόρτες ασφαλείας'));
echo $form->input('disability_facilities', array('label' => 'Προσβάσιμο από ΑΜΕΑ'));
echo $form->input('storeroom', array('label' => 'Αποθήκη'));
echo $form->input('rent_period', array('label' => 'Περίοδος ενοικίασης'));
echo $form->input('description', array('label' => 'Περιγραφή'));

echo $form->input('id', array('type' => 'hidden'));

echo $form->end('Save house');
?>
