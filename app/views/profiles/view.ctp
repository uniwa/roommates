<div id='top-frame' class='frame'>
	<div class='frame-container'>
		<div id='profile-edit'>
			<div id="actions">
				<?php
					if( ($this->Session->read('Auth.User.id') == $profile['Profile']['id']) || ($this->Session->read('Auth.User.role') == 'admin') ){
						echo $html->link('Επεξεργασία', array('action' => 'edit', $profile['Profile']['id']));
					}
				?>
			</div>
		</div>
		<div id='top-title' class='title'>
			<h1>
			<?php
				$name = $profile['Profile']['firstname']." ".$profile['Profile']['lastname'];
				$age = $profile['Profile']['age'];
				$gender = ($profile['Profile']['gender'])?'γυναίκα':'άνδρας';
				$smoker = ($profile['Profile']['smoker'])?'ναι':'όχι';
				$pet = ($profile['Profile']['pet'])?'ναι':'όχι';
				$couple = ($profile['Profile']['couple'])?'ναι':'όχι';
				$child = ($profile['Profile']['child'])?'ναι':'όχι';
				$weare = $profile['Profile']['we_are'];

				//$koko = count($profile['House']);
				//echo '<pre>'; print_r($koko); echo '</pre>'; die();	

				echo Sanitize::html($name, array('remove' => true));
				
				function echoDetail($title, $option){
					$span = array("open" => "<span class='profile-strong'>", "close" => "</span>");
					echo $title.': '.$span['open'].$option.$span['close']."<br \>\n";
				}
				
				function getPrefValue($preference, $values){
					if($preference < 2){
						$preference = ($preference)?$values[1]:$values[0];
					}else{
						$preference = $values[2];
					}
					
					return $preference;
				}
			?>
			</h1>
		</div>
		<div id='main-details' class='options profile-big'>
			<div id='top-photo'>
				<img src='./img/profile_avatar.png' />
			</div>

			<span class='profile-strong'><?php echo $age; ?></span> ετών, <span class='profile-strong'>
			<?php echo $gender; ?></span><br />
			<?php
				echoDetail('Καπνιστής', $smoker);
				echoDetail('Κατοικίδιο', $pet);
				echoDetail('Παιδί', $child);
				echoDetail('Ζευγάρι', $couple);
			?></span>
			
			<?php if ($weare == 1)
				echo 'Είμαι ' .  $weare . ' άτομo';
			      else 
				echo 'Είμαστε ' .  $weare . ' άτομα'; ?>
			<br />

			<?php if (count($profile['House']) != 0){
					//reads only the first users house
					$myhouse = $profile['House'][0]['id'];?>
					<img src = "<?php echo $this->webroot;?>img/home.png"><a href="http://localhost/roommates/houses/view/<?php echo $myhouse?>">  Το σπίτι μου</a>
			<?php }?>
			
		</div>
	</div>
</div>

<div id='bottom-frame' class='frame'>
	<div class='frame-container'>
		<div id='bottom-title' class='title'>
			<h2>Προτιμήσεις συγκατοίκων</h2>
		</div>
		<div id='bottom-subtitle' class='subtitle'>
		</div>
		<div id='profile-preferences' class='options profile-big'>
			<?php
				$prefgender = $profile['Preference']['pref_gender'];
				$prefsmoker = $profile['Preference']['pref_smoker'];
				$prefpet = $profile['Preference']['pref_pet'];
				$prefchild = $profile['Preference']['pref_child'];
				$prefcouple = $profile['Preference']['pref_couple'];

				$genderoptions = array('άνδρας', 'γυναίκα', 'αδιάφορο');
				$ynioptions = array('όχι', 'ναι', 'αδιάφορο');

				$gender = getPrefValue($prefgender, $genderoptions);
				$smoker = getPrefValue($prefsmoker, $ynioptions);
				$pet = getPrefValue($prefpet, $ynioptions);
				$child = getPrefValue($prefchild, $ynioptions);
				$couple = getPrefValue($prefcouple, $ynioptions);
				
			?></span>
			<br />Ηλικία: 
			<?php
				$age_min = $profile['Preference']['age_min'];
				$age_max = $profile['Preference']['age_max'];
				if($age_min){
			?>από <span class='profile-strong'><?php
					echo $profile['Preference']['age_min']." ";
				}
				if($age_max){
			?></span>μέχρι <span class='profile-strong'><?php
					echo $profile['Preference']['age_max'];
				}
			?></span><br />
			<?php
				echoDetail('Φύλο', $gender);
				echoDetail('Καπνιστής', $smoker);
				echoDetail('Κατοικίδιο', $smoker);
				echoDetail('Παιδί', $smoker);
				echoDetail('Φύλο', $smoker);
			?></span>
		</div>
	</div>
</div>

<?php // echo $this->Html->link("Προσθήκη Προφίλ", array('action' => 'add')); ?>
