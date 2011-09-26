
<h1>Edit Home</h1>
<?php
	echo $form->create('Home', array('action' => 'edit'));


echo $form->input('location');
echo $form->input('type');
echo $form->input('floor');
echo $form->input('embadon');
echo $form->input('price');
echo $form->input('construction_year');
echo $form->input('heat');
echo $form->input('gas');
echo $form->input('hliakos');
echo $form->input('epiplwmeno');
echo $form->input('fireplace');
echo $form->input('tentes');
echo $form->input('asanser');
echo $form->input('clima');
echo $form->input('porta_asfaleias');
echo $form->input('swimmingpool');
echo $form->input('koinoxrhsta');
echo $form->input('parking');
echo $form->input('roof');
echo $form->input('apothiki');
echo $form->input('garden');
echo $form->input('available_at');
echo $form->input('amea');
echo $form->input('description');



	echo $form->end('Save home');
?>
