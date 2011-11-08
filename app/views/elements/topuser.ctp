<div id='top-user'>
    <ul>
        <li>
            <?php
                $uname = $this->Session->read('Auth.User.username');
                $linkContent = "αποσύνδεση ({$uname})";
                echo $this->Html->link($linkContent, array(
                    'controller' => 'users',
                    'action' => 'logout'),
                    array('class' => 'menu-item menu-user menu-login'));
            ?>
        </li>
        <li>
            <?php
                if($this->Session->read('Auth.User.role') !== 'admin'){
                    $linkClass = 'menu-item menu-user';
                    if(isset($selected_action) && $selected_action == 'houses_view'){
                        $linkClass .= ' menu-selected';
                    }
                    $house_id = $this->Auth->get('House.id');
                    if ($house_id != NULL) {
                        $linkContent = 'Το σπίτι μου';
                        $linkAction = 'view';
                        $actionTarget = $house_id;
                    }else{
                        $linkContent = 'Προσθήκη σπιτιού';
                        $linkAction = 'add';
                        $actionTarget = NULL;
                    }
                    echo $this->Html->link($linkContent, array('controller' => 'houses',
                        'action' => $linkAction, $actionTarget), array('class' => $linkClass));
                }
            ?>
        </li>
        <li>
            <?php
                if($this->Session->read('Auth.User.role') !== 'admin'){
                    $linkClass = 'menu-item menu-user';
                    if(isset($selected_action) && $selected_action == 'profiles_view'){
                        $linkClass .= ' menu-selected';
                    }
                    $profile_id = $this->Auth->get("Profile.id");
                    $linkContent = 'Το προφίλ μου';
                    echo $this->Html->link($linkContent, array('controller' => 'profiles',
                        'action' => 'view', $profile_id), array('class' => $linkClass));
                }
            ?>
        </li>
        <li class='menu-item menu-rss menu-login'>
            <?php
                $userid = $this->Session->read('Auth.User.id');
                echo $this->Html->link(
                    $this->Html->image("rss.png", array("alt" => "Subscribe to RSS.")),
                    "/houses/index.rss",
                    array('escape' => false)
                );
            ?>
        </li>
    </ul>
</div>
