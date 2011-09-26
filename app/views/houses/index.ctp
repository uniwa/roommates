
<h1>houses</h1>
<table>
	<tr>
        <td>τύπος</td>
		<td>διεύθυνση</td>
        <td>ταχυδρομικός<br/>κώδικας</td>
        <td>όροφος</td>
        <td>εμβαδό</td>
        <td>αριθμός<br/>δωματίων</td>
        <td>αριθμός<br/>μπάνιων</td>
        <td>είδος<br/>θέρμανσης</td>
        <td>τιμή</td>
        <td>έτος<br/>κατασκευής</td>
        <td>επιπλομένο</td>
        <td>κλιματισμός</td>
        <td>ηλιακός<br/>θερμοσίφωνας</td>
        <td>κήπος</td>
        <td>πάρκινγκ</td>
        <td>κοινόχρηστα</td>
        <td>πόρτα<br/>ασφαλείας</td>
        <td>ΑΜΕΑ</td>
        <td>αποθήκη</td>
        <td>διαθέσιμο από</td>
        <td>περίοδος<br/>ενοικίασης</td>
        <td>περιγραφή</td>
	</tr>

	<!-- Here is where we loop through our $houses array, printing out info -->

    <?php foreach ($houses as $house): ?>
    <tr>
        <td><?php echo $house['House']['house_type_id']; ?></td>
        <td><?php echo $house['House']['address']; ?></td>
        <td><?php echo $house['House']['postal_code']; ?></td>
        <td><?php echo $house['House']['floor_id']; ?></td>
        <td><?php echo $house['House']['area']; ?></td>
        <td><?php echo $house['House']['bedroom_num']; ?></td>
        <td><?php echo $house['House']['bathroom_num']; ?></td>
        <td><?php echo $house['House']['heating_id']; ?></td>
        <td><?php echo $house['House']['price']; ?></td>
        <td><?php echo $house['House']['construction_year']; ?></td>
        <td><?php echo $house['House']['furnitured']; ?></td>
        <td><?php echo $house['House']['aircondition']; ?></td>
        <td><?php echo $house['House']['solar_heater']; ?></td>
        <td><?php echo $house['House']['garden']; ?></td>
        <td><?php echo $house['House']['parking']; ?></td>
        <td><?php echo $house['House']['shared_pay']; ?></td>
        <td><?php echo $house['House']['security_doors']; ?></td>
        <td><?php echo $house['House']['disability_facilities']; ?></td>
        <td><?php echo $house['House']['storeroom']; ?></td>
        <td><?php echo $house['House']['availability_date']; ?></td>
        <td><?php echo $house['House']['rent_period']; ?></td>
        <td><?php echo $house['House']['description']; ?></td>

        <td> <?php echo $html->link('Delete', array('action' => 'delete', $house['House']['id']), null, 'Are you sure?' )?></td>
        <td> <?php echo $html->link('Edit', array('action'=>'edit', $house['House']['id']));?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php echo $html->link('Add House',array('controller' => 'houses', 'action' => 'add'));?>