<div id='footer'>
    <div id='footer-menu'>
        <ul>
            <li>
                <?php
                    $linkClass = 'menu-item menu-footer';
                    if(isset($selected_action) && $selected_action == 'users_terms'){
                        $linkClass .= ' menu-selected';
                    }
                    echo $this->Html->link('Όροι χρήσης', array('controller' => 'users',
                        'action' => 'publicTerms'), array('class' => $linkClass));
                ?>
            </li>
            <li>
                <?php
                    $userNull = $this->Session->read("Auth.User") == NULL;
                    if(!$userNull){
                        $linkClass = 'menu-item menu-footer';
                        if(isset($selected_action) && $selected_action == 'help'){
                            $linkClass .= ' menu-selected';
                        }
                        echo $this->Html->link('Αναφορά προβλήματος', array('controller' => 'users',
                            'action' => 'help'), array('class' => $linkClass));
                    }
                ?>
            </li>
            <li>
                <?php
                    $linkClass = 'menu-item menu-footer';
                    if(isset($selected_action) && $selected_action == 'users_faq'){
                        $linkClass .= ' menu-selected';
                    }
                    echo $this->Html->link('Συχνές ερωτήσεις', array('controller' => 'users',
                        'action' => 'faq'), array('class' => $linkClass));
                ?>
            </li>
        </ul>
    </div>
    <div id='footer-main'>
        <div class='logos'>
            <?php
                echo $this->Html->image('logos/footer_logo.png', array('alt' => 'logo'));
            ?>
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
                    $iconUsers = $this->Html->image('group-users.png', array(
                        'alt' => 'Ενεργά προφίλ χρηστών',
                        'title' => 'Ενεργά προφίλ χρηστών',
                        'class' => 'info'));
                    $iconHouses = $this->Html->image('house-count.png', array(
                        'alt' => 'Καταχωρημένα σπίτια',
                        'title' => 'Καταχωρημένα σπίτια',
                        'class' => 'info'));
                    echo $iconUsers;
                    echo "<div class='info'>{$activeusers}</div>";
                    echo $iconHouses;
                    echo "<div class='info'>{$activehouses}</div>";
                } // isset active
            ?>
        </div>
    </div>
</div>

