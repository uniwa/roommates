
<h2>Προσθήκη κατοικίας</h2>
<?php
echo $form->create('House');

echo $form->input('house_type_id', array('label' => 'Τύπος κατοικίας', 'empty' => 'Επιλέξτε...'));
echo $form->input('address', array('label' => 'Διεύθυνση'));
echo $form->input('postal_code', array('label' => 'Τ.K.'));
echo $form->input('area', array('label' => 'Εμβαδό'));
echo $form->input('floor_id', array('label' => 'Όροφος', 'empty' => 'Επιλέξτε...'));
echo $form->input('bedroom_num', array('label' => 'Αριθμός δωματίων'));
echo $form->input('bathroom_num', array('label' => 'Αριθμός μπάνιων'));
echo $form->input('price', array('label' => 'Τιμή'));
echo $form->input('availability_date', array('label' => 'Διαθέσιμο από', 'empty' => '---',
                    'dateFormat' => 'DMY', 'minYear' => date('Y'), 'maxYear' => date('Y') + 5));
echo $form->input('construction_year', array('label' => 'Έτος κατασκευής', 'type' => 'select', 
                    'options' => $available_constr_years, 'empty' => 'Άγνωστο'));
echo $form->input('solar_heater', array('label' => 'Ηλιακός θερμοσίφωνας'));
echo $form->input('furnitured', array('label' => 'Επιπλωμένο'));
echo $form->input('heating_type_id', array('label' => 'Είδος θέρμανσης', 'empty' => 'Επιλέξτε...'));
echo $form->input('aircondition', array('label' => 'Κλιματισμός'));
echo $form->input('garden', array('label' => 'Κήπος'));
echo $form->input('parking', array('label' => 'Θέση πάρκινγκ'));
echo $form->input('shared_pay', array('label' => 'Κοινόχρηστα'));
echo $form->input('security_doors', array('label' => 'Πόρτες ασφαλείας'));
echo $form->input('disability_facilities', array('label' => 'Προσβάσιμο από ΑΜΕΑ'));
echo $form->input('storeroom', array('label' => 'Αποθήκη'));
echo $form->input('rent_period', array('label' => 'Περίοδος ενοικίασης'));
echo $form->input('description', array('label' => 'Περιγραφή','type'=>'texrea'));


echo $form->input('currently_hosting', array('label' => 'Διαμένουν','type'=>'select', 'options' => $hosting_or_available));
echo $form->input('currently_available', array('label' => 'Διαθεσιμότητα','type'=>'select', 'options' => $hosting_or_available));

echo $form->end('Αποθήκευση');
?>

