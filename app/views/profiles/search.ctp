<h1>αναζήτηση συγκατοίκων</h1>
<?php
	echo $this->Form->create('Profile', array('action'=>'search'));

	$ageminoptions = array('label' => 'από');
	$agemaxoptions = array('label' => 'μέχρι');
	$maxmatesoptions = array('label' => 'ελάχιστοι επιθυμητοί συγκάτοικοι');
	echo "ηλικία\n<br />";
	echo $this->Form->input('agemin', $ageminoptions);
	echo $this->Form->input('agemax', $agemaxoptions)."\n";
	$sexoptions = array('άνδρας', 'γυναίκα', 'αδιάφορο');
	$options = array('όχι', 'ναι', 'αδιάφορο');
	echo 'sex'. $this->Form->select('sex', $sexoptions) ."\n";
	echo 'καπνιστής'. $this->Form->select('smoker', $options) ."\n";
	echo 'κατοικίδιο'. $this->Form->select('pet', $options) ."\n";
	echo 'παιδί'. $this->Form->select('child', $options) ."\n";
	echo 'ζευγάρι'. $this->Form->select('couple', $options) ."\n";
	echo $this->Form->input('max_roommates', $maxmatesoptions);

	echo $this->Form->end('αναζήτηση');
?>

<table>
	<tr>
        <td>Όνομα</td>
	<td>Επίθετο</td>
        <td>Email</td>
        <td>Ηλικία</td>
        <td>Φύλο</td>
        <td>Τηλέφωνο</td>
        <td>Καπνιστής</td>
        <td>Κατοικίδιο</td>
        <td>Παιδί</td>
        <td>Ζευγάρι</td>
        <td>Συγκάτοικοι</td>
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
					 'Είστε βέβαιος για τη διαγραφή;') ?>
	    <?php echo $this->Html->link('Edit', 
				         array('action' => 'edit', $profile['Profile']['id'])); ?> 
	</td>

    </tr>
    <?php endforeach; ?>
</table>


