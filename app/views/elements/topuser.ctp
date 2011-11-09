<?php
    $role = $this->Session->read('Auth.User.role');
?>
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
                if($role !== 'admin'){
                    $linkClass = 'menu-item menu-user';
                    if(isset($selected_action) &&
                       ($selected_action == 'houses_view' ||
                        $selected_action == 'houses_manage')) {
                        $linkClass .= ' menu-selected';
                    }

                    $house_id = $this->Auth->get('House.id');
                    if ($role == 'realestate') {
                        $linkContent = 'Διαχείριση σπιτιών';
                        $linkAction = 'manage';
                        $actionTarget = null;
                    } else {
                        if ($house_id != null) {
                            $linkContent = 'Το σπίτι μου';
                            $linkAction = 'view';
                            $actionTarget = $house_id;
                        } else {
                                $linkContent = 'Προσθήκη σπιτιού';
                                $linkAction = 'add';
                                $actionTarget = null;
                        }
                    }

                    echo $this->Html->link($linkContent, array('controller' => 'houses',
                        'action' => $linkAction, $actionTarget), array('class' => $linkClass));
                }
            ?>
        </li>
        <li>
            <?php
                if($role !== 'admin'){
                    $linkClass = 'menu-item menu-user';
                    $linkContent = ($role == 'realestate')?'Στοιχεία επικοινωνίας':'Το προφίλ μου';
                    if(isset($selected_action) &&
                       ($selected_action == 'profiles_view' ||
                        $selected_action == 'real_estates_view')) {
                        $linkClass .= ' menu-selected';
                    }
                    if ($role == 'realestate') {
                        $id = $this->Auth->get('RealEstate.id');
                        $controller = 'real_estates';
                    } else {
                        $id = $this->Auth->get("Profile.id");
                        $controller = 'profiles';
                    }

                    echo $this->Html->link($linkContent, array('controller' => $controller,
                        'action' => 'view', $id), array('class' => $linkClass));
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
