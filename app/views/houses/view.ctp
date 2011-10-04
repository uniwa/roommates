<h1>Ενοικιάζεται</h1><div class="profile houseProfile">
    <div id="actions">
 <?php echo $html->link('Επεξεργασία', array('action' => 'edit', $house['House']['id']));?>
 <?php echo $html->link('Διαγραφή', array('action' => 'delete', $house['House']['id']), null, 'Είστε σίγουρος/η;')?>
</div>
        <div class="photo">
            <img src="<?php echo $this->webroot; ?>img/homedefault.png" alt="Home Picture" class="avatar"/>

        </div>

<div class="info-block">
    <p><span class="bold">Διεύθυνση:</span> <?php echo $house['House']['address']?></p>
    <p><span class="bold">Τ.Κ.:</span> <?php echo $house['House']['postal_code']?></p>
    <p><span class="bold">Τύπος:</span> <?php echo $house['HouseType']['type']?></p>
    <p><span class="bold">Τετραγωνικά:</span> <?php echo $house['House']['area']?></p>
    <p><span class="bold">Υπνοδωμάτια:</span> <?php echo $house['House']['bedroom_num']?></p>
    <p><span class="bold">Μπάνια:</span> <?php echo $house['House']['bathroom_num']?></p>
    <p><span class="bold">Όροφος:</span> <?php echo $house['Floor']['type']?></p>
    <p><span class="bold">Έτος κατασκευής:</span> <?php echo $house['House']['construction_year']?></p>
    <p><span class="bold">Θέρμανση:</span> <?php echo $house['HeatingType']['type']?></p>
    <p><span class="bold">Τιμή:</span> <?php echo $house['House']['price']?>€</p>
    <p><span class="bold">Διαθέσιμο από:</span> <?php echo $house['House']['availability_date']?></p>
    <p><span class="bold">Περίοδος ενοικίασης:</span> <?php echo $house['House']['rent_period']?></p>
</div>
<div class="info-block">
    <!-- boolean fields -->
    <p>
        <span class="bold">Ηλιακός:</span>
        <span class="checkbox cb<?php echo $house['House']['solar_heater']?>">&nbsp;</span>
    </p>
    <p>
        <span class="bold">Επιπλωμένο:</span>
        <span class="checkbox cb<?php echo $house['House']['furnitured']?>">&nbsp;</span>
    </p>
    <p>
        <span class="bold">Κλιματισμός:</span>
        <span class="checkbox cb<?php echo $house['House']['aircondition']?>">&nbsp;</span>
    </p>
    <p>
        <span class="bold">Κήπος:</span>
        <span class="checkbox cb<?php echo $house['House']['garden']?>">&nbsp;</span>
    </p>
    <p>
        <span class="bold">Parking:</span>
        <span class="checkbox cb<?php echo $house['House']['parking']?>">&nbsp;</span>
    </p>
    <p>
        <span class="bold">Κοινόχρηστα:</span>
        <span class="checkbox cb<?php echo $house['House']['shared_pay']?>">&nbsp;</span>
    </p>
    <p>
        <span class="bold">Πόρτα ασφαλείας:</span>
        <span class="checkbox cb<?php echo $house['House']['security_doors']?>">&nbsp;</span>
    </p>
    <p>
        <span class="bold">Προσβάσιμο από ΑΜΕΑ:</span>
        <span class="checkbox cb<?php echo $house['House']['disability_facilities']?>">&nbsp;</span>
    </p>
    <p>
        <span class="bold">Αποθήκη:</span>
        <span class="checkbox cb<?php echo $house['House']['storeroom']?>">&nbsp;</span>
    </p>
</div>
<div class="info-block">
    <!-- free text description -->
    <p><span class="bold">Περιγραφή:</span> <?php echo Sanitize::html($house['House']['description'])?></p>
</div>




</div>



