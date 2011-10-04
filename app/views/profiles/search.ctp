<div class='topframe'>
<h1>Αναζήτηση Συγκατοίκων</h1>
<?php

	echo $this->Form->create('Profile', array('action'=>'search'))."\n";

	$ageminoptions = array('label' => 'από: ', 'class' => 'short-textbox');
	$agemaxoptions = array('label' => 'μέχρι: ', 'class' => 'short-textbox');
	$maxmatesoptions = array('label' => 'ελάχιστοι επιθυμητοί συγκάτοικοι: ', 'class' => 'short-textbox');
	
	echo "<div class='short-field'>ηλικία</div>\n";
	echo "<div class='short-field'>".$this->Form->input('agemin', $ageminoptions)."</div>\n";
	echo "<div class='short-field'>".$this->Form->input('agemax', $agemaxoptions)."</div>\n";
	echo "<div class='clear-both'>&nbsp;</div>\n";
	echo $this->Form->input('max_roommates', $maxmatesoptions)."\n";
	echo "<br />\n";
	
	$genderoptions = array('Άνδρας', 'Γυναίκα', 'Αδιάφορο');
	$options = array('Όχι', 'Ναι', 'Αδιάφορο');
?>
<table>
	<tr>
		<td>
<?php
	echo $this->Form->input('gender', array('label' => 'Φύλο ', 'options' => $genderoptions, 'default' => '2'))."\n";
	echo $this->Form->input('smoker', array('label' => 'Καπνιστής ', 'options' => $options, 'default' => '2'))."\n";
?>
		</td>
	</tr>
	<tr>
		<td>
<?php
	echo $this->Form->input('pet', array('label' => 'Κατοικίδιο ', 'options' => $options, 'default' => '2'))."\n";
	echo $this->Form->input('child', array('label' => 'Παιδί ', 'options' => $options, 'default' => '2'))."\n";
?>
		</td>
		<td>
	</tr>
	<tr>
		<td>
<?php
	echo $this->Form->input('couple', array('label' => 'Ζευγάρι ', 'options' => $options, 'default' => '2'))."<br /><br />\n";
?>
		</td>
		<td>
			&nbsp;
		</td>
	</tr>
</table>
<?php
	echo 'διαθέτει σπίτι '.$this->Form->checkbox('User.hasHouse',
		array('value' => 1, 'checked' => false, 'hiddenField' => false))."<br /><br />\n";
	echo $this->Form->end('αναζήτηση');
?>
</div>

<!--
<table>
	<tr>
        <td>όνομα</td>
		<td>επίθετο</td>
        <td>ηλικία</td>
        <td>φύλο</td>
		
        <td>email</td>
        <td>τηλέφωνο</td>
        <td>καπνιστής</td>
        <td>κατοικίδιο</td>
        <td>παιδί</td>
        <td>ζευγάρι</td>
        <td>συγκάτοικοι</td>

	</tr>


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

		<td><?php echo $profile['Profile']['email']; ?></td>
		<td><?php echo $profile['Profile']['phone']; ?></td>
		<td><?php echo $profile['Profile']['smoker']; ?></td>
		<td><?php echo $profile['Profile']['pet']; ?></td>
		<td><?php echo $profile['Profile']['child']; ?></td>
		<td><?php echo $profile['Profile']['couple']; ?></td>
		<td><?php echo $profile['Profile']['max_roommates']; ?></td>

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
-->

    <ul class="thelist">

        <?php
		$oddLine = true;
		foreach ($profiles as $profile):
			$rowCSS = ($oddLine)?'0':'1';
			$rowCSS = "bgcolor".$rowCSS;
	?>


        <li>
            <div class="photo">
                <img src="<?php echo $this->webroot; ?>img/profile_avatar.png" alt="Profile Picture" class="avatar"/>

            </div>
            <div class="info">
                <?php echo $this->Html->link($profile['Profile']['firstname'].' '.$profile['Profile']['lastname'],array('controller' => 'profiles',
			'action' => 'view', $profile['Profile']['id'])); ?>

               Ηλικία: <?php echo $profile['Profile']['age']; ?>
<br />
    <?php if ($profile['Profile']['gender'])
        echo '<p class="female">Γυναίκα.</p>';
    else
        echo '<p class="male">Άνδρας.</p>';
    ?>
    <p class="smoking"> <?php if (!$profile['Profile']['smoker']) echo "Δεν"; ?> Είμαι Καπνιστής.</p>

    <p class="tel">Τηλέφωνο: <?php echo $profile['Profile']['phone'] ?></p>


            </div>

            <div class="aboutme">

            </div>

        </li>



            <?php
		$oddLine = !$oddLine;
		endforeach;

	?>

    </ul>


