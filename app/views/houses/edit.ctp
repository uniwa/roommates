<style>
    #leftbar{
        float: left;
        margin: 0px 0px 0px 32px;
        padding: 32px;
    }
    
    #main-inner{
        float: left;
        border-left: 1px dotted #333;
        margin: 10px 0px 20px 32px;
        padding: 0px 0px 24px 64px;
    }
    
    .housePic{
        float: left;
        padding: 2px;
        width: 180px;
        height: 100px;
        overflow: hidden;
    }
    
    .edit-title{
        margin: 12px 0px 24px 0px;
        font-size: 1.2em;
        font-weight: bold;
    }

    select{
            font-size: 12px;
    }

    .input {
        padding: 3px 0;
    }
    
    #HouseAddForm label, #HouseEditForm label {
        font-size: 12px;
        font-weight: bold;
        color: #21759B;
        line-height: 19px;
        min-width: 120px;
        display: inline-block;
        padding-left: 10px;
    }

    #HouseAddForm input[type=text], #HouseAddForm textarea, #HouseEditForm input[type=text], #HouseEditForm textarea {
        padding: 4px;
        border: solid 1px #C6C6C6;
        border-bottom: solid 1px #E3E3E3;
        color: #333;
        -moz-box-shadow: inset 0 4px 6px #ccc;
        -webkit-box-shadow: inset 0 4px 6px #ccc;
        box-shadow: inset 0 4px 6px #ccc;

    }

    #HouseAddForm input[type=text], #HouseEditForm input[type=text] {
        width: 50px;
        text-align: right;
    }
    
    #updateMap{
        clear: both;
        margin: 12px 0px 8px 10px;
    }
    
    .map{
        padding: 0px 0px 0px 10px;
        width: 450px;
        height: 250px;
    }
</style>

<?php
    echo $this->Html->script('http://maps.google.com/maps/api/js?sensor=false');
    echo $this->Html->script(array('jquery', 'gmap3.min', 'jquery.editgmap'));


    $houseid = $house['House']['id'];
    $houseType = $house['HouseType']['type'];
    $houseArea = $house['House']['area'];
    $houseTypeArea = $houseType.', '.$houseArea.' τ.μ.';
    $imageThumb = $this->Html->image($imageThumbLocation, array('alt' => $houseTypeArea));
?>

<div id='leftbar'>
    <div class='housePic liimage'>
        <?php
            echo $imageThumb;
        ?>
    </div>
</div>
<div id='main-inner'>
    <div class='edit-title'>
        <h2>Στοιχεία σπιτιού</h2>
    </div>

    <?php
        echo $form->create('House', array('action' => 'edit'));
        echo $form->input('house_type_id', array('label' => 'Τύπος κατοικίας', 'empty' => 'Επιλέξτε...'));
        echo $form->input('municipality_id', array('label' => 'Δήμος', 'empty' => 'Επιλέξτε...'));
        echo $form->input('address', array('label' => 'Διεύθυνση','type' => 'textarea' ,"rows" => "2"));
        echo $form->input('postal_code', array('label' => 'Τ.Κ.'));

        // map location mainly depends on [country], [municipality], [address]
        // and [postalCode] form-fields
        echo $this->Html->link('Ενημέρωση χάρτη από πεδία', '', array('id' => 'updateMap'));
        echo '<div class="map" id="editMap"></div>';

        echo $form->input( 'latitude', array( 'type' => 'hidden' ) );
        echo $form->input( 'longitude', array( 'type' => 'hidden' ) );

        echo $form->input('area', array('label' => 'Εμβαδόν','after' => 'τ.μ.'));
        echo $form->input('floor_id', array('label' => 'Όροφος', 'empty' => 'Επιλέξτε...'));
        echo $form->input('bedroom_num', array('label' => 'Αριθμός δωματίων'));
        echo $form->input('bathroom_num', array('label' => 'Αριθμός μπάνιων'));
        echo $form->input('price', array('label' => 'Ενοίκιο','after' => '€'));
        echo $form->input('availability_date', array('label' => 'Διαθέσιμο από', 'empty' => '---',
            'dateFormat' => 'DMY', 'minYear' => date('Y'), 'maxYear' => date('Y') + 5));
        echo $form->input('construction_year', array('label' => 'Έτος κατασκευής', 'type' => 'select',
            'options' => $available_constr_years, 'empty' => 'Άγνωστο'));
        echo $form->input('solar_heater', array('label' => 'Ηλιακός θερμοσίφωνας'));
        echo $form->input('furnitured', array('label' => 'Επιπλωμένο'));
        echo $form->input('heating_type_id', array('label' => 'Είδος θέρμανσης', 'empty' => 'Επιλέξτε...'));
        echo $form->input('aircondition', array('label' => 'Κλιματισμός'));
        echo $form->input('garden', array('label' => 'Κήπος'));
        echo $form->input('parking', array('label' => 'Θέση πάρκινγκ'));
        echo $form->input('shared_pay', array('label' => 'Κοινόχρηστα'));
        echo $form->input('security_doors', array('label' => 'Πόρτες ασφαλείας'));
        echo $form->input('disability_facilities', array('label' => 'Προσβάσιμο από ΑΜΕΑ'));
        echo $form->input('storeroom', array('label' => 'Αποθήκη'));
        echo $form->input('rent_period', array('label' => 'Περίοδος ενοικίασης ','after' => ' μήνες'));
        echo $form->input('description', array('label' => 'Περιγραφή','type'=>'textarea'));

        if ($this->Session->read('Auth.User.role') != 'realestate') {
                echo $form->input('currently_hosting', array(
                    'label' => 'Διαμένουν ','type' => 'select',
                    'options' => $places_availability));
                echo $form->input('total_places', array(
                    'label' => 'Μπορούν συνολικά να συγκατοικήσουν ',
                    'type' => 'select', 'options' => $places_availability));
        }
        echo $this->Form->input('visible', array(
            'label' => 'Να είναι ορατό στους υπόλοιπους χρήστες και στις αναζητήσεις.'));
        echo $form->input('id', array('type' => 'hidden'));
        echo $form->end('Αποθήκευση');
    ?>
</div>
