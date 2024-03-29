<?php
    echo $this->Html->script('http://maps.google.com/maps/api/js?sensor=false');
    echo $this->Html->script(array( 'jquery', 'gmap3.min', 'jquery.viewgmap'));
    // fancybox: js image gallery
    echo $this->Html->script('jquery.fancybox-1.3.4.pack');
    echo $this->Html->script('jquery.easing-1.3.pack');
    echo $this->Html->script('jquery.mousewheel-3.0.4.pack');
    echo $this->Html->script('jQuery.fileinput');
    echo $this->Html->script('jquery.autogrowtextarea');
    echo $this->Html->script('main');
    echo $this->Html->css('fancybox/jquery.fancybox-1.3.4.css', 'stylesheet', array("media"=>"all" ), false);

    $role = $this->Session->read('Auth.User.role');

    $loggedUser = $this->Session->read('Auth.User.id');
    $houseid = $house['House']['id'];
    $userid = $house['User']['id'];
    $ownerRole = $house['User']['role'];
    if($ownerRole == 'user'){
        $profileid = $house['User']['Profile']['id'];
        $profileName = $house['User']['Profile']['firstname'].' '
            .$house['User']['Profile']['lastname'];
        $profileAge = Sanitize::html($house['User']['Profile']['age']);
        $profileGender = ($house['User']['Profile']['gender'])?'γυναίκα':'άνδρας';
        $profileEmail = Sanitize::html($house['User']['Profile']['email']);
        $profileWanted = Sanitize::html($house['User']['Profile']['max_roommates']);
    }else if($ownerRole == 'realestate'){
        $realestateid = $house['User']['RealEstate']['id'];
        $realestateCompany = $house['User']['RealEstate']['company_name'];
        $realestateEmail = Sanitize::html($house['User']['RealEstate']['email']);
        $realestatePhone = Sanitize::html($house['User']['RealEstate']['phone']);
        $realestateFax = (!empty($house['User']['RealEstate']['fax']))?
            Sanitize::html($house['User']['RealEstate']['fax']):'-';
    }
    $houseAddress = $house['House']['address'];
    $housePostalCode = $house['House']['postal_code'];
    $houseType = $house['HouseType']['type'];
    $houseFloorType = $house['Floor']['type'];
    $housePrice = $house['House']['price'];
    $houseArea = $house['House']['area'];
    $houseFurnished = $house['House']['furnitured'];
    $houseMunicipality = $house['Municipality']['name'];
    $houseBedrooms = $house['House']['bedroom_num'];
    $houseBathrooms = $house['House']['bathroom_num'];
    $houseGarden = $house['House']['garden'];
    $houseParking = $house['House']['parking'];
    $houseShared = $house['House']['shared_pay'];
    $houseSolar = $house['House']['solar_heater'];
    $houseAircondition = $house['House']['aircondition'];
    $houseYear = $house['House']['construction_year'];
    $houseDoor = $house['House']['security_doors'];
    $houseHeating = $house['HeatingType']['type'];
    $houseStorage = $house['House']['storeroom'];
    $houseDisability = $house['House']['disability_facilities'];
    $houseHosting = $house['House']['currently_hosting'];
    $houseRentPeriod = $house['House']['rent_period'];
    $houseFreePlaces = $house['House']['free_places'];
    $houseTotalPlaces = $house['House']['total_places'];
    $houseAvailable = $time->format($format = 'd-m-Y', $house['House']['availability_date']);
    $houseDescription = Sanitize::html($house['House']['description']);
    $houseVisible = $house['House']['visible'];
    $houseVisibility = ($houseVisible == 1)?
        'Το σπίτι είναι ορατό σε άλλους χρήστες και στις αναζητήσεις':
        'Το σπίτι δεν είναι ορατό σε άλλους χρήστες και στις αναζητήσεις';
    $houseTypeArea = $houseType.', '.$houseArea.' τ.μ.';
    $houseLat = $house['House']['latitude'];
    $houseLng = $house['House']['longitude'];
    $empty_slots = 4 - count($images);

    // default image
    if(isset($images[0])){
        $imageThumbLocation = 'uploads/houses/'.$houseid.'/thumb_'.$default_image_location;
        $imageMediumLocation = '/img/uploads/houses/'.$houseid.'/medium_'. $default_image_location;
        $imageThumb = $this->Html->image($imageThumbLocation, array('alt' => $houseTypeArea));

        $housePic = $this->Html->link($imageThumb, $imageMediumLocation,
                array('class' => 'fancyImage', 'rel' => 'group',
                'title' => $houseTypeArea, 'escape' => false));

        if($loggedUser == $userid){
            $imageActions = "<div class='imageactions'>";
            $imageActions .= $this->Html->link('Διαγραφή',
                array('controller' => 'images', 'action' => 'delete', $default_image_id),
                array('class' => 'thumb_img_delete'), sprintf(__('Είστε σίγουρος;', true)));
            $imageActions .= "</div>";
        }
    }else{ // if don't have an image put placeholder
        if($loggedUser == $userid){
            // placeholder with link to add image
            $housePic = $this->Html->link($this->Html->image('addpic.png',
                array('alt' => 'add house image', 'class' => 'img-placeholder')),
                array('controller' => 'images', 'action' =>'add', $houseid),
                array('title' => 'προσθήκη εικόνας σπιτιού', 'escape' => false));
        }else{ // empty placeholder without link to add image
            $housePic = $this->Html->image('home.png', array(
                'alt' => 'προσθήκη εικόνας σπιτιού'));
        }
        $empty_slots -= 1;
    }

    if($loggedUser == $userid){
        $placeholders = '';
        for($i = 0; $i < $empty_slots; $i++){
                // placeholder with link to add image
                $placeholders .= "<li class='liimage'>";
                $placeholders .= $this->Html->link($this->Html->image('addpic.png',
                    array('alt' => 'προσθήκη εικόνας σπιτιού ['.$i.']', 'class' => 'img-placeholder')),
                    array('controller' => 'images', 'action' =>'add', $houseid),
                    array('title' => 'προσθήκη εικόνας σπιτιού', 'escape' => false));
                $placeholders .= "<li>";
        }
    }

    $imageLines = array();
    $i = 1;
    foreach($images as $image){
        if($image['location'] == $default_image_location){
            continue;
        }
        $imageid = $image['id'];
        $imageLocation = $image['location'];
        $imageThumbLocation = 'uploads/houses/'.$houseid.'/thumb_'.$imageLocation;
        $imageMediumLocation = '/img/uploads/houses/'.$houseid.'/medium_'. $imageLocation;
        $imageThumb = $this->Html->image($imageThumbLocation, array(
            'alt' => ' '.$houseTypeArea.'['.$i.']',
            'class' => 'imageListThumb'));
        $imageLink = $this->Html->link($imageThumb, $imageMediumLocation,
                array('class' => 'fancyImage', 'rel' => 'group',
                'title' => $houseTypeArea, 'escape' => false));
        $imageThumbCont = "<div class='imageThumbCont'>{$imageLink}</div>";
        $imageLine = "<li class='liimage'>";
        $imageLine .= $imageThumbCont;
        if($loggedUser == $userid){
            $imageLine .= "<div class='imageactions'>";
            $imageLine .= $this->Html->link(__('Διαγραφή', true),
                array('controller' => 'images', 'action' => 'delete', $imageid),
                array('class' => 'thumb_img_delete'), sprintf(__('Είστε σίγουρος;', true)));
            $imageLine .= ' ';
            $imageLine .= $this->Html->link('Προεπιλεγμένη',
                array('controller' => 'images', 'action' => 'set_default', $imageid),
                array('class' => 'thumb_img_thumb'), null);
            $imageLine .= "</div>";
        }
        $imageLine .= "</li>";
        $imageLines[$i] = $imageLine;
        $i++;
    }
    $numberImages = count($imageLines);

    if($loggedUser == $userid){
        //edit house
        $editContent = $html->image('edit.png', array(
            'alt' => 'Επεξεργασία', 'class' => 'optionIcon')).'Επεξεργασία';
        $editHouse = $html->link($editContent,
            array('action' => 'edit', $houseid), array('escape' => false));
        $editHouse = "<div class='houseOptions'>{$editHouse}</div>";
        // delete house
        $deleteContent = $html->image('delete_16.png', array(
            'alt' => 'Διαγραφή', 'class' => 'optionIcon')).'Διαγραφή';
        $deleteHouse = $html->link($deleteContent, array('action' => 'delete', $houseid),
            array('escape' => false), 'Είστε σίγουρος/η;');
        $deleteHouse = "<div class='houseOptions'>{$deleteHouse}</div>";
    }
    // owner's profile (not available to real estate)
    if(($loggedUser != $userid) && ($role != 'realestate')){
        if($ownerRole == 'user'){
            $profileInfo = "<div id='owner-info'>";
            $profileInfo .= "<div class='profileTitle'>Στοιχεία φοιτητή</div>";
            $profileInfo .= $this->Html->link($profileName, array(
                'controller' => 'profiles', 'action' => 'view',
                $profileid));
            $profileInfo .= '<br />'.$profileAge.' ετών, '.$profileGender;
            $profileInfo .= '<br />email: '.$this->Html->link($profileEmail, 'mailto:'.$profileEmail);
            $profileInfo .= '<br />επιθυμητοί συγκάτοικοι: '.$profileWanted;
            $profileInfo .= "</div>";
        }elseif($ownerRole == 'realestate'){
            $profileInfo = "<div id='owner-info'>";
            $profileInfo .= "<div class='profileTitle'>Στοιχεία ενοικιαστή</div>";
            $profileInfo .= $this->Html->link($realestateCompany,
                array('controller' => 'realEstates', 'action' => 'view',
                $realestateid));
            $profileInfo .= '<br />email: '.$this->Html->link($realestateEmail, 'mailto:'.$realestateEmail);
            $profileInfo .= '<br />τηλέφωνο: '.$realestatePhone;
            $profileInfo .= '<br />φαξ: '.$realestateFax;
            $profileInfo .= "</div>";
        }
    }

    if($ownerRole == 'user'){
        $rentPeriodLabel = 'Περίοδος συγκατοίκησης';
        $occupation_availability = ', Διαθέσιμες θέσεις ';
        $occupation_availability .= Sanitize::html($house['House']['free_places']);
    }else{
        $rentPeriodLabel = 'Περίοδος ενοικίασης';
        $occupation_availability = null;
    }

    if($role == 'user'){
        // allow posts to Facebook only by a 'user' (as in role)
        // create the link to post on Facebook
        $furnished = null;
        if($house['House']['furnitured']){
            $furnished = ' Επιπλωμένο, ';
        }else{
            $furnished = ', ';
        }
        // don't show 'available_places' if house does not belong to a 'user' (as in role)
        $fbUrl = "http://www.facebook.com/dialog/feed";
        $fbUrl .= "?app_id=".$fb_app_id;
        $fbUrl .= "&name=".urlencode('Δείτε περισσότερα εδώ...');
        $fbUrl .= "&link={$fb_app_uri}houses/view/{$houseid}";
        $fbUrl .= "&caption=".urlencode('«Συγκατοικώ»');
        $fbUrl .= "&description=".urlencode($houseTypeArea.'Ενοικίο '.$housePrice.' €,'
            .$furnished.'Δήμος '.$houseMunicipality.$occupation_availability);
        $fbUrl .= "&redirect_uri={$fb_app_uri}houses/view/{$houseid}";
        $fbImage = 'facebook.png';
        $fbDisplay = $this->Html->image($fbImage, array(
            'alt' => 'Κοινoποίηση στο Facebook',
            'class' => 'optionIcon'))
            ." Post";
        $fbLink = $this->Html->link($fbDisplay, $fbUrl,array(
            'title' => 'κοινοποίηση στο facebook', 'escape' => false,
            'target' => 'post_to_facebook'));
        $fbPost = "<div class='houseOptions'>{$fbLink}</div>";
    }

    // House properties
    if (($userid == $this->Session->read('Auth.User.id')) or
        ($ownerRole == 'realestate')) {
        $houseProperties['address']['label'] = 'Διεύθυνση';
        $houseProperties['address']['value'] = $houseAddress;
    }
    $houseProperties['municipality']['label'] = 'Δήμος';
    $houseProperties['postal_code']['label'] = 'Τ.Κ.';
    $houseProperties['type']['label'] = 'Τύπος';
    $houseProperties['area']['label'] = 'Εμβαδόν';
    $houseProperties['area']['suffix'] = 'τ.μ.';
    $houseProperties['bedrooms']['label'] = 'Υπνοδωμάτια';
    $houseProperties['bathrooms']['label'] = 'Μπάνια';
    $houseProperties['floor']['label'] = 'Όροφος';
    $houseProperties['year']['label'] = 'Έτος κατασκευής';
    $houseProperties['heating']['label'] = 'Θέρμανση';
    $houseProperties['price']['label'] = 'Ενοίκιο';
    $houseProperties['price']['suffix'] = '€';
    $houseProperties['available']['label'] = 'Διαθέσιμο από';
    $houseProperties['rent_period']['label'] = $rentPeriodLabel;
    $houseProperties['rent_period']['suffix'] = 'μήνες';
    // if the house belongs to real estate, don't display availability info
    if($ownerRole != 'realestate'){
        $houseProperties['hosting']['label'] = 'Διαμένουν';
        $houseProperties['hosting']['suffix'] = ($houseHosting > 1)?'άτομα':'άτομο';
        $houseProperties['free_places']['label'] = 'Διαθέσιμες θέσεις';
        $houseProperties['free_places']['suffix'] = "(από {$houseTotalPlaces} συνολικά)";
    }
    if(!is_null($house['House']['geo_distance'])){
        $geovalue = number_format($house['House']['geo_distance'], 2).' χλμ.';
    }else{
        $geovalue = 'δεν διατίθεται';
    }

    //house distance
    $houseProperties['geo_distance']['label'] = 'Απόσταση από ΤΕΙ';
    $houseProperties['solar']['label'] = 'Ηλιακός';
    $houseProperties['furnished']['label'] = 'Επιπλωμένο';
    $houseProperties['aircondition']['label'] = 'Κλιματισμός';
    $houseProperties['garden']['label'] = 'Κήπος';
    $houseProperties['parking']['label'] = 'Θέση στάθμευσης';
    $houseProperties['shared']['label'] = 'Κοινόχρηστα';
    $houseProperties['door']['label'] = 'Πόρτα ασφαλείας';
    $houseProperties['disability']['label'] = 'Προσβάσιμο από ΑΜΕΑ';
    $houseProperties['storage']['label'] = 'Αποθήκη';

    $houseProperties['geo_distance']['value'] = $geovalue;
    $houseProperties['municipality']['value'] = $houseMunicipality;
    $houseProperties['postal_code']['value'] = $housePostalCode;
    $houseProperties['type']['value'] = $houseType;
    $houseProperties['area']['value'] = $houseArea;
    $houseProperties['bedrooms']['value'] = $houseBedrooms;
    $houseProperties['bathrooms']['value'] = $houseBathrooms;
    $houseProperties['floor']['value'] = $houseFloorType;
    $houseProperties['year']['value'] = $houseYear;
    $houseProperties['heating']['value'] = $houseHeating;
    $houseProperties['price']['value'] = $housePrice;
    $houseProperties['available']['value'] = $houseAvailable;
    $houseProperties['rent_period']['value'] = $houseRentPeriod;

    // if the house belongs to real estate, don't display availability info
    if($ownerRole != 'realestate'){
        $houseProperties['hosting']['value'] = $houseHosting;
        $houseProperties['free_places']['value'] = $houseFreePlaces;
    }

    $houseProperties['solar']['check'] = $houseSolar;
    $houseProperties['furnished']['check'] = $houseFurnished;
    $houseProperties['aircondition']['check'] = $houseAircondition;
    $houseProperties['garden']['check'] = $houseGarden;
    $houseProperties['parking']['check'] = $houseParking;
    $houseProperties['shared']['check'] = $houseShared;
    $houseProperties['door']['check'] = $houseDoor;
    $houseProperties['disability']['check'] = $houseDisability;
    $houseProperties['storage']['check'] = $houseStorage;

    // coordinates over which the map is to be centered (not necessarily the
    // actual ones)
    $houseLat = $house['House']['latitude'];
    $houseLng = $house['House']['longitude'];

    // determines whether a marker or a cirle should be positioned over the
    // house's location (circle is used for 'user's)
    $displayCircle = null;
    // by default, the map div-tag won't be included
    $showMap = false;

    if( !is_null( $houseLat ) && !is_null( $houseLng ) ) {

        // include map div-tag in html, because coordinates were found
        $showMap = true;

        // obscure exact location of house if it belongs to a 'user' (as in
        // role) and request a circular area to be positioned over the map
        if( $ownerRole == 'user' ) {

            $displayCircle = 1;

            $latDev = rand( -1, 1 );

            $latDev *= 0.001;
            $lngDev = 0.001 - $latDev;
            $houseLat += $latDev;
            $houseLng += $lngDev;
        } else {
            $displayCircle = 0;
        }
    }

    // if either is null, 'null' should be 'printed' (in text) in javascript
    if( is_null( $houseLat ) )  $houseLat = 'null';
    if( is_null( $houseLng ) )  $houseLng = 'null';

    // php does not echo 0 either, so print it as text
    if( $displayCircle == 0 )  $displayCircle = '0';

    // the coordinates and the marker "type" (circle/arrow) are passed as
    // HTML-inline javascipt
    echo <<<EOT
        <script type='text/javascript'>
            var houseLat = $houseLat;
            var houseLng = $houseLng;
            var displayCircle = $displayCircle;
        </script>
EOT;

    $resultClass = 'result-cont';
    if($ownerRole == 'realestate'){
        if($house['User']['RealEstate']['type'] == 'owner'){
            $classCont='contOwner';
            $resultClass .= ' resultOwner';
            $roleClass = 'owner';
            $roleTitle = 'ιδιώτης';
        }else{
            $classCont='contRE';
            $resultClass .= ' resultRE';
            $roleClass = 'realestate';
            $roleTitle = 'μεσιτικό';
        }
    }else{
        $classCont='contStudent';
        $resultClass .= ' resultStudent';
        $roleClass = 'student';
        $roleTitle = 'φοιτητής';
    }
    echo "<div id='houseView' class='{$classCont}'>";
?>
<div id='leftbar' class='leftSearch'>
    <div id='houseCont'>
        <div class='housePic default-image'>
            <?php
                echo $housePic;
                if(isset($imageActions)){
                    echo $imageActions;
                }
            ?>
        </div>
    </div>
    <div id='houseEdit'>
        <?php
            if($this->Session->read('Auth.User.id') == $userid){
                echo $editHouse;
                echo $deleteHouse;
            }
            if($role == 'user'){
                echo $fbPost;
            }
            if(($loggedUser != $userid) && ($role != 'realestate')){
                echo $profileInfo;
            }
        ?>
    </div>
        <?php
            if($showMap) {
                echo <<<EOM
                <div id='houseMap'>
                    <div class='map' id='viewMap'></div>
                </div>
EOM;
            }
        ?>
</div>
<div id='main-inner' class='mainSearch'>
    <?php
        if(($loggedUser == $userid) || ($numberImages > 0)){
    ?>
    <div id='imageList' class='houseClear'>
        <ul>
            <?php
                for($i = 1; $i <= $numberImages; $i++){
                    echo $imageLines[$i];
                }
                if(isset($placeholders)) echo $placeholders;
            ?>
        </ul>
    </div>
    <?php
        } // $numberImages > 0
    ?>
    <div id='houseInfo' class='houseClear'>
        <div class='houseTitle'>
            Στοιχεία σπιτιού
        </div>
        <?php
            $odd = false;
            $propertiesValues = '';
            $propertiesChecks = '';
            foreach($houseProperties as $hp){
                $checkbox = isset($hp['check']);
                $propertyLine = '';
                $odd = !$odd;
                $lineClass = ($odd)?'houseOdd ':' ';
                $lineClass .= ($checkbox)?'houseLineShort':'houseLineLong';
                $propertyLine .= "<li class='houseClear houseLine {$lineClass}'>\n";
                $lineClass = ($checkbox)?'houseC':'houseV';
                $propertyLine .= "<div class='houseProperty {$lineClass}'>\n";
                $property = "{$hp['label']} : ";
                $propertyLine .= $property;
                $lineClass = 'houseValue';
                if($checkbox){
                    $lineClass .= ' houseCheck';
                    if($hp['check']){
                        $value = 'ναι';
                        $lineClass .= ' houseCheckTrue';
                    }else{
                        $value = 'όχι';
                        $lineClass .= ' houseCheckFalse';
                    }
                }else{
                    $value = '-';
                    if(isset($hp['value'])){
                        if($hp['value'] != ''){
                            $value = $hp['value'];
                            if(isset($hp['suffix'])){
                                $value .= " {$hp['suffix']}";
                            }
                        }
                    }
                }
                $propertyLine .= "</div>\n<div class='{$lineClass}'>\n";
                $propertyLine .= $value;
                $propertyLine .= "</div>\n</li>\n";
                if($checkbox){
                    $propertiesChecks .= $propertyLine;
                }else{
                    $propertiesValues .= $propertyLine;
                }
            } //foreach $houseProperties
        ?>
        <div id='housePropertiesCont'>
            <ul class='housePropertiesCol'>
                <?php
                    echo $propertiesValues;
                ?>
                <?php if ($loggedUser == $userid) { ?>
                <li class='houseClear houseLine houseLineLong'>
                    <?php
                        echo '<br />'.$houseVisibility;
                    ?>
                </li>
                <?php } ?>
                <li class='houseClear houseLine houseLineLong'>
                    <?php
                        if($houseDescription != ''){
                            echo 'Περιγραφή: '.$houseDescription;
                        }
                    ?>
                </li>
            </ul>
            <ul class='housePropertiesCol'>
                <?php
                    echo $propertiesChecks;
                ?>
            </ul>
        </div>
    </div>
</div>
</div>
