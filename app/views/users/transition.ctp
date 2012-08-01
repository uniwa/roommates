<div id='loginView'>
    <div id='loginForm' class='mainCenter'>
        <?php
            echo $this->Form->create('User', array('action' => 'transition', 'class' => 'loginForm'));
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
                    echo $this->Form->submit('Μετάβαση', array('name' => 'transition', 'class' => 'button'));
                ?>
            </div>
        </div>
        <?php
            echo $this->Form->end();
        ?>

        <div id='help-cont'>
            <div id='help-text'>
                <p>Συμπληρώστε τα στοιχεία πρόσβασης</p>
                <p>των δικτυακών υπηρεσιών του Ιδρύματος (e-mail, wifi, εύδοξος κ.ο.κ.)</p>
                <p>ώστε να γίνει η μετάβαση από τον προσωρινό λογαριασμό.</p>
            </div>
        </div>
    </div>
</div>
