<h2>Kατάλογος Δημόσιων Προφίλ</h2>
<?php echo $this->Html->link("Προσθήκη Νέου Προφίλ", array('action' => 'add'),array('class' => 'addButton')); ?>


<!--<p>--><?php //echo $this->Html->link("Αναζήτηση Συγκατοίκων", array('action' => 'search')); ?><!--</p>-->
<!-- OLD view
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
		<td><?php echo date('Y') - $profile['Profile']['dob']; ?></td>

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
<!--    <p class="smoking"> --><?php //if (!$profile['Profile']['smoker']) echo "Δεν"; ?><!-- Είμαι Καπνιστής.</p>-->


     <?php if ($profile['Profile']['smoker'])
        echo '<p class="smoker">Καπνιστής</p>';
    else
        echo '<p class="nosmoker">Δεν είμαι Καπνιστής.</p>';
    ?>


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
