<div class='topframe'>
<h1>Αναζήτηση Συγκατοίκων</h1><br/>

<?php
    echo "<div class='clear-both'></div>";
	echo $this->Form->create('Profile', array('action'=>'search'))."\n";

	$ageminoptions = array('label' => 'Ηλικία από ', 'class' => 'short-textbox');
	$agemaxoptions = array('label' => 'μέχρι ', 'class' => 'short-textbox');
	$maxmatesoptions = array('label' => 'Ελάχιστοι επιθυμητοί συγκάτοικοι ', 'class' => 'short-textbox');
    $genderoptions = array('Άνδρας', 'Γυναίκα', 'Αδιάφορο');
    $options = array('Όχι', 'Ναι', 'Αδιάφορο');
?>

<table>
    <tr>
        <td>
<?php
	echo $this->Form->input('agemin', $ageminoptions);
    echo '</td><td>';
	echo $this->Form->input('agemax', $agemaxoptions);
    echo '</td><td>';
	echo $this->Form->input('max_roommates', $maxmatesoptions);
?>
        </td>
    </tr>
    <tr>
        <td>

<?php
	echo $this->Form->input('gender', array('label' => 'Φύλο ',
                                             'options' => $genderoptions,
                                             'default' => '2'));
    echo '</td><td>';
	echo $this->Form->input('smoker', array('label' => 'Καπνιστής ',
                                            'options' => $options,
                                            'default' => '2'));
    echo '</td><td>';
    echo $this->Form->input('pet', array('label' => 'Κατοικίδιο ',
                                         'options' => $options,
                                         'default' => '2'));
?>
		</td>
	</tr>
	<tr>
		<td>
<?php

	echo $this->Form->input('child', array('label' => 'Παιδί ',
                                           'options' => $options,
                                           'default' => '2'));
    echo '</td><td>';
    echo $this->Form->input('couple', array('label' => 'Ζευγάρι ',
                                            'options' => $options,
                                            'default' => '2'));
    echo '</td><td>';
    echo 'Διαθέτει σπίτι '.$this->Form->checkbox('User.hasHouse', array('value' => 1,
                                                                        'checked' => false,
                                                                        'hiddenField' => false));
?>
		</td>

	</tr>
	<tr><td>&nbsp;</td></tr>
</table>

<table>
    <tr>
        <td>
<?php
    echo $this->Form->submit('αναζήτηση', array('name' => 'simplesearch'));
    echo '</td><td>';
    echo $this->Form->submit('αναζήτηση με βάση τις προτιμήσεις μου', array('name' => 'searchbyprefs'));
    echo '</td><td>';
    echo $this->Form->submit('αποθήκευση αναζήτησης', array('name' => 'savesearch'));
	echo $this->Form->end();
?>
        </td>
    </tr>
</table>
</div>

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

                    <?php if ($profile['Profile']['gender'])
        echo '<p class="smoker">Είμαι Καπνιστής.</p>';
    else
        echo '<p class="nosmoker">Δεν Είμαι Καπνιστής.</p>';
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


