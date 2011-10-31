<div id='footer'>
    <div id='footer-menu'>
        <ul>
            <li class='menu-item menu-footer'>
                <?php echo $this->Html->link('Όροι χρήσης', array('controller' => 'users','action' => 'publicTerms')); ?>
            </li>
            <li class='menu-item menu-footer'>
                <?php echo $this->Html->link('Συχνές ερωτήσεις', array('controller' => 'users','action' => 'faq')); ?>
            </li>
        </ul>
    </div>
    <div id='footer-main'>
        <div class="logos">
        <?php
            echo $this->Html->image("logos/psifiakiellada.JPG", array("alt" => "" ,"style" => "width:80px;height:100px;"));
            echo $this->Html->image("logos/flag1_big.jpg", array("alt" => "" ,"style" => "width:110px;height:100px;"));
            echo $this->Html->image("logos/epsa_logo_CMYK.png", array("alt" => "" ,"style" => "width:250px;height:100px;"));
            echo $this->Html->image("logos/gr_big.jpg", array("alt" => "" ,"style" => "width:140px;height:100px;"));
        ?>
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