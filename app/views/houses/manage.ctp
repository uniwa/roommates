<style>
    #leftbar{
        float: left;
        margin: 0px 0px 0px 0px;
        padding: 0px 0px 0px 0px;
        width: 300px;
    }

    #main-inner{
        float: left;
        border-left: 1px dotted #aaa;
        margin: 0px 0px 10px 2px;
        padding: 0px 0px 0px 0px;
        width: 620px;
    }

    .form-title{
        clear: both;
        margin: 12px 0px 12px 8px;
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

    .buttonAdd{
        background-color: #aaa;
        margin: 12px 0px 0px 48px;
        padding: 4px 12px 4px 12px;
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
    
    .housetable{
        margin: 0px 0px 0px 8px;
    }
    
    .colodd{
        background-color: #f3f3f3;
    }
    
    .rowodd{
        background-color: #f3f3f3;
    }
    
    .rowtitle{
        background-color: #ccc;
        border-bottom: 1px solid #000;
        font-weight: bold;
        text-decoration: none;
    }
    
    .row{
        clear: both;
        width: 100%;
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

    .address{
        width: 180px;
        overflow: hidden;
    }

    .type{
        width: 100px;
    }
    
    .area{
        width: 60px;
    }

    .price{
        width: 50px;
    }

    .admpaginator{
        clear: both;
        margin: 20px auto 20px auto;
        text-align: center;
    }
</style>

<div id='leftbar'>
    <div class='left-form-cont'>
        <div class='form-title'>
            <h2>Προσθήκη σπιτιού</h2>
        </div>
        <?php
            $addLink = array('controller' => 'houses', 'action' => 'add');
            $addClass = 'button buttonAdd';
            echo $this->Html->link('προσθήκη', $addLink, array('class' => $addClass));
        ?>
        <div class='form-title'>
            <h2>Αναζήτηση σπιτιών</h2>
        </div>
        <?php
            echo $this->Form->create('House', array(
                'type' => 'get',
                'controller' => 'houses',
                'action' => 'manage'));
        ?>
        <ul>
            <li class='form-line'>
                <div class='form-elem form-label'>
                    Τύπος σπιτιού
                </div>
                <div class='form-elem form-input'>
                    <?php
                        echo $this->Form->input('house_type', array('label' => '',
                            'options' => $house_type_options,
                            'value' => isset($defaults) ? $defaults['house_type'] : '',
                            'empty' => 'Αδιάφορο' ));
                    ?>
                </div>
            </li>
            <li class='form-line'>
                <div class='form-elem form-label'>
                    Ταξινόμηση 
                </div>
                <div class='form-elem form-input'>
                    <?php
                        echo $this->Form->input('order_by', array('label' => '',
                            'options' => $order_options,
                            'selected' => isset($defaults['order_by']) ? $defaults['order_by'] : '0',
                            'class' => 'input-elem'));
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
        if (isset($results)) {
    ?>
        <div class='search-title'>
            <h2>Καταχωρημένα σπίτια</h2>
        </div>
        <div class='search-subtitle'>
        <?php
            $count = $this->Paginator->counter(array('format' => '%count%'));
            $foundmessage = $count." σπίτια";
            echo $foundmessage;
        ?>
        </div>
        <div class='admpaginator' >
            <?php
                /*records per page*/
                $current_recs = $this->Paginator->counter(array('format' => '%count%')); 
                /*change type from String to int*/
                settype($current_recs, "integer");

                $page_num = $this->Paginator->counter(array('format' => '%pages%'));
                settype($page_num, "integer");

                $page = $this->Paginator->current();
                $count = ($page-1)*$limit;

                if($page_num > 1){
                    /*
                     *Pass params in paginator options in case form is submited
                     *so as to hold params in new page
                     */
                    if(isset($this->params['url']['address']) ||
                        isset($this->params['url']['type']) ||
                        isset($this->params['url']['area']) ||
                        isset($this->params['url']['price'])){
                        $queryString = "address={$this->params['url']['address']}&";
                        $queryString .= "banned={$this->params['url']['type']}&";
                        $queryString .= "banned={$this->params['url']['area']}&";
                        $queryString .= "banned={$this->params['url']['price']}";
                        $options = array('url' => array(
                            'controller' => 'houses',
                            'action' => 'manage', '?' => $queryString));
                        $this->Paginator->options($options);
                    }
                    
                    /* pagination anv*/
                    echo $paginator->prev('« Προηγούμενη ',null, null,
                        array('class' => 'disabled'));
                    /* show pages */
                    echo $paginator->numbers(array(
                        'first' => 3, 'last' => 3,
                        'modulus' => '4', 'separator' => ' '));
                    /* Shows the next link */
                    echo $paginator->next(' Επόμενη » ', null, null,
                        array('class' => 'disabled'));
                }
            ?>
        </div>
        <!-- pagination -->
        <div class='housetable'>
            <div class='row rowtitle'>
                <div class='col num'>
                    #
                </div>
                <div class='col address'>
                    <?php echo $this->Paginator->sort('Διεύθυνση', 'House.price'); ?>
                </div>
                <div class='col type'>
                    <?php echo $this->Paginator->sort('Τύπος', 'House.type'); ?>
                </div>
                <div class='col area'>
                    <?php echo $this->Paginator->sort('Εμβαδόν', 'House.area'); ?>                    
                </div>
                <div class='col price'>
                    <?php echo $this->Paginator->sort('Ενοίκιο', 'House.price'); ?>
                </div>
            </div>
            <?php
                $count = 0;
                $rowodd = true;
                foreach($results as $house){
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
                <div class='col address'>
                    <?php
                        echo $this->Html->link($house['House']['address'],
                            "/houses/view/{$house['House']['id']}");
                    ?>
                </div>
                <div class='col type'>
                    <?php echo '';// $house['House']['type']; ?>
                </div>
                <div class='col area'>
                    <?php echo $house['House']['area']; ?>
                </div>
                <div class='col price'>
                    <?php echo $house['House']['price']; ?>
                </div>
            </div>
            <?php
                } //foreach
            ?>
        </div>
        <div class='admpaginator'>
            <?php
                /* pagination anv*/
                echo $paginator->prev('« Προηγούμενη ',null, null,
                    array( 'class' => 'disabled' ) );
                /* show pages */
                echo $paginator->numbers(array(
                    'first' => 3, 'last' => 3,
                    'modulus' => '4', 'separator' => ' '));
                /* Shows the next link */
                echo $paginator->next(' Επόμενη » ', null, null,
                    array('class' => 'disabled'));
            ?>
        </div>
    <?php } //isset results ?>
</div>
