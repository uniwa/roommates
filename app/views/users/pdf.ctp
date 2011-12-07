<!doctype html>
<html>

<head>
<?php echo $this->Html->charset('utf-8'); ?>

<style>
    h2{
        text-align: center;
        margin: 0 0 17px 0;
    }
    .testpdf{
        font-weight: bold;
    }

    #footer{
        text-align: center;
    }
</style>
</head>

<body>
<?php $username = $data['User']['username'];
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
    $municipality = $data['RealEstate']['Municipality']['name'];
    $isOffice = !empty($companyName);
    $roleClarification = $isOffice ? 'μεσιτικού γραφείου' : 'ιδιώτη';
?>

<div class='testpdf'>
<h2>Αίτηση εγγραφής Παρόχου Χώρων Στέγασης</h2>
<p>Η παρούσα αίτηση χρησιμοποιείται </p>
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
<p>Δήμος: <?php echo $municipality; ?></p>
<p>Διεύθυνση: <?php echo $address; ?></p>
<p>Τ.Κ.: <?php echo $postalCode; ?></p>

</p>Υπογράφοντας την παρούσα αίτηση, επιβεβαιώνετε την ορθότητατα των ανωτέρω
αναγραφόμενων στοιχείων καθώς και την από μέρου σας ανάγνωση, πλήρη κατανόηση,
αποδοχή των εν λόγω όρων χρήσης και τη δέσμευσή σας να ενεργείτε στο νομικό
και ηθικό πλαίσιο το οποίο αυτοί ορίζουν.<p>
<br /> (Ο/Η υπογράφων/ουσα) __________________________
</div>

<hr />
<?php echo $this->element('footer_pdf'); ?>

</body>

</html>
