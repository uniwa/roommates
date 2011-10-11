<div id='top-frame' class='frame'>
<div class='frame-container'>
	<div id='top-title' class='title'>
		<h1>Αναζήτηση συγκατοίκων</h1>
	</div>

<?php
    echo "<div class='clear-both'></div>";
    echo $this->Form->create('Profile', array('action'=>'search'));
	$ageminoptions = array( 'label' => 'Ηλικία από ',
                            'class' => 'short-textbox',
                            'value' => isset($defaults) ? $defaults['age_min'] : '',
                            'default' => '');
	$agemaxoptions = array( 'label' => 'μέχρι ',
                            'class' => 'short-textbox',
                            'value' => isset($defaults) ? $defaults['age_max'] : '',
                            'default' => '');
	/*$maxmatesoptions = array(   'label' => 'Ελάχιστοι επιθυμητοί συγκάτοικοι ',
                                'class' => 'short-textbox',
                                'value' => isset($defaults) ? $defaults['mates'] : '',
                                'default' => '');*/
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
	//echo $this->Form->input('max_roommates', $maxmatesoptions);
?>
        </td>
    </tr>
    <tr>
        <td>

<?php
	echo $this->Form->input('gender', array('label' => 'Φύλο ',
                                                'options' => $genderoptions,
                                                'value' => isset($defaults) ? $defaults['gender'] : '2',
                                                'default' => '2'    ));
    echo '</td><td>';
	echo $this->Form->input('smoker', array('label' => 'Καπνιστής ',
                                            'options' => $options,
                                            'default' => '2',
                                            'value' => isset($defaults) ? $defaults['smoker'] : '2' ));
    echo '</td><td>';
    echo $this->Form->input('pet', array(   'label' => 'Κατοικίδιο ',
                                            'options' => $options,
                                            'default' => '2',
                                            'value' => isset($defaults) ? $defaults['pet'] : '2'    ));
?>
		</td>
	</tr>
	<tr>
		<td>
<?php

	echo $this->Form->input('child', array( 'label' => 'Παιδί ',
                                            'options' => $options,
                                            'default' => '2',
                                            'value' => isset($defaults) ? $defaults['child'] : '2'  ));
    echo '</td><td>';
    echo $this->Form->input('couple', array('label' => 'Ζευγάρι ',
                                            'options' => $options,
                                            'default' => '2',
                                            'value' => isset($defaults) ? $defaults['couple'] : '2' ));
    echo '</td><td>';
    echo 'Διαθέτει σπίτι '.$this->Form->checkbox('User.hasHouse', array('value' => 1,
                                                                        'checked' => isset($defaults) ? $defaults['has_house'] : false,
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
    echo $this->Form->submit('αποθήκευση κριτηρίων αναζήτησης', array('name' => 'savesearch'));
	echo $this->Form->end();
?>
        </td>
    </tr>
</table>
</div>
</div>

<?php
	if(isset($profiles)){
?>
<div id='bottom-frame' class='frame'>
    <div class='frame-container'>
        <div id='bottom-title' class='title'>
            <h2>Αποτελέσματα αναζήτησης</h2>
        </div>
        <div id='bottom-subtitle' class='subtitle'>
            <?php
				$foundmessage = "Δεν βρέθηκαν προφίλ";
				if(isset($profiles)){
					$count = count($profiles);
					if($count > 0){
						$postfound = ($count == 1)?'ε ':'αν ';
						$foundmessage = "Βρέθηκ".$postfound.$count." προφίλ\n";
					}
				}
				echo $foundmessage;
            ?>
        </div>
        <div id='results-profiles' class='results'>
            <ul>
			<?php
				if(isset($profiles)) {
			?>
                <?php foreach ($profiles as $profile): ?>
                <li>
                    <div class='card'>
                        <div class='card-inner'>
                        <?php
                            $gender = ($profile['Profile']['gender'])?'fe':'';
                            echo "<div class='profile-pic ".$gender."male'>\n";
                        ?>
                        </div>
                        <div class='profile-info'>
                            <div class='profile-name'>
                                <?php
                                    echo $this->Html->link($profile['Profile']['firstname'].' '.$profile['Profile']['lastname'],
                                        array('controller' => 'profiles', 'action' => 'view', $profile['Profile']['id']));
                                ?>
                            </div>
                            <div class='profile-details'>
                                <?php
                                    echo $profile['Profile']['age'].", ";
                                    $gender = ($profile['Profile']['gender'])?'γυναίκα':'άνδρας';
                                    echo $gender."<br />\n";
                                    echo "email: ".$profile['Profile']['email']."<br />\n";
                                ?>
                            </div>
                            <div class='profile-house'>
                            </div>
                        </div>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
		<?php
			}
		?>
    </div>
</div>
<?php
	}
?>