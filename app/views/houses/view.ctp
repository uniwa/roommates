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
    
    .housePic{
        float: left;
        width: 128px;
        height: 128px;
        padding: 2px;
    }
    
    #houseEdit{
        margin: 64px 0px 0px 12px;
    }
    
    .houseTitle{
        margin: 0px 0px 16px 18px;
        font-size: 1.2em;
        font-weight: bold;
    }

    .houseClear{
        clear: both;
    }
    
    .houseLine{
        padding: 6px;
        overflow: hidden;
        width: 100%;
    }
    
    .houseProperty{
        float: left;
        text-align: right;
    }

    .houseV{
        width: 130px;
    }
    
    .houseC{
        width: 150px;
    }
       
    .houseValue{
        float: left;
        margin: 0px 0px 0px 8px;
    }
    
    .houseOdd{
        background-color: #eef;
    }
    
    .housePropertiesCol{
        float: left;
        margin: 0px 0px 0px 48px;
    }
    
    .liimage{
        float: left;
        margin: 0px 0px 0px 6px;
    }
    
    #imageList{
        margin: 0px 0px 0px 24px;
        padding: 0px 8px 0px 8px;
    }
    
    #houseInfo{
        margin: 0px 0px 0px 0px;
        padding: 24px 0px 0px 0px;
    }
</style>

<?php
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
    }else if($ownerRole == 'realestate'){
        $realestateid = $house['User']['RealEstate']['id'];
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

    $houseTypeArea = $houseType.', '.$houseArea.' τ.μ.';
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
            $housePic .= "<div class='imageactions'>";
            $housePic .= $this->Html->link(__('Διαγραφή', true),
                array('controller' => 'images', 'action' => 'delete', $default_image_id),
                array('class' => 'thumb_img_delete'), sprintf(__('Είστε σίγουρος;', true)));
            $housePic .= "</div>";
        }
    }else{ // if don't have an image put placeholder
        if($loggedUser == $userid){
            // placeholder with link to add image
            $housePic = $this->Html->link($this->Html->image('addpic.png',
                array('alt' => 'add house image', 'class' => 'img-placeholder')),
                array('controller' => 'images', 'action' =>'add', $houseid),
                array('title' => 'προσθήκη εικόνας σπιτιού', 'escape' => false));
        }else{ // empty placeholder without link to add image
            $housePic = $this->Html->image('addpic.png', array(
                'alt' => 'προσθήκη εικόνας σπιτιού', 'class' => 'img-placeholder'));
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
        if($image['Image']['location'] == $default_image_location){
            continue;
        }
        $imageid = $image['Image']['id'];
        $imageLocation = $image['Image']['location'];
        $imageThumbLocation = 'uploads/houses/'.$houseid.'/thumb_'.$imageLocation;
        $imageMediumLocation = '/img/uploads/houses/'.$houseid.'/medium_'. $imageLocation;
        $imageThumb = $this->Html->image($imageThumbLocation,
            array('alt' => ' '.$houseTypeArea.'['.$i.']'));
        $imageLink = $this->Html->link($imageThumb, $imageMediumLocation,
                array('class' => 'fancyImage', 'rel' => 'group',
                'title' => $houseTypeArea, 'escape' => false));
        $imageLine = "<li class='liimage'>";
        $imageLine .= $imageLink;
        if($loggedUser == $userid){
            $imageLine .= "<div class='imageactions'>";
            $imageLine .= $this->Html->link(__('Διαγραφή', true),
                array('controller' => 'images', 'action' => 'delete', $imageid),
                array('class' => 'thumb_img_delete'), sprintf(__('Είστε σίγουρος;', true)));
            $imageLine .= "</div>";
        }
        $imageLine .= "</li>";
        $imageLines[$i] = $imageLine;
        $i++;
    }
    
    if($loggedUser == $userid){
        //edit house
        $editHouse = $html->link('Επεξεργασία', array('action' => 'edit', $houseid));
        // delete house
        $deleteHouse = $html->link('Διαγραφή', array('action' => 'delete', $houseid),
            null, 'Είστε σίγουρος/η;');
    }
    // owner's profile (not available to real estate)
    if(($loggedUser != $userid) && ($role != 'realestate')){
        if($ownerRole == 'user'){
            $linkAction = "/profiles/view/{$profileid}";
        }else if($ownerRole == 'realestate'){
            $linkAction = "/real_estates/view/{$realestateid}";
        }
        $profileLink =  $this->Html->link('Προφίλ ιδιοκτήτη', $linkAction);
    }

    // allow posts to Facebook only by a 'user' (as in role)
    if($role == 'user'){
        // create the link to post on Facebook
        $furnished = null;
        if($house['House']['furnitured']){
            $furnished = ' Επιπλωμένο, ';
        }else{
            $furnished = ', ';
        }
        // don't show 'available_places' if house does not belong to a 'user' (as in role)
        $occupation_availability = null;
        if($role != 'user'){
            $occupation_availability = '';
        }else{
            $occupation_availability = ', Διαθέσιμες θέσεις ';
            $occupation_availability .= Sanitize::html($house['House']['free_places']);
        }
        $fbUrl = "http://www.facebook.com/dialog/feed";
        $fbUrl .= "?app_id=".$facebook->getAppId();
        $fbUrl .= "&name=".urlencode('Δείτε περισσότερα εδώ...');
        $fbUrl .= "&link={$fb_app_uri}houses/view/{$houseid}";
        $fbUrl .= "&caption=".urlencode('«Συγκατοικώ»');
        $fbUrl .= "&description=".urlencode($houseTypeArea.'Ενοικίο '.$housePrice.' €,'
            .$furnished.'Δήμος '.$houseMunicipality.$occupation_availability);
        $fbUrl .= "&redirect_uri={$fb_app_uri}houses/view/{$houseid}";
        $fbLink = $this->Html->link('Κοινoποίηση στο Facebook', $fbUrl,
            array('title' => 'κοινοποίηση στο facebook'));
        $fbPost = "<div class='facebook-post'>{$fbLink}</div>";
    }

    // House properties
    $houseProperties['address']['label'] = 'Διεύθυνση';
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
    $houseProperties['rent_period']['label'] = 'Περίοδος ενοικίασης';
    $houseProperties['rent_period']['suffix'] = 'μήνες';
    // if the house belongs to real estate, don't display availability info
    if($role != 'realestate'){
        $houseProperties['hosting']['label'] = 'Διαμένουν';
        $houseProperties['hosting']['suffix'] = ($houseHosting > 1)?'άτομα':'άτομο';
        $houseProperties['free_places']['label'] = 'Διαθέσιμες θέσεις';
        $houseProperties['free_places']['suffix'] = "(από {$houseTotalPlaces} συνολικά θέσεις)";
    }
    $houseProperties['solar']['label'] = 'Ηλιακός';
    $houseProperties['furnished']['label'] = 'Επιπλωμένο';
    $houseProperties['aircondition']['label'] = 'Κλιματισμός';
    $houseProperties['garden']['label'] = 'Κήπος';
    $houseProperties['parking']['label'] = 'Θέση στάθμευσης';
    $houseProperties['shared']['label'] = 'Κοινόχρηστα';
    $houseProperties['door']['label'] = 'Πόρτα ασφαλείας';
    $houseProperties['disability']['label'] = 'Προσβάσιμο από ΑΜΕΑ';
    $houseProperties['storage']['label'] = 'Αποθήκη';
    
    $houseProperties['address']['value'] = $houseAddress;
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
    if($role != 'realestate'){
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
/*
            Ορατότητα:                
                    if($this->Session->read('Auth.User.id') == $house['User']['id']) {
                        if($house['House']['visible']) {
                            echo 'Είναι ορατό σε άλλους χρήστες και στις αναζητήσεις.';
                        } else {
                            echo 'Δεν είναι ορατό σε άλλους χρήστες και στις αναζητήσεις.';
                        }
                    }
                            Περιγραφή:
              echo Sanitize::html($house['House']['description'])

          // TODO fix css in order to use this check
            // if ($house['House']['user_id'] !== $this->Session->read('Auth.User.id') ) {
        
         if($house['User']['Profile'] && $this->Session->read('Auth.User.role') != 'realestate'){
            
                echo $this->Html->link($house['User']['Profile']['firstname'].' '.
                    $house['User']['Profile']['lastname'],
                    array('controller' => 'profiles', 'action' => 'view',
                    $house['User']['Profile']['id']));
            
                        
                    echo Sanitize::html($house['User']['Profile']['age'].' ετών, '.
                    ($house['User']['Profile']['gender']?'γυναίκα':'άνδρας'));
                            e-mail:                
                    echo Sanitize::html($house['User']['Profile']['email']);
                            επιθυμητοί συγκάτοικοι:                
                    echo Sanitize::html($house['User']['Profile']['max_roommates'])
                        
         }elseif($house['User']['RealEstate']){         
            
                echo $this->Html->link($house['User']['RealEstate']['company_name'],
                    array('controller' => 'realEstates', 'action' => 'view',
                    $house['User']['RealEstate']['id']));
            
            e-mail:                
                    echo Sanitize::html($house['User']['RealEstate']['email']);
                            τηλέφωνο επικοινωνίας:                
                    echo Sanitize::html($house['User']['RealEstate']['phone']);
φαξ:
                    echo Sanitize::html($house['User']['RealEstate']['fax']);*/
?>

<div id='leftbar'>
    <div class='housePic liimage'>
        <?php
            echo $housePic;
        ?>
    </div>
    <div id='houseEdit'>
        <?php
            if($this->Session->read('Auth.User.id') == $userid){
                echo $editHouse.'<br />';
                echo $deleteHouse.'<br />';
            }
            if(($loggedUser != $userid) && ($role != 'realestate')){
                echo $profileLink.'<br />';
            }
            echo $fbPost.'<br />';
            
        ?>
    </div>
</div>
<div id='main-inner'>
    <div id='imageList' class='houseClear'>
        <ul>
            <?php
                for($i = 1; $i <= count($imageLines); $i++){
                    echo $imageLines[$i];
                }
                if(isset($placeholders)) echo $placeholders;
            ?>
        </ul>
    </div>
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
                $propertyLine .= "<li class='houseClear houseLine {$lineClass}'>\n";
                $lineClass = ($checkbox)?'houseC':'houseV';
                $propertyLine .= "<div class='houseProperty {$lineClass}'>\n";
                $property = "{$hp['label']} : ";
                $propertyLine .= $property;
                $propertyLine .= "</div>\n<div class='houseValue'>\n";
                if($checkbox){
                    $value = ($hp['check'])?'ναι':'όχι';
                }else{
                    if(isset($hp['value'])){
                        if(isset($hp['value'])){
                            $value = $hp['value'];
                            if(isset($hp['suffix'])){
                                $value .= " {$hp['suffix']}";
                            }
                        }else{
                            $value = '-';
                        }
                    }
                }
                $propertyLine .= $value;
                $propertyLine .= "</div>\n</li>\n";
                if($checkbox){
                    $propertiesChecks .= $propertyLine;
                }else{
                    $propertiesValues .= $propertyLine;
                }
            } //foreach $houseProperties
        ?>
        <ul class='housePropertiesCol'>
            <?php
                echo $propertiesValues;
            ?>
        </ul>
        <ul class='housePropertiesCol'>
            <?php
                echo $propertiesChecks;
            ?>
        </ul>
    </div>
</div>

