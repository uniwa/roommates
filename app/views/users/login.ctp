<div id='loginView'>
    <div id='startPage'>
        <div id='homeText'>
            <h2>Καλώς ήρθατε στην ιστοσελίδα της Υπηρεσίας Εύρεσης Συγκατοίκων.</h2>
            <?php
                echo $this->Html->image('loginheader.jpg', array('alt' => 'about banner', 'class' => 'loginbanner'));
            ?>
            Η υπηρεσία δίνει τη δυνατότητα στους 
            <?php
                echo $this->Html->link('φοιτητές',
                    array('controller' => 'users', 'action' => 'aboutstudents'));
            ?>
             του ΤΕΙ Αθήνας να αναζητούν σπίτι προς ενοικίαση ή και συγκάτοικο, ενώ σε ενδαφερόμενους 
            <?php
                echo $this->Html->link('ιδιοκτήτες',
                    array('controller' => 'users', 'action' => 'aboutowners'));
            ?>
              σπιτιών και 
            <?php
                echo $this->Html->link('μεσιτικά γραφεία',
                    array('controller' => 'users', 'action' => 'aboutowners'));
            ?>
               να αναρτήσουν αγγελίες ενοικίασης των σπιτιών τους με σκοπό να προσελκύσουν τους φοιτητές του ΤΕΙ Αθήνας.
            <br /><br />Η υπηρεσία παρέχει λειτουργίες καταχώρησης αγγελίας εύρεσης συγκατοίκου, αυτόματης πολυκαναλικής ενημέρωσης μέσω email και RSS feed, καταχώρησης ακινήτου από πιστοποιημένους ιδιοκτήτες και μεσίτες, αναζήτησης ακινήτου και συγκατοίκου, ανάρτησης όλων των αγγελιών ακινήτων σε ειδική σελίδα στο Facebook.
            <br /><br />Η υπηρεσία παρέχεται από το Τμήμα Σπουδών και Σπουδαστικής Μέριμνας.
        </div>
        <div id='loginForm'>
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
        </div>
        <div id='help-cont'>
            <div class='help-text'>
                <p>Οι φοιτητές του ΤΕΙ Αθήνας μπορούν να εισέρχονται στο σύστημα με τη χρήση
                των στοιχείων πρόσβασης των δικτυακών υπηρεσιών του Ιδρύματος
                e-mail, wifi, εύδοξος κ.ο.κ.)</p>
            </div>
<!--
            <div class='help-text'>
                <p>Οι νεοεισαχθέντες φοιτητές του ΤΕΙ Αθήνας μπορούν να εισέρχονται στο σύστημα με τη χρήση του ΑΜ με τον οποίo έκαναν τη δήλωση του μηχανογραφικού τους στις Πανελλαδικες Εξετάσεις.</p>
                <p>Ο κωδικός που πρέπει να χρησιμοποιήσετε αποτελείται από το πρώτο γράμμα του επωνύμου, του ονόματος, του πατρωνύμου και του μητρωνύμου σας, όπως αυτά έχουν καταχωρηθεί στο μηχανογραφικό.</p>
                <p>Π.χ. για τον Ιωάννη Παπαδόπουλο του Δημητρίου και της Ελένης, ο κωδικός είναι 'ΙΠΔΕ'.
                Αν κάποιο ή κάποια από τα πεδία είναι κενά, παραλείπονται, π.χ. για την Ευθυμία Ψαρρά, του Θεοδώρου (χωρίς μητρώνυμο), ο κωδικός είναι 'ΕΨΘ'.</p>
                <p>Για οποιαδήποτε απορία, μπορείτε να επικοινωνήσετε με το roommates@teiath.gr.</p>
            </div>
-->
        </div>
    </div>
</div>


