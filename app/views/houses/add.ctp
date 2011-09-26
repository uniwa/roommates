
<h1>Add House</h1>
<?php
echo $form->create('House');

echo $form->input('house_type_id');
echo $form->input('address');
echo $form->input('postal_code');
echo $form->input('area');
echo $form->input('floor_id');
echo $form->input('bedroom_num');
echo $form->input('bathroom_num');
echo $form->input('price');
echo $form->input('availability_date');
echo $form->input('construction_year');
echo $form->input('solar_heater');
echo $form->input('furnitured');
echo $form->input('heating_type_id');
echo $form->input('aircondition');
echo $form->input('garden');
echo $form->input('parking');
echo $form->input('shared_pay');
echo $form->input('security_doors');
echo $form->input('disability_facilities');
echo $form->input('storeroom');
echo $form->input('rent_period');
echo $form->input('description');

echo $form->end('Save house');
?>

