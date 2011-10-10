<div id='top-frame' class='frame'>
	<div class='frame-container'>
		<div id='profile-edit'>
			<div id="actions">
				<?php
					if( ($this->Session->read('Auth.User.id') == $user['User']['id']) || ($this->Session->read('Auth.User.role') == 'admin') ){
						echo $html->link('Επεξεργασία', array('action' => 'edit', $user['Profile']['id']));
					}
				?>
			</div>
		</div>
		<div id='top-title' class='title'>
			<h1>
			<?php
				$name = $user['Profile']['firstname']." ".$user['Profile']['lastname'];
				$age = $user['Profile']['age'];
				$gender = ($user['Profile']['gender'])?'γυναίκα':'άνδρας';
				$smoker = ($user['Profile']['smoker'])?'ναι':'όχι';
				$pet = ($user['Profile']['pet'])?'ναι':'όχι';
				$couple = ($user['Profile']['couple'])?'ναι':'όχι';
				$child = ($user['Profile']['child'])?'ναι':'όχι';
				$weare = $user['Profile']['we_are'];

				//$koko = count($user['House']);
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

			<?php if (count($user['House']) != 0){
					//reads only the first users house
					$myhouse = $user['House'][0]['id'];?>
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
				$prefgender = $user['Profile']['Preference']['pref_gender'];
				$prefsmoker = $user['Preference']['pref_smoker'];
				$prefpet = $user['Preference']['pref_pet'];
				$prefchild = $user['Preference']['pref_child'];
				$prefcouple = $user['Preference']['pref_couple'];

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
				$age_min = $user['Preferences']['age_min'];
				$age_max = $user['Preference']['age_max'];
				if($age_min){
			?>από <span class='profile-strong'><?php
					echo $user['Preference']['age_min']." ";
				}
				if($age_max){
			?></span>μέχρι <span class='profile-strong'><?php
					echo $user['Preference']['age_max'];
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
