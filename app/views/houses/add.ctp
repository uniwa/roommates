
<h1>Add House</h1>
<?php
echo $form->create('House');

echo $form->input('house_type_id', array('label' => 'Τύπος'));
echo $form->input('address', array('label' => 'Διεύθυνση'));
echo $form->input('postal_code', array('label' => 'Ταχυδρομικός κώδικας'));
echo $form->input('area', array('label' => 'Εμβαδό'));
echo $form->input('floor_id', array('label' => 'Όροφος'));
echo $form->input('bedroom_num', array('label' => 'Αριθμός δωματίων'));
echo $form->input('bathroom_num', array('label' => 'Αριθμός μπάνιων'));
echo $form->input('price', array('label' => 'Τιμή'));
echo $form->input('availability_date', array('label' => 'Διαθέσιμο από'));
echo $form->input('construction_year', array('label' => 'Έτος κατασκευής'));
echo $form->input('solar_heater', array('label' => 'Ηλιακός θερμοσίφωνας'));
echo $form->input('furnitured', array('label' => 'Επιπλομένο'));
echo $form->input('heating_type_id', array('label' => 'Είδος θέρμανσης'));
echo $form->input('aircondition', array('label' => 'Κλιματισμός'));
echo $form->input('garden', array('label' => 'Κήπος'));
echo $form->input('parking', array('label' => 'Θέση πάρκινγκ'));
echo $form->input('shared_pay', array('label' => 'Κοινόχρηστα'));
echo $form->input('security_doors', array('label' => 'Πόρτες ασφαλείας'));
echo $form->input('disability_facilities', array('label' => 'Προσβάσιμο από ΑΜΕΑ'));
echo $form->input('storeroom', array('label' => 'Αποθήκη'));
echo $form->input('rent_period', array('label' => 'Περίοδος ενοικίασης'));
echo $form->input('description', array('label' => 'Περιγραφή'));

echo $form->end('Save house');
?>

