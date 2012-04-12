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
ΣΗΜΑΝΤΙΚΟ: Η εκτυπώσιμη μορφή (pdf) της παρούσας αίτησης δεν κατέστη δυνατό να παραχθεί και, συνεπώς, δεν εστάλη στον αιτούντα.
Για το λόγο αυτό, επικοινωνήστε μαζί του το συντομότερο δυνατό ώστε τον ενημερώσετε σχετικά με το πώς να κινηθεί.

<?php } ?>

Υπεβλήθη αίτηση εγγραφής νέου <?php echo $roleClarification ?> στο σύστημα με τα ακόλουθα στοιχεία:
Όνομα: <?php echo $firstname; ?>

Επίθετο: <?php echo $lastname; ?>
<?php
    if($type == 'realestate') {
        echo <<<EOT

Επωνυμία εταιρίας: {$companyName}
EOT;
    }
?>

ΑΦΜ: <?php echo $vatNumber; ?>

ΔΟΥ: <?php echo $doy; ?>

Email: <?php echo $email; ?>

Τηλέφωνο επικοινωνίας: <?php echo $phone; ?>

Φαξ: <?php echo $fax; ?>

Δήμος: <?php echo $municipalityName; ?>

Διεύθυνση: <?php echo $address; ?>

Τ.Κ.: <?php echo $postalCode; ?>


Τα στοιχεία έχουν καταχωρηθεί με όνομα λογαριασμού: <?php echo $username; ?>

Εκκρεμεί η ενεργοποίηση του λογαριασμού.


