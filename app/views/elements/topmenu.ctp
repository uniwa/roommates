<div id='top-menu'>
    <ul>
        <li>
            <?php
                $userNull = $this->Session->read("Auth.User") == NULL;
                $userBanned = $this->Session->read('Auth.User.banned');
                // Logo
                $linkClass = 'menu-logo';
                $logo = $this->Html->image('logo.png', array('alt' => '+κατοικώ'));
                echo $this->Html->link($logo, array('controller' => 'pages',
                    'action' => 'display'), array('class' => $linkClass, 'escape' => false));
            ?>
        </li>
            <?php
                if(!$userNull){
            ?>
        <li>
            <?php
                // Roommates search
                // realestates cannot search for roommates
                if ($this->Session->read('Auth.User.role') != 'realestate') {
                    $linkClass = 'menu-item menu-main';
                    if(isset($selected_action) && $selected_action == 'profiles_search'){
                        $linkClass .= ' menu-selected';
                    }
                    echo $this->Html->link('Αναζήτηση συγκατοίκων', array('controller' => 'profiles',
                        'action' => 'search'), array('class' => $linkClass));
                }
            ?>
        </li>
        <li>
            <?php
                // Houses search
                $linkClass = 'menu-item menu-main';
                if(isset($selected_action) && $selected_action == 'houses_search'){
                    $linkClass .= ' menu-selected';
                }
                echo $this->Html->link('Αναζήτηση σπιτιών', array('controller' => 'houses',
                    'action' => 'search'), array('class' => $linkClass));
            ?>
        </li>
        <li>
            <?php
                // Users administration
                if($this->Session->read('Auth.User.role') == 'admin'){
                    $linkClass = 'menu-item menu-main';
                    if(isset($selected_action) && $selected_action == 'manage_user'){
                        $linkClass .= ' menu-selected';
                    }
                    echo $this->Html->link('Διαχείριση χρηστών', array('controller' => 'admins',
                        'action' => 'manage_users'), array('class' => $linkClass));
                }
            ?>
        </li>
        <li>
            <?php
                // RealEstates administration
                if($this->Session->read('Auth.User.role') == 'admin'){
                    $linkClass = 'menu-item menu-main';
                    if(isset($selected_action) && $selected_action == 'manage_realestate'){
                        $linkClass .= ' menu-selected';
                    }
                    echo $this->Html->link('Διαχείριση ενοικιαστών', array('controller' => 'admins',
                        'action' => 'manage_realestates'), array('class' => $linkClass));
                }
            ?>
        </li>
            <?php
                } // !$userNull
            ?>
    </ul>
</div>

