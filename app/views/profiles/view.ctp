<style>
    #leftbar{
        float: left;
        margin: 0px 0px 0px 32px;
        padding: 32px;
    }
    
    #main-inner{
        float: left;
        border-left: 1px dotted #333;
        margin: 10px 0px 20px 0px;
        padding: 24px;
    }
    
    #profilePic{
        width: 128px;
        height: 128px;
        padding: 2px;
    }
    
    #profileEdit{
        margin: 64px 0px 0px 12px;
    }
    
    #profileRss{
        margin: 16px 0px 0px 0px;
    }
    
    #profileRss img{
        margin: 0px 4px 0px 0px;
    }
    
    .profileClear{
        clear: both;
    }
    
    .profileBlock{
        float: left;
        margin: 0px 64px 64px 16px;
        padding: 0px 8px 0px 8px;
    }

    .profileTitle{
        margin: 12px 0px 8px 18px;
        font-size: 1.2em;
        font-weight: bold;
    }

    .profileInfo{
        margin: 0px 0px 0px 24px;
        font-size: 1.0em;
    }
</style>
<?php
    // Profile info
	$name = $profile['Profile']['firstname']." ".$profile['Profile']['lastname'];
    if($this->Session->read("Auth.User.role") == 'admin'){
        $name = $name." (".$profile['User']['username'].")";
    }
	$age = $profile['Profile']['age'];
	$email = $profile['Profile']['email'];
	$phone = ($profile['Profile']['phone'])?$profile['Profile']['phone']:'-';
	$gender = ($profile['Profile']['gender'])?'γυναίκα':'άνδρας';
	$picture = ($profile['Profile']['gender'])?'female.jpg':'male.jpg';
	$smoker = ($profile['Profile']['smoker'])?'ναι':'όχι';
	$pet = ($profile['Profile']['pet'])?'ναι':'όχι';
	$couple = ($profile['Profile']['couple'])?'ναι':'όχι';
	$child = ($profile['Profile']['child'])?'ναι':'όχι';
	$weAre = $profile['Profile']['we_are'];
	$matesWanted = $profile['Profile']['max_roommates'];
	$name = Sanitize::html($name, array('remove' => true));
	$profileThumb = $picture;
    // Roommate preferences
	$prefgender = $profile['Preference']['pref_gender'];
	$prefsmoker = $profile['Preference']['pref_smoker'];
	$prefpet = $profile['Preference']['pref_pet'];
	$prefchild = $profile['Preference']['pref_child'];
	$prefcouple = $profile['Preference']['pref_couple'];
	$genderoptions = array('άνδρας', 'γυναίκα', 'αδιάφορο');
	$ynioptions = array('όχι', 'ναι', 'αδιάφορο');
	$prefgender = getPrefValue($prefgender, $genderoptions);
	$prefsmoker = getPrefValue($prefsmoker, $ynioptions);
	$prefpet = getPrefValue($prefpet, $ynioptions);
	$prefchild = getPrefValue($prefchild, $ynioptions);
	$prefcouple = getPrefValue($prefcouple, $ynioptions);
	$age_min = $profile['Preference']['age_min'];
	$age_max = $profile['Preference']['age_max'];
    // House preferences
	$prefFurnished = $profile['Preference']['pref_furnitured'];
	$prefAccessibility = $profile['Preference']['pref_disability_facilities'];
    $prefHousePhoto = $profile['Preference']['pref_has_photo'];
	$furnished = getPrefValue($prefFurnished, $ynioptions);
	$accessibility = ($prefAccessibility)?$ynioptions[1]:$ynioptions[2];
	$price_max = $profile['Preference']['price_max'];
	$area_min = $profile['Preference']['area_min'];
	$area_max = $profile['Preference']['area_max'];
    // House info
    if(isset($house)){
        $houseAddress = $house['House']['address'];
        $houseType = $house['HouseType']['type'];
        $housePrice = $house['House']['price'];
        $houseArea = $house['House']['area'];
        $houseFurnished = ($house['House']['furnitured'])?'επιπλωμένο':'';
        $houseMunicipality = $house['Municipality']['name'];
        $houseFree = $house['House']['free_places'];
        $houseId = $house['House']['id'];
        $houseThumb = $image;
    }
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
<div id='leftbar'>
    <div id='profilePic'>
        <?php
            $profilePic = $this->Html->image($profileThumb, array('alt' => $name));
            echo $profilePic;
        ?>
    </div>
    <div id='profileRss'>
        <?php
            $rssContent = $this->Html->image('rss.png', array('alt' => $name));
            $rssContent .= ' Προσωποποιημένο RSS';
            $rssLink = array('controller' => 'houses', 'action' => 'search.rss',
                '?' => array('token' => $profile["Profile"]["token"]));
            if($profile['Profile']['user_id'] == $this->Session->read('Auth.User.id')){
                $personalRSS = $this->Html->link(
                    $rssContent, $rssLink, array('escape' => false));
                echo $personalRSS;
            }
        ?>
    </div>
    <div id='profileEdit'>
        <?php
            if($this->Session->read('Auth.User.id') == $profile['User']['id']){
                echo $html->link('Επεξεργασία προφίλ',
                    array('action' => 'edit', $profile['Profile']['id']));
            }
        ?>
    </div>
</div>
<div id='main-inner'>
        <?php
/*            if($this->Session->read('Auth.User.role') == 'admin' &&
                $profile['Profile']['user_id'] != $this->Session->read('Auth.User.id')){
                if($profile['User']['banned'] == 0){
                    $flash = "Είστε σίγουρος ότι θέλετε να απενεργοποιήσετε τον λογαρισμό αυτού του χρήστη;";
                    echo $html->link('Ban',
                        array('action' => 'ban', $profile['Profile']['id']),
                        array('class' => 'ban-button'), $flash);
                }else{
                    echo $html->link('Unban',
                        array('action' => 'unban', $profile['Profile']['id']),
                        array('class' => 'unban-button'));
                }
            }*/
        ?>
    <div class='profileBlock profileClear'>
        <div id='myName' class='profileTitle'>
            <h2><?php echo $name; ?></h2>
        </div>
        <div id='myProfile' class='profileInfo'>    
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
			    if($weAre == 1){
				    echo "Είμαι <span class='profile-strong'>".$weAre."</span> άτομο";
			    }else{
				    echo "Είμαστε <span class='profile-strong'>".$weAre."</span> άτομα";
			    }
		    ?>
		    <br />
		    <?php
			    if($matesWanted == 1){
				    echo "Ζητείται <span class='profile-strong'>".$matesWanted."</span> άτομο";
			    }else{
				    echo "Ζητούνται <span class='profile-strong'>".$matesWanted."</span> άτομα";
			    }
			    echo '<br />';
			    $emailUrl = $this->Html->link($email, 'mailto:'.$email);
			    echoDetail('Email', $emailUrl);
			    echoDetail('Τηλέφωνο', $phone);
		    ?>
        </div>
    </div>
    <div class='profileBlock'>
        <div class='profileTitle'>
		    <h2>Προτιμήσεις συγκατοίκου</h2>
        </div>
        <div id='matePrefs' class='profileInfo'>
		    <?php
			    if(isset($age_min) && isset($age_max)){
				    if ($age_min == $age_max){?>
					    <span class='profile-strong'><?php
						    echo $profile['Preference']['age_min'];
				    }
				    else{
					    if($age_min){
				    ?>Ηλικία από: <span class='profile-strong'><?php
						    echo $profile['Preference']['age_min']." ";
					    }
					    if($age_max){
				    ?></span>μέχρι: <span class='profile-strong'><?php
						    echo $profile['Preference']['age_max'];
					    }
				    }
			    }elseif (isset($age_min) && is_null($age_max)){
                    ?>Ηλικία από: <span class='profile-strong'><?php
						    echo $profile['Preference']['age_min']." ";
			    }elseif (isset($age_max) && is_null($age_min)){
                    ?>Ηλικία μέχρι: <span class='profile-strong'><?php
						    echo $profile['Preference']['age_max']." ";
                }
		    ?></span><br />
		    <?php
                if($prefgender != 2) echoDetail('Φύλο', $prefgender);
			    if($prefgender != 2) echoDetail('Καπνιστής', $prefsmoker);
			    if($prefgender != 2) echoDetail('Κατοικίδιο', $prefpet);
			    if($prefgender != 2) echoDetail('Παιδί', $prefchild);
			    if($prefgender != 2) echoDetail('Ζευγάρι', $prefcouple);
		    ?></span>
        </div>
        <div class='profileTitle'>
	        <h2>Προτιμήσεις σπιτιού</h2>
        </div>
        <div id='housePrefs' class='profileInfo'>
		    <?php

			    if(isset($price_max)){?>
        			Τιμή: μέχρι
				    <span class='profile-strong'><?php
					    echo $profile['Preference']['price_max']."<br />\n";
			    }
		    ?></span>
		    <?php

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
                if($prefFurnished < '2' && $prefFurnished != null) {
			        echoDetail('Επιπλωμένο', $furnished);
                }
		        if($prefAccessibility){?>
                    <span class='profile-strong'>Προσβάσιμο από ΑΜΕΑ</span><br />
			    <?php }
                if($prefHousePhoto){?>
                    <span class='profile-strong'>Να διαθέτει φωτογραφία</span><br />
                <?php }
		    ?></span>
        </div>
    </div>
    <?php if(isset($house)){ ?>
        <div class='profileBlock profileClear'>
            <div class='profileTitle'>
	            <h2>Το σπίτι μου</h2>
            </div>
            <div id='myHouse' class='profileInfo'>
                <?php
                    echo $this->Html->image($houseThumb,
                        array('alt' => $houseAddress));
                        echo '<br />';
                    echo $this->Html->link($houseAddress,
                        array('controller' => 'houses', 'action' => 'view', $houseId));
                    echo '<br />'.$houseType.', '.$houseArea.' τ.μ.<br />';
                    echo $houseFurnished.', '.$housePrice.' €/μήνα';
                ?>
            </div>
        </div>
    <?php } ?>
</div>

