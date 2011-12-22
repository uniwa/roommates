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
    </div>
</div>

