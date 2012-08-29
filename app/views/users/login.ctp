<div id='loginView'>
    <div id='loginForm' class='mainCenter'>
        <?php
            echo $this->Form->create('User', array('action' => 'login', 'class' => 'loginForm'));
        ?>
        <div class='loginCredential'>
            Συμπληρώστε το όνομα χρήστη και το συνθηματικό σας:
        </div>
        <div class='loginContent'>
            <div class='loginCredential'>
                <?php
                    echo $this->Form->input('username',
                        array('label' => 'Όνομα χρήστη', 'class' => 'loginInputCredential', 'autofocus' => 'autofocus' ));
                ?>
            </div>
            <div class='loginCredential'>
                <?php
                    echo $this->Form->input('password',
                        array('label' => 'Συνθηματικό', 'class' => 'loginInputCredential'));
                ?>
            </div>
            <div class='loginCredential'>
                <?php
                    echo $this->Form->submit('Είσοδος', array('name' => 'login', 'class' => 'button'));
                ?>
            </div>
        </div>
        <div id='loginRegistration'>
            <?php
                echo $this->Html->link('Εγγραφή ιδιώτη',
                    array('controller' => 'users', 'action' => 'registerowner'))
                    .'<br /><br />';
                echo $this->Html->link('Εγγραφή μεσιτικού γραφείου',
                    array('controller' => 'users', 'action' => 'registerrealestate'));
            ?> 
        </div>
        <?php
            echo $this->Form->end();
        ?>

        <div id='help-cont'>
            <div class='help-text'>
                <p>Οι φοιτητές του ΤΕΙ Αθήνας μπορούν να εισέρχονται στο σύστημα με τη χρήση
                των στοιχείων πρόσβασης των δικτυακών υπηρεσιών του Ιδρύματος
                e-mail, wifi, εύδοξος κ.ο.κ.)</p>
            </div>
            <div class='help-text'>
                <p>Οι νεοεισαχθέντες φοιτητές του ΤΕΙ Αθήνας μπορούν να εισέρχονται στο σύστημα με τη χρήση του ΑΜ με τον οποίo έκαναν τη δήλωση του μηχανογραφικού τους στις Πανελλαδικες Εξετάσεις.</p>
                <p>Ο κωδικός που πρέπει να χρησιμοποιήσετε αποτελείται από το πρώτο γράμμα του επωνύμου, του ονόματος, του πατρωνύμου και του μητρωνύμου σας, όπως αυτά έχουν καταχωρηθεί στο μηχανογραφικό.</p>
                <p>Π.χ. για τον Ιωάννη Παπαδόπουλο του Δημητρίου και της Ελένης, ο κωδικός είναι 'ΙΠΔΕ'.
                Αν κάποιο ή κάποια από τα πεδία είναι κενά, παραλείπονται, π.χ. για την Ευθυμία Ψαρρά, του Θεοδώρου (χωρίς μητρώνυμο), ο κωδικός είναι 'ΕΨΘ'.</p>
                <p>Για οποιαδήποτε απορία, μπορείτε να επικοινωνήσετε με το roommates@teiath.gr.</p>
            </div>
        </div>
    </div>
</div>


