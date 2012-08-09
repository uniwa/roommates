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
            <div id='help-text'>
                <p>Οι φοιτητές του ΤΕΙ Αθήνας μπορούν να εισέρχονται στο σύστημα με τη χρήση</p>
                <p>των στοιχείων πρόσβασης των δικτυακών υπηρεσιών του Ιδρύματος</p>
                <p>(e-mail, wifi, εύδοξος κ.ο.κ.)</p>
            </div>
            <div id='help-text'>
                <p>Οι νεοεισαχθέντες φοιτητές του ΤΕΙ Αθήνας μπορούν να εισέρχονται στο σύστημα με τη χρήση
                του ΑΜ και του μυστικού κωδικού τους με τα οποία έκαναν τη δήλωση του μηχανογραφικού τους
                στις Πανελλαδικες Εξετάσεις.</p>
            </div>
        </div>
    </div>
</div>


