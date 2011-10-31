<div id='top-user'>
    <ul>
        <li class='menu-item menu-user menu-login'>
            <?php
                echo $this->Html->link('αποσύνδεση ('.$this->Session->read("Auth.User.username").")",
                    array('controller' => 'users', 'action' => 'logout'));
            ?>
        </li>
        <li class='menu-item menu-user'>
            το σπίτι μου
        </li>
        <li class='menu-item menu-user'>
            το προφίλ μου
        </li>
        <li class='menu-item menu-rss menu-login'>
            <img src='img/rss.png' />
        </li>
    </ul>
</div>

