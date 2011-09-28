
<h1>houses</h1>
<table>
	<tr>
        <td>τύπος</td>
		<td>διεύθυνση</td>
        <td>όροφος</td>
        <td>εμβαδό</td>
        <td>αριθμός<br/>δωματίων</td>
        <td>είδος<br/>θέρμανσης</td>
        <td>τιμή</td>
        <td>διαθέσιμο από</td>
	</tr>

	<!-- Here is where we loop through our $houses array, printing out info -->

    <?php foreach ($houses as $house): ?>
    <tr>
        <td><?php echo $house['HouseType']['type']; ?></td>
        <td><?php echo $this->Html->link($house['House']['address'], 
                        array('controller' => 'houses', 'action' => 'view', 
                        $house['House']['id'])); ?></td>
        <td><?php echo $house['Floor']['type']; ?></td>
        <td><?php echo $house['House']['area']; ?></td>
        <td><?php echo $house['House']['bedroom_num']; ?></td>
        <td><?php echo $house['HeatingType']['type']; ?></td>
        <td><?php echo $house['House']['price']; ?></td>
        <td><?php echo $house['House']['availability_date']; ?></td>

        <td> <?php echo $html->link('Delete', array('action' => 'delete', $house['House']['id']), null, 'Are you sure?' )?></td>
        <td> <?php echo $html->link('Edit', array('action'=>'edit', $house['House']['id']));?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php echo $html->link('Add House',array('controller' => 'houses', 'action' => 'add'));?>