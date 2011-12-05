<style>
    #leftbar{
        float: left;
        margin: 0px 0px 0px 0px;
        padding: 0px 0px 0px 16px;
        width: 260px;
    }

    #main-inner{
        float: left;
        border-left: 1px solid #ddd;
        margin: 10px 0px 10px 2px;
        padding: 0px 0px 0px 0px;
        width: 660px;
        min-height: 300px;
        overflow: hidden;
    }

    .form-title{
        clear: both;
        margin: 16px 0px 12px 8px;
        font-size: 1.2em;
        font-weight: bold;
    }
    
    .left-form ul{
        margin: 0px 0px 20px 0px;
    }
    
    .left-form ul{
        margin: 0px 0px 20px 0px;
    }
    
    .form-buttons{
        margin: 10px auto;
        width: 220px;
    }
    
    .form-elem{
        margin: 0px 8px 8px 0px;
        font-size: 1.2em;
    }

    .form-label{
        float: left;
        width: 100px;
    }
    
    .form-input{
        float: left;
        width: 140px;
        overflow: no-scroll;
    }

    .form-submit{
        float: left;
    }

    .button{
        border: 0px;
        width: 100px;
        height: 24px;
        cursor: pointer;
    }
    
    .search-title{
/*        margin: 12px 0px 8px 48px;*/
        text-align: center;
        font-size: 1.2em;
        font-weight: bold;
    }

    .search-subtitle{
/*        margin: 0px 0px 12px 64px;*/
        text-align: center;
        font-size: 1.2em;
        font-style: italic;
    }
    
    .pagination{
        margin: 0px auto 12px auto;
        text-align: center;
    }
    
    .pagination ul li{
        display: inline;
    }

    .pagination ul li.current{
        border: 1px solid #59A4D8;
        padding: 0px 2px 0px 2px;
        font-weight: bold;
    }
    
    .pagination ul li.disabled{
        color: #aaa;
    }
    
    .usertable{
        margin: 0px 0px 0px 8px;
    }
    
    .colodd{
        background-color: #f3f3f3;
    }
    
    .rowodd{
        background-color: #f3f3f3;
    }
    
    .rowtitle{
        border-top: 3px solid #aaa;
        border-bottom: 1px solid #aaa;
        margin: 0px 0px 8px 0px;
        font-weight: bold;
        text-decoration: none;
    }
    
    .row{
        clear: both;
        height: 100%;
        overflow: hidden;
    }
    
    .col{
        float: left;
/*        border-right: 1px solid #bbb;*/
        margin: 0px 4px 0px 0px;
        padding: 4px;
        text-align: center;
    }
    
    .num{
        width: 12px;
    }

    .uname{
        width: 60px;
    }

    .fname,.lname,.cname{
        width: 120px;
    }

    .email{
        width: 180px;
    }

    .banned,.enabled{
        border-right: 0px;
        width: 80px;
    }

    .admpaginator{
        clear: both;
        margin: 20px auto 20px auto;
        text-align: center;
    }
    
    .optionUnbanned{
        background-image: url('img/unlock_16.png');
        background-position: 50% 50%;
        background-repeat: no-repeat;
        margin: 0 auto;
        width: 16px;
        height: 16px;
        text-indent: -9999px;
    }
    
    .optionUnbanned:hover{
        background-image: url('img/lock_16.png');
    }
    
    .optionBanned{
        background-image: url('img/lock_16.png');
        background-position: 50% 50%;
        background-repeat: no-repeat;
        margin: 0 auto;
        width: 16px;
        height: 16px;
        text-indent: -9999px;
    }
    
    .optionBanned:hover{
        background-image: url('img/unlock_16.png');
    }

    .optionEnabled{
        background-image: url('img/accept_16.png');
        background-position: 50% 50%;
        background-repeat: no-repeat;
        margin: 0 auto;
        width: 16px;
        height: 16px;
        text-indent: -9999px;
    }
    
    .optionEnabled:hover{
        background-image: url('img/delete_16.png');
    }
    
    .optionDisabled{
        background-image: url('img/delete_16.png');
        background-position: 50% 50%;
        background-repeat: no-repeat;
        margin: 0 auto;
        width: 16px;
        height: 16px;
        text-indent: -9999px;
    }
    
    .optionDisabled:hover{
        background-image: url('img/accept_16.png');
    }
</style>

<div id='leftbar'>
    <div class='left-form-cont'>
        <div class='form-title'>
            <h2>Αναζήτηση</h2>
        </div>
        <?php
            echo $this->Form->create( 'Admin', array(
                'type' => 'get',
                'controller' => 'admins',
                'action' => 'manage_realestates'));
        ?>
        <ul>
            <li class='form-line'>
                <div class='form-elem form-label'>
                    όνομα χρήστη
                </div>
                <div class='form-elem form-input'>
                <?php
                    echo $this->Form->text('name', array(
                        'value' => isset($this->params['url']['name'])?
                            $this->params['url']['name']:'',
                        'class' => 'input-elem'));
                ?>
                </div>
            </li>
            <li class='form-line'>
                <div class='form-elem form-input'>
                <?php
                    if(isset($this->params['url']['banned']) && $this->params['url']['banned'] == 1 ){
                        $check = 'checked';
                    } else {
                        $check = 'unchecked';
                    }
                    echo $this->Form->checkbox('banned', array('checked' => $check));
                    echo $this->Form->label(' Κλειδωμένοι');
                ?>
                </div>
            </li>
            <li class='form-line'>
                <div class='form-elem form-input'>
                <?php
                    if(isset($this->params['url']['disabled']) && $this->params['url']['disabled'] == 1){
                        $check = 'checked';
                    }else{
                        $check = 'unchecked';
                    }
                    echo $this->Form->checkbox('disabled', array('checked' => $check));
                    echo $this->Form->label(' Ανενεργοί');
                ?>
                </div>
            </li>
            <li class='form-line form-buttons'>
                <div class='form-elem form-submit'>
                    <?php
                        echo $this->Form->submit('αναζήτηση', array(
                            'class' => 'button'));
                        echo $this->Form->end();
                    ?>
                </div>
            </li>
        </ul>
    </div>
</div>
<div id='main-inner'>
    <div class='results'>
    <?php
        if(isset($results)){
    ?>
        <div class='search-title'>
            <h2>Εγγεγραμμένοι ενοικιαστές</h2>
        </div>
        <div class='search-subtitle'>
        <?php
            $count = $this->Paginator->counter(array('format' => '%count%'));
            $foundmessage = $count." ενοικιαστές";
            echo $foundmessage;
        ?>
        </div>
        <div class='admpaginator'>
            <?php
                // records per page
                $current_recs = $this->Paginator->counter(array('format' => '%count%')); 
                // change type from String to int
                settype($current_recs, 'integer');

                $page_num = $this->Paginator->counter(array('format' => '%pages%'));
                settype($page_num, 'integer');

                $page = $this->Paginator->current();
                $count = ($page-1)*$limit;

                if($page_num > 1){
                     // Pass params in paginator options in case form is submited
                     // so as to hold params in new page
                    if(isset($this->params['url']['name']) || isset($this->params['url']['banned'])
                        || isset($this->params['url']['disabled'])){
                        $queryString = "name={$this->params['url']['name']}&
                            banned={$this->params['url']['banned']}&
                            disabled={$this->params['url']['disabled']}";
                        $options = array('url' => array(
                            'controller' => 'admins', 'action' => 'manage_realestates', '?' => $queryString));
                        $this->Paginator->options($options);
                    }
                    
                    // pagination anv
                    echo $paginator->prev('« Προηγούμενη ',null, null, array('class' => 'disabled'));
                    // show pages
                    echo $paginator->numbers(array(
                        'first' => 3, 'last' => 3, 'modulus' => '4', 'separator' => ' '));
                    // Shows the next link
                    echo $paginator->next(' Επόμενη » ', null, null, array('class' => 'disabled'));
                }
            ?>
        </div>
        <!-- pagination -->
        <div class='usertable'>
            <div class='row rowtitle'>
                <div class='col num'>
                    #
                </div>
                <div class='col uname'>
                    <?php echo $this->Paginator->sort('χρήστης', 'User.username'); ?>
                </div>
                <div class='col cname'>
                    <?php echo $this->Paginator->sort('επωνυμία', 'RealEstate.firstname'); ?>
                </div>
                <div class='col email'>
                    email
                </div>
                <div class='col banned'>
                    κλείδωμα
                </div>
                <div class='col enabled'>
                    ενεργός
                </div>
            </div>
            <?php
                $count = 0;
                $rowodd = true;
                foreach($results as $user){
                    $count++;
                    $rowodd = !$rowodd;
                    $rowclass = 'row';
                    if($rowodd){
                        $rowclass .= ' rowodd';
                    }
                    echo "<div class='".$rowclass."'>\n";
            ?>
                <div class='col num'>
                    <?php echo $count; ?>
                </div>
                <div class='col uname'>
                    <?php
                        echo $this->Html->link($user['User']['username'],
                            array('controller' => 'real_estates',
                            'action' => 'view', $user['RealEstate']['id']));
                    ?>
                </div>
                <div class='col cname'>
                    <?php echo $user['RealEstate']['company_name']; ?>
                </div>
                <div class='col email'>
                    <?php echo $user['RealEstate']['email']; ?>
                </div>
                <div class='col banned'>
                    <?php
                        $textUnbanned = "<div class='optionUnbanned'>κλείδωμα</div>";
                        $textBanned = "<div class='optionBanned'>ξεκλείδωμα</div>";
                        echo (($user['User']['banned'])?
                            $this->Html->link($textBanned,array(
                                'controller' => 'real_estates',
                                'action' => 'unban',
                                $user['RealEstate']['id']),
                                array('title' => 'ξεκλείδωμα', 'escape' => false)):
                            $this->Html->link($textUnbanned,array(
                                'controller' => 'real_estates',
                                'action' => 'ban',
                                $user['RealEstate']['id']),
                                array('title' => 'κλείδωμα', 'escape' => false)));
                    ?>
                </div>
                <div class='col enabled'>
                    <?php
                        $iconEnabled = $this->Html->image('accept_16.png',
                            array('alt' => 'ενεργός'));
                        $iconDisabled = $this->Html->image('delete_16.png',
                            array('alt' => 'ανενεργός'));
                        $textEnabled = "<div class='optionEnabled'>απενεργοποίηση</div>";
                        $textDisabled = "<div class='optionDisabled'>ενεργοποίηση</div>";
                        echo (($user['User']['enabled'])?
                            $this->Html->link($textEnabled,array(
                                'controller' => 'real_estates',
                                'action' => 'disable',
                                $user['RealEstate']['id']),
                                array('title' => 'απενεργοποίηση', 'escape' => false)):
                            $this->Html->link($textDisabled,array(
                                'controller' => 'real_estates',
                                'action' => 'enable',
                                $user['RealEstate']['id']),
                                array('title' => 'ενεργοποίηση', 'escape' => false)));
                    ?>
                </div>
            </div>
            <?php } //foreach ?>
        </div>
        <div class='admpaginator'>
            <?php
                if($page_num > 1){
                    /* pagination anv*/
                    echo $paginator->prev('« Προηγούμενη ',null, null, array( 'class' => 'disabled' ) );
                    /* show pages */
                    echo $paginator->numbers(array(
                        'first' => 3, 'last' => 3, 'modulus' => '4', 'separator' => ' '));
                    /* Shows the next link */
                    echo $paginator->next(' Επόμενη » ', null, null, array('class' => 'disabled'));
                }
            ?>
        </div>
    <?php
        } //isset results
    ?>
</div>


