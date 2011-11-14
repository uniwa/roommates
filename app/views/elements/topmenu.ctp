<div id='top-menu'>
    <ul>
        <li>
            <?php
                // Only students can search for roommates
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
                if($this->Session->read('Auth.User.role') == 'admin'){
                    $linkClass = 'menu-item menu-main';
                    if(isset($selected_action) && $selected_action == 'admin_search'){
                        $linkClass .= ' menu-selected';
                    }
                    echo $this->Html->link('Αναζήτηση χρηστών', array('controller' => 'admins',
                        'action' => 'search'), array('class' => $linkClass));
                }
            ?>
        </li>
    </ul>
</div>

