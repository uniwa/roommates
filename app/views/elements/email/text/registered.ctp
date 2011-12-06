<?php
    $username = $data['User']['username'];
    $firstname = $data['RealEstate']['firstname'];
    $lastname = $data['RealEstate']['lastname'];
    $companyName = $data['RealEstate']['company_name'];
    $email = $data['RealEstate']['email'];
    $phone = $data['RealEstate']['phone'];
    $fax = $data['RealEstate']['fax'];
    $vatNumber = $data['RealEstate']['afm'];
    $doy = $data['RealEstate']['doy'];
    $address = $data['RealEstate']['address'];
    $postalCode = $data['RealEstate']['postal_code'];
    $municipality = $municipality['Municipality']['name'];

    $isOffice = !empty($companyName);
    $roleClarification = $isOffice ? 'μεσιτικού γραφείου' : 'ιδιώτη';
?>

Υπεβλήθη αίτηση εγγραφής νέου <?php echo $roleClarification ?> στο σύστημα με τα ακόλουθα στοιχεία:
Όνομα: <?php echo $firstname; ?>

Επίθετο: <?php echo $lastname; ?>
<?php
    if($isOffice) {
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

Δήμος: <?php echo $municipality; ?>

Διεύθυνση: <?php echo $address; ?>

Τ.Κ.: <?php echo $postalCode; ?>


Τα στοιχεία έχουν καταχωρηθεί με όνομα λογαριασμού: <?php echo $username; ?>
