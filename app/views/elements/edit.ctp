<?php
    echo $this->Html->script('http://maps.google.com/maps/api/js?sensor=false');
    echo $this->Html->script(array('jquery', 'gmap3.min', 'jquery.editgmap'));
    $dateOptions = array('label' => 'Διαθέσιμο από', 'separator' => '',
                         'dateFormat' => 'DMY', 'minYear' => date('Y'),
                         'maxYear' => date('Y') + 5, 'orderYear' => 'asc');
    $role = $this->Session->read('Auth.User.role');
    if(isset($house)){
        $houseid = $house['House']['id'];
        $houseType = $house['HouseType']['type'];
        $houseArea = $house['House']['area'];
        $houseTypeArea = $houseType.', '.$houseArea.' τ.μ.';
        $imageThumb = $this->Html->image($imageThumbLocation, array('alt' => $houseTypeArea));
    }else{
        $imageThumb = $this->Html->image('home.png', array('alt' => 'προσθήκη σπιτιού'));
//        $dateOptions['empty'] = '---';
    }
?>
<div class='addEditHouseView'>
<div id='leftbar' class='leftGeneral'>
    <div class='housePic liimage'>
        <?php
            echo $imageThumb;
        ?>
    </div>
</div>
<div id='main-inner' class='mainGeneral'>
    <div class='edit-title'>
        <h2>Στοιχεία σπιτιού</h2>
    </div>
    <?php
        echo $form->create('House', array('action' => $editOrigin));
        echo $form->input('house_type_id', array('label' => 'Τύπος κατοικίας', 'empty' => 'Επιλέξτε...'));
        echo $form->input('municipality_id', array('label' => 'Δήμος', 'empty' => 'Επιλέξτε...'));
        echo $form->input('address', array('label' => 'Διεύθυνση','type' => 'textarea' ,"rows" => "2"));
        echo $form->input('postal_code', array('label' => 'Τ.Κ.', 'class' => 'short'));

        // map location mainly depends on [country], [municipality], [address]
        // and [postalCode] form-fields
        echo "<div id='mapDiv'>";
        echo $this->Html->link('Ενημέρωση χάρτη από πεδία', '', array(
            'id' => 'updateMap', 'class' => 'mapMarker',
            'title' =>
                'Απόπειρα προσδιορισμού της θέσης του σπιτιού βάσει των πεδίων διεύθυνσης.'));
        echo $this->Html->link('Επαναφορά χάρτη','', array(
            'id' => 'eraseLatLng', 'class' => 'mapMarker',
            'title' =>
                'Αφαίρεση της οποιαδήποτε πληροφορίας σχετικά με τη θέση του σπιτιού.'));
        echo "<div class='map' id='editMap'></div></div>";

        echo $form->input('latitude', array('type' => 'hidden'));
        echo $form->input('longitude', array('type' => 'hidden'));

        echo $form->input('area', array('label' => 'Εμβαδόν', 'after' => 'τ.μ.', 'class' => 'short'));
        echo $form->input('floor_id', array('label' => 'Όροφος', 'empty' => 'Επιλέξτε...'));
        echo $form->input('bedroom_num', array('label' => 'Αριθμός δωματίων', 'class' => 'short'));
        echo $form->input('bathroom_num', array('label' => 'Αριθμός μπάνιων', 'class' => 'short'));
        echo $form->input('price', array('label' => 'Ενοίκιο','after' => '€', 'class' => 'short'));
        echo $form->input('availability_date', $dateOptions);
        echo $form->input('construction_year', array('label' => 'Έτος κατασκευής', 'type' => 'select',
            'options' => $available_constr_years, 'empty' => 'Άγνωστο'));
        echo $form->input('heating_type_id', array('label' => 'Είδος θέρμανσης', 'empty' => 'Επιλέξτε...'));
        echo $form->input('solar_heater', array('label' => 'Ηλιακός θερμοσίφωνας'));
        echo $form->input('furnitured', array('label' => 'Επιπλωμένο'));
        echo $form->input('aircondition', array('label' => 'Κλιματισμός'));
        echo $form->input('garden', array('label' => 'Κήπος'));
        echo $form->input('parking', array('label' => 'Θέση πάρκινγκ'));
        echo $form->input('shared_pay', array('label' => 'Κοινόχρηστα'));
        echo $form->input('security_doors', array('label' => 'Πόρτες ασφαλείας'));
        echo $form->input('disability_facilities', array('label' => 'Προσβάσιμο από ΑΜΕΑ'));
        echo $form->input('storeroom', array('label' => 'Αποθήκη'));
        $rentPeriodLabel = ($role == 'realestate')?'Περίοδος ενοικίασης ':'Περίοδος συγκατοίκησης ';
        echo $form->input('rent_period', array('label' => $rentPeriodLabel,'after' => ' μήνες', 'class' => 'short'));
        echo $form->input('description', array('label' => 'Περιγραφή','type'=>'textarea'));

        if ($this->Session->read('Auth.User.role') != 'realestate') {
                echo $form->input('currently_hosting', array(
                    'label' => 'Διαμένουν ','type' => 'select',
                    'options' => $places_availability));
                echo $form->input('total_places', array(
                    'label' => 'Μπορούν συνολικά να συγκατοικήσουν ',
                    'type' => 'select', 'options' => $places_availability_extra, 'default' => 2));
        }

        if ($editOrigin == 'add') {
            echo $this->Form->input('visible', array(
                'label' => 'Να είναι ορατό στους υπόλοιπους χρήστες και στις αναζητήσεις.',
                'checked' => true));
        } else {
            echo $this->Form->input('visible', array(
                'label' => 'Να είναι ορατό στους υπόλοιπους χρήστες και στις αναζητήσεις.'));
        }

        echo $form->input('id', array('type' => 'hidden'));
        echo $form->end('Αποθήκευση');
    ?>
</div>
</div>

