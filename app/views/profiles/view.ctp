<div class="profile">

    <p class="name"><?php echo $profile['Profile']['firstname'] . " " . $profile['Profile']['lastname'] ?><span
            class="age"> - Ηλικία: <?php echo $profile['Profile']['age'] ?></span></p>

<div id="actions">
     <?php echo $this->Html->link('επεξεργασία',
						 array('action' => 'edit', $profile['Profile']['id'])); ?>

    <?php echo $this->Html->link('διαγραφή',
					 array('action' => 'delete', $profile['Profile']['id']), null, 'Είστε σίγουρος;') ?>

</div>


    <img src="<?php echo $this->webroot; ?>img/profile_avatar.png" alt="Profile Picture" class="avatar"/>

    <p class="mail">email: <?php echo $profile['Profile']['email'] ?></p>

    <?php if ($profile['Profile']['gender'])
        echo '<p class="female">Γυναίκα.</p>';
    else
        echo '<p class="male">Άνδρας.</p>';
    ?>
    <p class="tel">Τηλέφωνο: <?php echo $profile['Profile']['phone'] ?></p>


     <?php if ($profile['Profile']['smoker'])
        echo '<p class="smoker">Είμαι Καπνιστής</p>';
    else
        echo '<p class="nosmoker">Δεν είμαι Καπνιστής.</p>';
    ?>


    <p class="pet"> <?php if (!$profile['Profile']['pet']) echo "Δεν" ?> 'Εχω Κατοικίδιο.</p>

    <p class="kid"> <?php if (!$profile['Profile']['child']) echo "Δεν" ?> 'Εχω παιδί.</p>

    <p class="couple"><?php if (!$profile['Profile']['couple']) echo "Δεν" ?> Είμαστε Ζευγάρι.</p>

    <p class="rmates">Επιθυμώ να συγκατοικήσω με το πολυ <?php echo $profile['Profile']['max_roommates'] ?> άτομα.</p>

	<?php
	     $gender_options = array('άνδρας', 'γυναίκα', 'αδιάφορο');
		 $general_options = array('αδιάφορο', 'ναι', 'όχι');
	?>
	<h2>Κριτήρια Επιλογής Συγκατοίκου</h2>
	<?php
		$age_min = $profile['Preference']['age_min'];
		$age_max = $profile['Preference']['age_max'];
		$mates_min = $profile['Preference']['mates_min'];
		$mates_max = $profile['Preference']['mates_max'];
	?>
    <p>
		<?php
			if($age_min || $age_max){
                echo "Ηλικία: ";
                if($age_min){
				    echo "από ".$profile['Preference']['age_min'];
				}
				if($age_max){
				    echo " μέχρι ".$profile['Preference']['age_max'];
				}
			}
				
		?>
	</p>
    <p>
		<?php
			if($mates_min || $mates_max){
                echo "Αριθμός συγκατοίκων: ";
                if($mates_min){
				    echo "από ".$profile['Preference']['mates_min'];
				}
				if($mates_max){
				    echo " μέχρι ".$profile['Preference']['mates_max'];
				}
			}
				
		?>
	</p>
    <p><?php echo "Φύλο: ".$gender_options[$profile['Preference']['pref_gender']] ?></p>
    <p><?php echo "Καπνιστής: ".$general_options[$profile['Preference']['pref_smoker']] ?></p>
    <p><?php echo "Κατοικίδιο: ".$general_options[$profile['Preference']['pref_pet']] ?></p>
    <p><?php echo "Παιδί: ".$general_options[$profile['Preference']['pref_child']] ?></p>
    <p><?php echo "Ζευγάρι: ".$general_options[$profile['Preference']['pref_couple']] ?></p>
</div>
