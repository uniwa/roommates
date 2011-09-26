<h1>public profiles</h1>

<p><?php echo $this->Html->link("Add Profile", array('action' => 'add')); ?></p>

<table>
	<tr>
        <td>όνομα</td>
	<td>επίθετο</td>
        <td>email</td>
        <td>ηλικία</td>
        <td>φύλο</td>
        <td>τηλέφωνο</td>
        <td>καπνιστής</td>
        <td>κατοικίδιο</td>
        <td>παιδί</td>
        <td>ζευγάρι</td>
        <td>συγκάτοικοι</td>
	</tr>

	<!-- Here is where we loop through our $profiles array, printing out info -->

    <?php foreach ($profiles as $profile): ?>
    <tr>
	<td><?php echo $this->Html->link($profile['Profile']['firstname'],array('controller' => 'profiles',
			'action' => 'view', $profile['Profile']['id'])); ?></td>
        <td><?php echo $this->Html->link($profile['Profile']['lastname'],array('controller' => 'profiles',
			'action' => 'view', $profile['Profile']['id'])); ?></td>
        <td><?php echo $profile['Profile']['email']; ?></td>
        <td><?php echo $profile['Profile']['age']; ?></td>
        <td><?php echo $profile['Profile']['sex']; ?></td>
        <td><?php echo $profile['Profile']['phone']; ?></td>
        <td><?php echo $profile['Profile']['smoker']; ?></td>
        <td><?php echo $profile['Profile']['pet']; ?></td>
        <td><?php echo $profile['Profile']['child']; ?></td>
        <td><?php echo $profile['Profile']['couple']; ?></td>
        <td><?php echo $profile['Profile']['max_roommates']; ?></td>

	<td><?php echo $this->Html->link('Delete', 
					 array('action' => 'delete', $profile['Profile']['id']),
					 null,
					 'Are you sure?') ?>
	    <?php echo $this->Html->link('Edit', 
				         array('action' => 'edit', $profile['Profile']['id'])); ?> 
	</td>

    </tr>
    <?php endforeach; ?>
</table>
