<style>
    #leftbar{
        float: left;
        margin: 0px 0px 0px 0px;
        padding: 0px 0px 0px 0px;
        width: 300px;
        height: 100%;
    }

    #main-inner{
        float: left;
        border-left: 1px solid #ddd;
        margin: 10px 0px 20px 0px;
        padding: 24px;
        height: 100%;
    }

    .profilePic{
        margin: 0px auto;
        padding: 2px;
        width: 100px;
        height: 100px;
        overflow: hidden;
    }

    .housePic{
        margin: 0px auto;
        padding: 2px;
        width: 180px;
        height: 100px;
        overflow: hidden;
    }

    #profileCont{
        margin: 0px 0px 0px 0px;
        padding: 32px;
        text-align: center;
    }

    #profileEdit{
        margin: 32px 0px 0px 12px;
        text-align: center;
    }

    #profileBan{
        margin: 32px auto 0px auto;
    }

    .profileClear{
        clear: both;
    }

    .profileBlock{
        float: left;
        margin: 0px 32px 64px 0px;
        padding: 0px 8px 0px 8px;
        width: 240px;
        overflow: hidden;
    }

    .profileTitle{
        margin: 24px 0px 8px 18px;
        font-family: 'Didact Gothic', Verdana, Tahoma, Arial, sans-serif;
        font-size: 1.2em;
        color: #333;
    }

    .profileInfo{
        float: left;
        margin: 0px 0px 16px 24px;
        font-size: 1.0em;
    }

    .profileOptions{
        margin: 16px 0px 0px 0px;
    }

    .optionIcon{
        margin: 0px 4px 0px 0px;
        vertical-align: -30%;
    }

    #myHouse{
        width: 500px;
    }

    #myHousePic{
        margin: 0px 0px 0px 24px;
    }

    #myHouseInfo{
        margin: 24px 0px 0px 24px;
    }
</style>
<?php
    $role = $this->Session->read('Auth.User.role');
    $loggedUser = $this->Session->read('Auth.User.id');
    $profileid = $profile['Profile']['id'];
    $userid = $profile['Profile']['user_id'];
    // Profile info
	$name = $profile['Profile']['firstname']." ".$profile['Profile']['lastname'];
    if($this->Session->read("Auth.User.role") == 'admin'){
        $name = $name." (".$profile['User']['username'].")";
    }
	$age = $profile['Profile']['age'];
	$email = $profile['Profile']['email'];
	$phone = ($profile['Profile']['phone'])?$profile['Profile']['phone']:'-';
    $gender = ($profile['Profile']['gender'])?'γυναίκα':'άνδρας';
    if (empty($profile['Profile']['avatar'])) {
        $picture = ($profile['Profile']['gender'])?'female.jpg':'male.jpg';
    }
    else {
        $picture = 'uploads/profiles/'.$profileid.'/'.$profile['Profile']['avatar'];
    }
	$smoker = ($profile['Profile']['smoker'])?'ναι':'όχι';
	$pet = ($profile['Profile']['pet'])?'ναι':'όχι';
	$couple = ($profile['Profile']['couple'])?'ναι':'όχι';
	$child = ($profile['Profile']['child'])?'ναι':'όχι';
	$weAre = $profile['Profile']['we_are'];
	$matesWanted = $profile['Profile']['max_roommates'];
	$name = Sanitize::html($name, array('remove' => true));
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
    // default image
	$defaultThumb = $picture;
    if(isset($images[0])){
        $imageThumbLocation = 'uploads/profiles/'.$profileid.'/thumb_'.$imageLocation;
        $profilePic = $this->Html->image($imageThumbLocation, array('alt' => $name));
    }else{ // if there is no image, put a placeholder
        $profilePic = $this->Html->image($defaultThumb, array(
            'alt' => 'προσθήκη εικόνας προφίλ'));
    }
    // House preferences
	$prefFurnished = $profile['Preference']['pref_furnitured'];
	$prefAccessibility = $profile['Preference']['pref_disability_facilities'];
    $prefHousePhoto = $profile['Preference']['pref_has_photo'];
	$furnished = getPrefValue($prefFurnished, $ynioptions);
	$accessibility = ($prefAccessibility)?$ynioptions[1]:$ynioptions[2];
	$price_max = $profile['Preference']['price_max'];
	$area_min = $profile['Preference']['area_min'];
	$area_max = $profile['Preference']['area_max'];
    // check if the owner of this profile has
    // any saved house preferences
    if ($prefFurnished == 2  && $prefAccessibility == 0 &&
        $prefHousePhoto == 0 && $price_max == '' &&
        $area_max == ''      && $area_min == '')
    {
        $has_house_prefs = false;
    } else {
        $has_house_prefs = true;
    }
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
        $houseTypeArea = $houseType.', '.$houseArea.' τ.μ.';
        $houseLink = $this->Html->link($houseTypeArea,
            array('controller' => 'houses', 'action' => 'view', $houseId));
        if(isset($image)){
            $houseThumb = $this->Html->image($image,
                array('alt' => $houseTypeArea));
            $houseThumbLink = $this->Html->link($houseThumb,
                array('controller' => 'houses', 'action' => 'view', $houseId),
                array('escape' => false));
        }
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
    <div id='profileCont'>
        <div class='profilePic'>
            <?php
                echo $profilePic;
            ?>
        </div>
        <div class="imageactions">
            <?php
                if (! empty($profile['Profile']['avatar'])
                    && ($userid == $loggedUser) ) {
                    echo $this->Html->link(__('Διαγραφή', true),
                        array('controller' => 'profiles', 'action' => 'deleteImage', $profileid),
                        array('class' => 'profile_img_delete', 'title' => 'Διαγραφή εικόνας προφίλ'),
                        sprintf(__('Είστε σίγουρος;', true)));
                }
            ?>
        </div>
        <div id='profileBan'>
            <?php
                if($role == 'admin' &&
                    $profile['Profile']['user_id'] != $this->Session->read('Auth.User.id')){
                    if($profile['User']['banned'] == 0){
                        $banContent = $this->Html->image('delete_16.png', array(
                            'alt' => $name, 'class' => 'optionIcon'))
                            .'Κλείδωμα χρήστη';
                        $banClass = 'banButton';
                        $banMsg = "Είστε σίγουρος ότι θέλετε να κλειδώσετε τον λογαριασμό αυτού του χρήστη;";
                        $banCase = 'ban';
                    }else{
                        $banContent = $this->Html->image('accept_16.png', array(
                            'alt' => $name, 'class' => 'optionIcon'))
                            .'Ξεκλείδωμα χρήστη';
                        $banClass = 'unbanButton';
                        $banMsg = "Είστε σίγουρος ότι θέλετε να ξεκλειδώσετε τον λογαριασμό αυτού του χρήστη;";
                        $banCase = 'unban';
                    }
                    $banLink = $this->Html->link($banContent, array(
                        'controller' => 'profiles', 'action' => $banCase, $profile['Profile']['id']),
                        array('class' => $banClass, 'escape' => false), $banMsg);
                    echo $banLink;
                }
            ?>
        </div>
        <?php
            if($profile['Profile']['user_id'] == $this->Session->read('Auth.User.id')){
                $rssContent = $html->image('rss.png', array(
                    'alt' => 'προσωποποιημένο RSS', 'class' => 'optionIcon'))
                    .'Προσωποποιημένο RSS';
                $rssLink = array('controller' => 'houses', 'action' => 'search.rss',
                    '?' => array('token' => $profile['Profile']['token']));
                $editRSS = $html->link($rssContent, $rssLink,
                    array('escape' => false));
                $editRSS = "<div class='profileOptions'>{$editRSS}</div>";
                echo $editRSS;
            }

            if($this->Session->read('Auth.User.id') == $profile['User']['id']){
                $editContent = $html->image('edit.png', array(
                    'alt' => 'επεξεργασία προφίλ', 'class' => 'optionIcon'))
                    .'Επεξεργασία προφίλ';
                $profileLink = array('action' => 'edit',$profile['Profile']['id']);
                $editProfile = $html->link($editContent, $profileLink,
                    array('escape' => false));
                $editProfile = "<div class='profileOptions'>{$editProfile}</div>";
                echo $editProfile;
            }
        ?>
    </div>
</div>
<div id='main-inner'>
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
        <div class='profileTitle profileClear'>
            <?php
                if ($has_house_prefs) {
                    echo "<h2>Προτιμήσεις σπιτιού</h2>";
                }
                else {
                    echo "<h2>Δεν έχουν οριστεί<br/>προτιμήσεις σπιτιού</h2>";
                }
            ?>
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
    <?php
        if(isset($house)){
            $houseTitle = 'Το σπίτι ';
            if($profile['Profile']['user_id'] == $this->Session->read('Auth.User.id')){
                $houseTitle .= 'μου';
            }else{
                $houseTitle .= ($profile['Profile']['gender'])?'της':'του';
            }
    ?>
        <div id='myHouse' class='profileBlock profileClear'>
            <div class='profileTitle'>
	            <h2><?php echo $houseTitle; ?></h2>
            </div>
            <div id='myHouseInfo' class='profileInfo'>
                <?php
                    echo "{$houseLink}<br />{$houseAddress}<br />{$housePrice} €/μήνα<br />{$houseFurnished}";
                ?>
            </div>
            <div id='myHousePic' class='profileInfo housePic'>
                <?php
                    if(isset($houseThumbLink)){
                        echo $houseThumbLink;
                    }
                ?>
            </div>
        </div>
    <?php
        }else{
            if($profile['Profile']['user_id'] == $this->Session->read('Auth.User.id')){
                $houseTitle = '+Προσθήκη σπιτιού';
                $houseLink = $this->Html->link($houseTitle,
                    array('controller' => 'houses', 'action' => 'add'));
            }
    ?>
        <div id='myHouse' class='profileBlock profileClear'>
            <div class='profileTitle'>
	            <h2>
	                <?php
	                    if(isset($houseLink)){
	                        echo $houseLink;
                        }
                    ?>
	            </h2>
            </div>
        </div>
    <?php
        } // isset $house
    ?>
</div>

