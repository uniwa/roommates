<?php ?>

<div id="navigation" class="column">
    <ul id="nav">
        <li class="page_item current_page_item home">
            <a href="#"><span>Αρχική</span></a>
        </li>
        <li>
            <?php echo $this->Html->link('Όλα τα σπίτια', array(
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
            <?php echo $this->Html->link('Αναζήτηση συγκατοίκων', array(
                                                                       'controller' => 'profiles',
                                                                       'action' => 'search')); ?>
        </li>

        <li>
            <?php echo $this->Html->link('Αναζήτηση σπιτιών', array('controller' => 'houses',
                                                                   'action' => 'search')); ?>
        </li>
        <li>
            <?php echo $this->Html->link('Όροι χρήσης', array('controller' => 'users','action' => 'publicTerms')); ?>

        </li>


        <li>
            <?php echo $this->Html->link('Σύνθετη Αναζήτηση', array('controller' => 'houses',
                                                                   'action' => 'advanced_search')); ?>
    	</li>

        <li>
            <?php 
                if( $this->Session->read( 'Auth.User.profile' ) == 'amdin' ) {

                   echo $this->Html->link( 'Αναζήτηση Χρήστη', array( 'controller' => 'admin', 'action' => 'search' ) );
                }
            ?>
        </li>

        <?php if ($this->Session->read('Auth.User')) {
        echo '<li>';
        echo $this->Html->link('Αποσύνδεση (' . $this->Session->read("Auth.User.username") . ")", array('controller' => 'users',
                                                                                                       'action' => 'logout'));
        echo '</li>';
    }?>
        <li class="rss">
            <?php
            $userid = $this->Session->read('Auth.User.id');
            echo $this->Html->link(
                $this->Html->image("rssIn.png", array("alt" => "Subscribe to RSS.")),
                "/houses/index.rss",
                array('escape' => false)
            );
            ?>
        </li>
        <li>
			<div id='active-info'>
            <?php
				if(isset($active)){
					echo "Ενεργά προφίλ χρηστών: ".$active['profiles']."<br />\n";
					echo "Καταχωρημένα σπίτια: ".$active['houses']."<br />\n";
				}
			?>
			</div>
        </li>

    </ul>
</div>
