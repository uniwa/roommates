<div id='top-frame' class='frame'>
	<div class='frame-container'>
		<div id='top-title' class='title'>
			<h1>
			<?php
				$name = $profile['Profile']['firstname']." ".$profile['Profile']['lastname'];
                if ( $this->Session->read("Auth.User.role") == 'admin') {
                    $name = $name . " (" . $profile['User']['username'] . ")";
                }
				$age = $profile['Profile']['age'];
				$email = $profile['Profile']['email'];
				$phone = ($profile['Profile']['phone'])?$profile['Profile']['phone']:'-';
				$gender = ($profile['Profile']['gender'])?'γυναίκα':'άνδρας';
				$smoker = ($profile['Profile']['smoker'])?'ναι':'όχι';
				$pet = ($profile['Profile']['pet'])?'ναι':'όχι';
				$couple = ($profile['Profile']['couple'])?'ναι':'όχι';
				$child = ($profile['Profile']['child'])?'ναι':'όχι';
				$weare = $profile['Profile']['we_are'];
				$mates_wanted = $profile['Profile']['max_roommates'];

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

            <div id='profile-edit'>
                <div class="actions">
                    <?php
                        if ($this->Session->read('Auth.User.id') == $profile['User']['id']) {
                            echo $html->link('Επεξεργασία', array('action' => 'edit', $profile['Profile']['id']));
                        }
                        if ($this->Session->read('Auth.User.role') == 'admin' &&
                            $profile['Profile']['user_id'] != $this->Session->read('Auth.User.id')) {
                            if ($profile['User']['banned'] == 0) {
                                $flash = "Είστε σίγουρος ότι θέλετε να απενεργοποιήσετε τον λογαρισμό αυτού του χρήστη;";
                                echo $html->link('Ban', array('action' => 'ban', $profile['Profile']['id']),
                                            array('class' => 'ban-button'), $flash);
                            } else {
                                echo $html->link('Unban', array('action' => 'unban', $profile['Profile']['id']),
                                            array('class' => 'unban-button'));
                            }
                        }
                    ?>
                </div>
            </div>

			<span class='profile-strong'><?php echo $age; ?></span> ετών,
			<span class='profile-strong'><?php echo $gender; ?></span>
			<br />
			<?php
				echoDetail('Καπνιστής', $smoker);
				echoDetail('Κατοικίδιο', $pet);
				echoDetail('Παιδί', $child);
				echoDetail('Ζευγάρι', $couple);
			?>

			<?php
				if($weare == 1){
					echo "Είμαι <span class='profile-strong'>".$weare."</span> άτομο";
				}else{
					echo "Είμαστε <span class='profile-strong'>".$weare."</span> άτομα";
				}
			?>
			<br />
			<?php
				if($mates_wanted == 1){
					echo "Ζητείται <span class='profile-strong'>".$mates_wanted."</span> άτομο";
				}else{
					echo "Ζητούνται <span class='profile-strong'>".$mates_wanted."</span> άτομα";
				}
				echo '<br />';
				$emailUrl = $this->Html->link($email, 'mailto:'.$email);
				echoDetail('Email', $emailUrl);
				echoDetail('Τηλέφωνο', $phone);
			?>
			<br />
            <div class="my-house">
                <?php if ($houseid != NULL){
                        //reads only the first users house
                        echo $this->Html->link(
                            $this->Html->image("homedefault.png",
                                               array("alt" => "Το σπίτι μου",
                                                    "title" => "Το σπίτι μου",
                                                    "class" => "home-small",
                                                   "style" => "height:30px;vertical-align: bottom"
                                               )
                            ). " Το σπίτι μου ",
                                           "/houses/view/$houseid",
                                       array('escape'=>false,
                                               'style' => 'font-size:12px'));
                      } ?>
            </div>
		</div>
	</div>
</div>

<div id='bottom-frame' class='frame'>
	<div class='frame-container'>
		<div class='title'>
			<h2>Κριτήρια επιλογής συγκατοίκου</h2>
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
			Ηλικία:
			<?php
				$age_min = $profile['Preference']['age_min'];
				$age_max = $profile['Preference']['age_max'];

				if(isset($age_min) && isset($age_max)){
					if ($age_min == $age_max){?>
						<span class='profile-strong'><?php
							echo $profile['Preference']['age_min'];
					}
					else{
						if($age_min){
					?>από <span class='profile-strong'><?php
							echo $profile['Preference']['age_min']." ";
						}
						if($age_max){
					?></span>μέχρι <span class='profile-strong'><?php
							echo $profile['Preference']['age_max'];
						}
					}
				}else{
					?><span class='profile-strong'><?php
							echo 'αδιάφορο';
				}
			?></span><br />
			<?php
				echoDetail('Φύλο', $gender);
				echoDetail('Καπνιστής', $smoker);
				echoDetail('Κατοικίδιο', $pet);
				echoDetail('Παιδί', $child);
				echoDetail('Ζευγάρι', $couple);
			?></span>
		</div>
		<div class='title'>
			<h2>Κριτήρια επιλογής σπιτιού</h2>
		</div>
		<div id='house-preferences' class='options profile-big'>
			<?php
				$prefFurnished = $profile['Preference']['pref_furnitured'];
				$prefAccessibility = $profile['Preference']['pref_disability_facilities'];

				$ynioptions = array('όχι', 'ναι', 'αδιάφορο');

				$furnished = getPrefValue($prefFurnished, $ynioptions);
				$accessibility = ($prefAccessibility)?$ynioptions[1]:$ynioptions[2];

			?></span>
			<?php
				$price_max = $profile['Preference']['price_max'];

				if(isset($price_max)){?>
        			Τιμή: μέχρι
					<span class='profile-strong'><?php
						echo $profile['Preference']['price_max']."<br />\n";
				}
			?></span>
			<?php
				$area_min = $profile['Preference']['area_min'];
				$area_max = $profile['Preference']['area_max'];

				if(isset($area_min) || isset($area_max)){?>
    			Εμβαδόν:
				<?php
					if ($area_min == $area_max){?>
						<span class='profile-strong'><?php
							echo $profile['Preference']['area_min'];
					}
					else{
						if($area_min){
					?>από <span class='profile-strong'><?php
							echo $profile['Preference']['area_min']." ";
						}
						if($area_max){
					?></span> τ.μ. μέχρι <span class='profile-strong'><?php
							echo $profile['Preference']['area_max'];
						}
					}
					echo "</span> τ.μ.<br />\n";
				}
			?>
			<?php
			    if(isset($municipality)){
    				echoDetail('Δήμος', $municipality);
    			}
                if($prefFurnished < '2') {
				    echoDetail('Επιπλωμένο', $furnished);
                }
			    if($prefAccessibility){?>
                    <span class='profile-strong'>Προσβάσιμο από ΑΜΕΑ</span><br />
    			<?php }
			?></span>
		</div>
	</div>
</div>

<?php // echo $this->Html->link("Προσθήκη Προφίλ", array('action' => 'add')); ?>
