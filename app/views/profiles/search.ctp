<h1>αναζήτηση συγκατοίκων</h1>
<?php
	echo $this->Form->create('Profile', array('action'=>'search'));

	$ageminoptions = array('label' => 'από');
	$agemaxoptions = array('label' => 'μέχρι');
	$maxmatesoptions = array('label' => 'ελάχιστοι επιθυμητοί συγκάτοικοι');
	echo "ηλικία\n<br />";
	echo $this->Form->input('agemin', $ageminoptions);
	echo $this->Form->input('agemax', $agemaxoptions)."\n";
	echo $this->Form->input('max_roommates', $maxmatesoptions);
	echo "<br />\n";
	$genderoptions = array('άνδρας', 'γυναίκα', 'αδιάφορο');
	$options = array('όχι', 'ναι', 'αδιάφορο');
	echo 'φύλο'. $this->Form->select('gender', $genderoptions) ."\n";
	echo 'καπνιστής'. $this->Form->select('smoker', $options) ."\n";
	echo 'κατοικίδιο'. $this->Form->select('pet', $options) ."\n";
	echo 'παιδί'. $this->Form->select('child', $options) ."\n";
	echo 'ζευγάρι'. $this->Form->select('couple', $options) ."<br /><br />\n";
	echo 'διαθέτει σπίτι'.$this->Form->checkbox('User.hasHouse',
		array('value' => 1, 'checked' => false, 'hiddenField' => false));

	echo $this->Form->end('αναζήτηση');
?>

<table>
	<tr>
        <td>όνομα</td>
		<td>επίθετο</td>
        <td>ηλικία</td>
        <td>φύλο</td>
<!--		
        <td>email</td>
        <td>τηλέφωνο</td>
        <td>καπνιστής</td>
        <td>κατοικίδιο</td>
        <td>παιδί</td>
        <td>ζευγάρι</td>
        <td>συγκάτοικοι</td>
-->
	</tr>

	<!-- Here is where we loop through our $profiles array, printing out info -->

    <?php
		$oddLine = true;
		foreach ($profiles as $profile):
			$rowCSS = ($oddLine)?'0':'1';
			$rowCSS = "bgcolor".$rowCSS;
	?>
    <tr class='<?php echo $rowCSS; ?>'>
		<td><?php echo $this->Html->link($profile['Profile']['firstname'],array('controller' => 'profiles',
			'action' => 'view', $profile['Profile']['id'])); ?></td>
		<td><?php echo $this->Html->link($profile['Profile']['lastname'],array('controller' => 'profiles',
			'action' => 'view', $profile['Profile']['id'])); ?></td>
		<td><?php echo $profile['Profile']['age']; ?></td>
		<?php
			$genderLabels = array('άνδρας', 'γυναίκα');
		?>
		<td><?php echo $genderLabels[$profile['Profile']['gender']]; ?></td>
<!--
		<td><?php echo $profile['Profile']['email']; ?></td>
		<td><?php echo $profile['Profile']['phone']; ?></td>
		<td><?php echo $profile['Profile']['smoker']; ?></td>
		<td><?php echo $profile['Profile']['pet']; ?></td>
		<td><?php echo $profile['Profile']['child']; ?></td>
		<td><?php echo $profile['Profile']['couple']; ?></td>
		<td><?php echo $profile['Profile']['max_roommates']; ?></td>
-->
		<td><?php echo $this->Html->link('διαγραφή',
					 array('action' => 'delete', $profile['Profile']['id']), null, 'Είστε σίγουρος;') ?>
		<?php echo $this->Html->link('επεξεργασία',
						 array('action' => 'edit', $profile['Profile']['id'])); ?> 
		</td>
    </tr>
    <?php
		$oddLine = !$oddLine;
		endforeach;
	?>
</table>


