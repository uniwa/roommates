<div id='leftbar' class='leftManage'>
    <div class='left-form-cont'>
        <div class='form-title'>
            <h2>Αναζήτηση χρηστών</h2>
        </div>
        <?php
            echo $this->Form->create( 'Admin', array(
                'type' => 'get',
                'controller' => 'admins',
                'action' => 'manage_users'));
        ?>
        <ul>
            <li class='form-line form-manage'>
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
            <li class='form-line form-manage'>
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
<div id='main-inner' class='mainManage'>
    <div class='results'>
    <?php
        if (isset($results)) {
    ?>
        <div class='search-title'>
            <h2>Εγγεγραμμένοι χρήστες</h2>
        </div>
        <div class='search-subtitle'>
        <?php
            $count = $this->Paginator->counter(array('format' => '%count%'));
            $foundmessage = $count." χρήστες";
            echo $foundmessage;
        ?>
        </div>
        <div class='admpaginator' >
            <?php
                /*records per page*/
                $current_recs = $this->Paginator->counter( array( 'format' => '%count%' ) ); 
                /*change type from String to int*/
                settype( $current_recs, "integer");

                $page_num = $this->Paginator->counter( array( 'format' => '%pages%' ) );
                settype( $page_num, "integer" );

                $page = $this->Paginator->current();
                $count = ($page-1)*$limit;

                if($page_num > 1){
                    /*
                     *Pass params in paginator options in case form is submited
                     *so as to hold params in new page
                     */
                    if(isset($this->params['url']['name']) || isset($this->params['url']['banned'])){
                        $queryString = "name={$this->params['url']['name']}&
                            banned={$this->params['url']['banned']}";
                        $options = array('url' => array(
                            'controller' => 'admins', 'action' => 'manage_users', '?' => $queryString));
                        $this->Paginator->options($options);
                    }
                    
                    /* pagination anv*/
                    echo $paginator->prev('« Προηγούμενη ',null, null, array('class' => 'disabled'));
                    /* show pages */
                    echo $paginator->numbers(array(
                        'first' => 3, 'last' => 3, 'modulus' => '4', 'separator' => ' '));
                    /* Shows the next link */
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
                <div class='col uname-user'>
                    <?php echo $this->Paginator->sort('Username', 'User.username'); ?>
                </div>
                <div class='col fname'>
                    <?php echo $this->Paginator->sort('Όνομα', 'Profile.firstname'); ?>
                </div>
                <div class='col lname'>
                    <?php echo $this->Paginator->sort('Επίθετο', 'Profile.lastname'); ?>
                </div>
                <div class='col email'>
                    email
                </div>
                <div class='col banned'>
                    κλείδωμα
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
                <div class='col uname-user'>
                    <?php
                        echo $this->Html->link($user['User']['username'],
                            "/profiles/view/{$user['Profile']['id']}");
                    ?>
                </div>
                <div class='col fname'>
                    <?php echo $user['Profile']['firstname']; ?>
                </div>
                <div class='col lname'>
                    <?php echo $user['Profile']['lastname']; ?>
                </div>
                <div class='col email'>
                    <?php echo $user['Profile']['email']; ?>
                </div>
                <div class='col banned'>
                    <?php
                        $textUnbanned = "<div class='optionUnbanned'>κλείδωμα</div>";
                        $textBanned = "<div class='optionBanned'>ξεκλείδωμα</div>";
                        echo (($user['User']['banned'])?
                            $this->Html->link($textBanned,array(
                                'controller' => 'profiles',
                                'action' => 'unban',
                                $user["Profile"]["id"]),
                                array('title' => 'ξεκλείδωμα', 'escape' => false)):
                            $this->Html->link($textUnbanned,array(
                                'controller' => 'profiles',
                                'action' => 'ban',
                                $user["Profile"]["id"]),
                                array('title' => 'κλείδωμα', 'escape' => false)));
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
