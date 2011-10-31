<div id='top-user'>
    <ul>
        <li class='menu-item menu-user menu-login'>
            <?php
                echo $this->Html->link('αποσύνδεση ('.$this->Session->read("Auth.User.username").")",
                    array('controller' => 'users', 'action' => 'logout'));
            ?>
        </li>
        <li class='menu-item menu-user'>
            <?php
                $house_id = $this->Auth->get("House.id");
                    if ($house_id != NULL) {
                        echo $this->Html->link('Το σπίτι μου', array('controller' => 'houses',
                            'action' => 'view', $house_id));
                    } else {
                        echo $this->Html->link('Προσθήκη σπιτιού', array('controller' => 'houses',
                            'action' => 'add'));
                }
            ?>
        </li>
        <li class='menu-item menu-user'>
            <?php 
                $profile_id = $this->Auth->get("Profile.id");
                echo $this->Html->link(' Το προφίλ μου', array('controller' => 'profiles',
                                                               'action' => 'view',
                                                               $profile_id,)); 
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
