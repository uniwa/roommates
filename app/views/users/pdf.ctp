<!doctype html>
<html>

    <head>
        <?php echo $this->Html->charset('utf-8'); ?>

        <style>
            h2{
                text-align: center;
                margin: 0 0 17px 0;
            }

            div.text-area{
                margin: 43px 43px 0 43px;
            }

            ul{
                list-style-type: none;
            }

            li{
                padding: 3px;
            }

            p.justified{
                text-align: justify;
            }

            hr {
                margin: 0 0 19px 0;
            }

            body{
                font-family: Ubuntu, Verdana, Arial, "Sans-serif";
            }

            #footer{
                text-align: center;
            }

            #undersigned{
                text-align: center;
                margin: 0 130px 0 500px;
            }

            #undersigned .desc{
                margin: 0 0 60px 0;
                vertical-align: super;
                color: darkgray;
            }

            .liner{
                border-bottom: 1px black solid;
                width: 100%;
                margin: 12px 0;
            }
        </style>
    </head>

    <body>
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

    <div class='text-area'>
        <h2>Αίτηση εγγραφής Παρόχου Χώρων Στέγασης</h2>
        <p class='justified'>
        Υπεβλήθη η αίτησή σας για την εγγραφή νέου
        <?php echo $roleClarification; ?> στο σύστημα με τα ακόλουθα στοιχεία.

        <p>Το συνημμένο αρχείο αποτελεί την αίτηση, η οποία πρέπει να ελεγχθεί, 
        να υπογραφεί και να παραδοθεί στο Τμήμα Σπουδαστικής Μέριμνας του ΤΕΙ Αθήνας. 
        Για τα μεσιτικά γραφεία, απαιτείται σφραγίδα του γραφείου πάνω στην αίτηση.</p>

        <p>Οι ιδιώτες θα πρέπει επιπλέον να αποστέλουν αντίγραφο των δύο όψεων του δελτίου αστυνομικής ταυτότητας μαζί με την αίτηση.</p>

        <p>Η υποβολή των εγγράφων γίνεται μέσω τηλεομοιότυπου (fax) 
        στον αριθμό <?php echo $authority_fax; ?> ή απευθείας στα γραφεία της Σπουδαστικής Μέριμνας.</p>

        <ul>
            <li>Όνομα: <b><?php echo $firstname; ?></b></li>
            <li>Επίθετο: <b><?php echo $lastname; ?></b></li>
            <?php
                if($type == 'realestate') {
                    echo <<<EOT
                <li>Επωνυμία εταιρίας: <b>{$companyName}</b></li>

EOT;
                }
            ?>

            <li class="">ΑΦΜ: <b><?php echo $vatNumber; ?></b></li>
            <li>ΔΟΥ: <b><?php echo $doy; ?></b></li>
            <li>Email: <b><?php echo $email; ?></b></li>
            <li>Τηλέφωνο<br />επικοινωνίας: <b><?php echo $phone; ?></b></li>
            <li>Φαξ: <b><?php echo $fax; ?></b></li>
            <li>Δήμος: <b><?php echo $municipalityName; ?></b></li>
            <li>Διεύθυνση: <b><?php echo $address; ?></b></li>
            <li>Τ.Κ.: <b><?php echo $postalCode; ?></b></li>
            <li>Τα στοιχεία έχουν καταχωρηθεί με όνομα λογαριασμού:
                <b><?php echo $username; ?></b>
            </li>
        </ul>

        <p class="justified">Υπογράφοντας την παρούσα αίτηση, επιβεβαιώνετε την
        ορθότητα των ανωτέρω αναγραφόμενων στοιχείων καθώς και την από μέρους
        σας ανάγνωση, πλήρη κατανόηση και αποδοχή των όρων χρήσης και τη 
        δέσμευσή σας να ενεργείτε εντός του νομικού και ηθικού πλαισίου το οποίο
        αυτοί ορίζουν.</p>
        <div id="undersigned">
            <div class="desc">(Ο/Η υπογράφων/ουσα)</div>
            <div class="liner"></div>
        </div>

    </div>

    <div class="liner"></div>
    <?php echo $this->element('footer_pdf'); ?>

    </body>

</html>
