<?php
    $username = $data['User']['username'];
    $firstname = $data['RealEstate']['firstname'];
    $lastname = $data['RealEstate']['lastname'];
    $type = $data['RealEstate']['type'];
    $companyName = $data['RealEstate']['company_name'];
    $email = $data['RealEstate']['email'];
    $phone = $data['RealEstate']['phone'];
    $fax = $data['RealEstate']['fax'];
    $vatNumber = $data['RealEstate']['afm'];
    $doy = $data['RealEstate']['doy'];
    $address = $data['RealEstate']['address'];
    $postalCode = $data['RealEstate']['postal_code'];
    $municipalityName = '';
    if( isset($municipality['Municipality']['name']) ) {
        $municipalityName = $municipality['Municipality']['name'];
    }

    $roleClarification = $type == 'owner' ? 'ιδιώτη' : 'μεσιτικού γραφείου';
?>
<?php if( !$pdf_success ) { ?>
<p>ΣΗΜΑΝΤΙΚΟ: Η εκτυπώσιμη μορφή (pdf) της παρούσας αίτησης δεν κατέστη δυνατό να παραχθεί και, συνεπώς, δεν εστάλη στον αιτούντα. Για το λόγο αυτό, επικοινωνήστε μαζί του το συντομότερο δυνατό ώστε να τον ενημερώσετε σχετικά με το πώς να κινηθεί.</p>

<?php } ?>

<p>Υπεβλήθη αίτηση εγγραφής νέου <?php echo $roleClarification ?> στο σύστημα με τα ακόλουθα στοιχεία:</p>
<p>Όνομα: <?php echo $firstname; ?></p>
<p>Επίθετο: <?php echo $lastname; ?></p>
<?php
    if($type == 'realestate') {
        echo <<<EOT
<p>Επωνυμία εταιρίας: {$companyName}</p>

EOT;
    }
?>
<p>ΑΦΜ: <?php echo $vatNumber; ?></p>
<p>ΔΟΥ: <?php echo $doy; ?></p>
<p>Email: <?php echo $email; ?></p>
<p>Τηλέφωνο επικοινωνίας: <?php echo $phone; ?></p>
<p>Φαξ: <?php echo $fax; ?></p>
<p>Δήμος: <?php echo $municipalityName; ?></p>
<p>Διεύθυνση: <?php echo $address; ?></p>
<p>Τ.Κ.: <?php echo $postalCode; ?></p>

<p>Τα στοιχεία έχουν καταχωρηθεί με όνομα λογαριασμού: <?php echo $username; ?></p>
<p>Εκκρεμεί η ενεργοποίηση του λογαριασμού.</p>
