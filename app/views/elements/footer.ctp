<div id='footer'>
    <div id='footer-menu'>
        <ul>
            <?php if (isset($selected_action) && $selected_action == 'users_terms') { ?>
                <li class='menu-item menu-footer menu-selected'>
            <?php } else { ?>
                <li class='menu-item menu-footer'>
            <?php
                }
                echo $this->Html->link('Όροι χρήσης', array(
                    'controller' => 'users','action' => 'publicTerms'));
            ?>
            </li>
            <?php if (isset($selected_action) && $selected_action == 'users_faq') { ?>
                <li class='menu-item menu-footer menu-selected'>
            <?php } else { ?>
                <li class='menu-item menu-footer'>
            <?php
                }
                echo $this->Html->link('Συχνές ερωτήσεις', array(
                    'controller' => 'users','action' => 'faq'));
            ?>
            </li>
        </ul>
    </div>
    <div id='footer-main'>
        <div class='logos'>
            <img src='img/logos/tei.png' alt='ΤΕΙ Αθήνας' />
            <img src='img/logos/psifiakiellada.JPG' alt='Ψηφιακή Ελλάδα' />
            <img src='img/logos/epsa_logo_CMYK.png' alt='ΕΣΠΑ 2007-2013' />
            <img src='img/logos/eurwpaikienwsi.jpg' alt='Ευρωπαϊκή Ένωση' />
        </div>
        <div class='funding'>
            Με τη συγχρηματοδότηση της Ελλάδας και της Ευρωπαϊκής Ένωσης
             - Ευρωπαϊκό Ταμείο Περιφερειακής Ανάπτυξης
        </div>
        <div class='infos'>
            <?php
                if(isset($active)){
                    $activeusers = $active['profiles'];
                    $activehouses = $active['houses'];
                    echo "<img src='img/group-users.png' alt''Ενεργά προφίλ χρηστών' class='info' />";
                    echo "<div class='info'>".$activeusers."</div>";
                    echo "<img src='img/favicon.ico' alt='Καταχωρημένα σπίτια' class='info' />";
                    echo "<div class='info'>".$activehouses."</div>";
                } // isset active
            ?>
        </div>
    </div>
</div>

