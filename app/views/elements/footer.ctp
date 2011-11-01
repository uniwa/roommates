<div id='footer'>
    <div id='footer-menu'>
        <ul>
            <?php if (isset($selected_action) && $selected_action == 'users_terms') { ?>
                <li class='menu-item menu-footer menu-selected'>
            <?php } else { ?>
                <li class='menu-item menu-footer'>
            <?php } ?>
                <?php echo $this->Html->link('Όροι χρήσης', array('controller' => 'users','action' => 'publicTerms')); ?>
            </li>

            <?php if (isset($selected_action) && $selected_action == 'users_faq') { ?>
                <li class='menu-item menu-footer menu-selected'>
            <?php } else { ?>
                <li class='menu-item menu-footer'>
            <?php } ?>
                <?php echo $this->Html->link('Συχνές ερωτήσεις', array('controller' => 'users','action' => 'faq')); ?>
            </li>
        </ul>
    </div>
    <div id='footer-main'>
        <div class="logos">
        <?php
            echo $this->Html->image("logos/tei.png", array("alt" => "" ,"style" => "width:110px;height:90px;"));
            echo $this->Html->image("logos/psifiakiellada.JPG", array("alt" => "" ,"style" => "width:80px;height:90px;"));
            echo $this->Html->image("logos/epsa_logo_CMYK.png", array("alt" => "" ,"style" => "width:220px;height:90px;"));
            echo $this->Html->image("logos/eurwpaikienwsi.jpg", array("alt" => "" ,"style" => "width:140px;height:90px;"));
        ?>
            Με τη συγχρηματοδότηση της Ελλάδας και της Ευρωπαϊκής Ένωσης – Ευρωπαϊκό Ταμείο Περιφερειακής Ανάπτυξης
        </div>
        <div class="info">
        <?php
            if(isset($active)){
                echo $this->Html->image("group-users.png", array("title" => "Ενεργά προφίλ χρηστών:". $active['profiles'])) ;
                echo $active['profiles'];
                echo $this->Html->image("favicon.ico", array("title" => "Καταχωρημένα σπίτια:". $active['houses'])) ;
                echo $active['houses'];
             }
        ?>
        </div>

    </div>
</div>
