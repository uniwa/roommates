

<div id="navigation" class="column">
    <ul id="nav">
        <li class="page_item current_page_item home">
            <a href="#"><span>Αρχική</span></a>
        </li>
        <li>
            <?php echo $this->Html->link('Ολα τα σπίτια', array(
                                                               'controller' => 'houses',
                                                               'action' => 'index')); ?>
        </li>
        <li>
            <?php 
                $profile_id = $this->Auth->get("Profile.id");
                echo $this->Html->link(' Το προφίλ μου', array(
                                                               'controller' => 'profiles',
                                                               'action' => 'view',
                                                               $profile_id,
                                                           )); ?>
        </li>

        <li>
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
        <li>
            <?php echo $this->Html->link('Αναζήτηση Συγκατοίκου', array(
                                                                       'controller' => 'profiles',
                                                                       'action' => 'search')); ?>
        </li>

        <?php if ($this->Session->read('Auth.User')) {
            echo '<li>';
            echo $this->Html->link('Αποσύνδεση ('.$this->Session->read("Auth.User.username").")", array('controller' => 'users',
                                                      'action' => 'logout'));
            echo '</li>';
        }?>
        <li class="rss">
            <a href="#" title="Subscribe"><img src="<?php echo $this->webroot; ?>img/rss.png"
                                               alt="RSS-feed"/></a>
        </li>
    </ul>
</div>