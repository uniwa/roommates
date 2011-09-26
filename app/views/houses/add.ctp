
<h1>Add Home</h1>
<?php
echo $form->create('House');

echo $form->input('house_type_id');
echo $form->input('address');
echo $form->input('area');
echo $form->input('bedroom_num');
echo $form->input('floor_id');
echo $form->input('price');
echo $form->input('availability_date');
echo $form->input('heating_type_id');


echo $form->end('Save house');
?>

