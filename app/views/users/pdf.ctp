<style>
    .testpdf{
        font-weight: bold;
    }
</style>
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
    //$municipality = $municipality['Municipality']['name'];
pr( $data );
    $isOffice = !empty($companyName);
    $roleClarification = $isOffice ? 'μεσιτικού γραφείου' : 'ιδιώτη';
?>

<div class='testpdf'>
<p>Υπεβλήθη αίτηση εγγραφής νέου <?php echo $roleClarification ?> στο σύστημα
με τα ακόλουθα στοιχεία. Παρακαλείστε να ελέγξε την ορθότητα των αναγραφόμενων
στοιχείων:</p>

<p>Όνομα: <?php echo $firstname; ?></p>
<p>Επίθετο: <?php echo $lastname; ?></p>
<?php
    if($isOffice) {
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
<p>Δήμος: <?php echo ''/*$municipality;*/ ?></p>
<p>Διεύθυνση: <?php echo $address; ?></p>
<p>Τ.Κ.: <?php echo $postalCode; ?></p>

Υπογράφοντας παρακάτω επιβεβαιώνετε την ορθότητατα των ανωτέρω αναγραφόμενων
στοιχείων και ότι έχετε διαβάσει, πλήρως κατανοήσει και αποδέχεστε τους εν 
λόγω όρους χρήσης και ότι δεσμεύεστε να ενεργείτε σε συμφωνία με αυτούς.
<br /> __________________________
</div>
