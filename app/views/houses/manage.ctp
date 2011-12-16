<div id='leftbar' class='leftSearch'>
    <div class='left-form-cont-house'>
        <div class='form-title'>
            <h2>Προσθήκη σπιτιού</h2>
        </div>
        <div class='button'>
            <?php
                $addLink = array('controller' => 'houses', 'action' => 'add');
                $addClass = 'buttonAdd';
                echo $this->Html->link('προσθήκη', $addLink, array('class' => $addClass));
            ?>
        </div>
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
<div id='main-inner' class='mainSearch'>
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
                        $queryString .= "type={$this->params['url']['type']}&";
                        $queryString .= "area={$this->params['url']['area']}&";
                        $queryString .= "price={$this->params['url']['price']}";
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
                <div class='col manage-address'>
                    <?php echo $this->Paginator->sort('Διεύθυνση', 'House.price'); ?>
                </div>
                <div class='col manage-type'>
                    <?php echo $this->Paginator->sort('Τύπος', 'House.type'); ?>
                </div>
                <div class='col manage-area'>
                    <?php echo $this->Paginator->sort('Εμβαδόν', 'House.area'); ?>
                </div>
                <div class='col manage-price'>
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
                <div class='col manage-address'>
                    <?php
                        echo $this->Html->link($house['House']['address'],
                            "/houses/view/{$house['House']['id']}");
                    ?>
                </div>
                <div class='col manage-type'>
                    <?php echo $house['HouseType']['type']; ?>
                </div>
                <div class='col manage-area'>
                    <?php echo $house['House']['area']; ?>
                </div>
                <div class='col manage-price'>
                    <?php echo $house['House']['price']; ?>
                </div>
                <div class='col manage-delete'>
                    <?php
                        echo $this->Html->link('διαγραφή',
                            array('action' => 'delete', $house['House']['id']),
                            null, 'Είστε σίγουρος/η;');
                    ?>
                </div>
            </div>
            <?php
                } //foreach
            ?>
        </div>
        <div class='admpaginator'>
            <?php
                if($page_num > 1){
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
                }
            ?>
        </div>
    <?php } //isset results ?>
</div>
