<style>
    .form-title{
        clear: both;
        margin: 20px 0px 12px 8px;
        font-size: 1.2em;
        font-weight: bold;
    }
    
    .left-form ul{
        margin: 0px 0px 20px 0px;
    }
    
    #leftbar{
        float: left;
        background-color: #eaeaea;
        margin: 0px 0px 0px 0px;
        padding: 0px 0px 0px 0px;
        width: 320px;
    }

    #main-inner{
        float: left;
        background-color: #eaeaea;
        margin: 0px 0px 0px 2px;
        padding: 0px 0px 0px 0px;
        width: 620px;
    }
    
    .form-buttons{
        margin: 10px auto;
        width: 220px;
    }
    
    .form-line{
/*        margin: 0px 12px 0px 0px;*/
    }
    
    .form-elem{
        margin: 2px 0px 12px 8px;
        font-size: 1.2em;
    }

    .form-label{
        float: left;
        width: 80px;
/*        margin: 2px 4px 2px 0px;*/
    }

    .form-input{
        float: left;
        width: 200px;
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
        margin: 12px auto 0px auto;
        text-align: center;
        font-size: 1.2em;
        font-weight: bold;
    }

    .search-subtitle{
/*        margin: 0px 0px 12px 64px;*/
        margin: 8px auto 24px auto;
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
    }
    
    .pagination ul li.disabled{
        color: #aaa;
    }
</style>

<div id='leftbar'>
    <div class='left-form-cont'>

    <?php
        $select_options = array('Όχι', 'Ναι', 'Αδιάφορο');
        $gender_options = array('Άνδρας', 'Γυναίκα', 'Αδιάφορο');
        $min_age_options = array(   'label' => '',
                                    'class' => '',
                                    'value' => isset($defaults['min_age']) ? $defaults['min_age'] : '');
        $max_age_options = array(   'label' => '',
                                    'class' => '',
                                    'value' => isset($defaults['max_age']) ? $defaults['max_age'] : '');

        // modify the URL for pagination
        $get_vars = '';
        $urls = $this->params['url'];
        foreach($urls as $key => $value) {
            if($key == 'url' || $key == 'ext') continue;

            $get_vars .= urldecode($key).'='.urldecode($value).'&';
        }
        $get_vars = substr_replace($get_vars, '', -1); // remove the last &

        echo $this->Form->create('House', array('action' => 'search', 'type' => 'get'));
    ?>

        <div class='left-form'>
            <div class='form-title'>
                <h2>Χαρακτηριστικά σπιτιών</h2>
            </div>
            <ul>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Ενοίκιο μέχρι
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('max_price', array('label' => '',
                                'value' => isset($defaults['max_price']) ? $defaults['max_price'] : '',
                                'class' => 'input-elem'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Εμβαδόν από
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('min_area', array('label' => '',
                                'value' => isset($defaults['min_area']) ? $defaults['min_area'] : '',
                                'class' => 'input-elem'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        μέχρι
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('max_area', array('label' => '',
                                'value' => isset($defaults['max_area']) ? $defaults['max_area'] : '',
                                'class' => 'input-elem'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Δήμος
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('municipality', array('label' => '',
                                'options' => $municipalities,
                                'value' => isset($defaults['municipality']) ? $defaults['municipality'] : '',
                                'empty' => 'Αδιάφορο',
                                'class' => 'input-elem'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Επιπλωμένο
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('furnitured', array('label' => '',
                                'options' => array( 'Όχι','Ναι','Αδιάφορο'),
                                'value' => isset($defaults['furnitured']) ? $defaults['furnitured'] : '2',
                                'class' => 'input-elem'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->checkbox('accessibility',
                                array(  'hiddenField' => false,
                                        'checked' => isset($defaults['accessibility'])))
                                        .' Προσβάσιμο από ΑΜΕΑ';
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->checkbox('has_photo',
                                array('hiddenField' => false, 'checked' => isset($defaults['has_photo'])))
                                .' Διαθέτει φωτογραφία';
                        ?>
                    </div>
                </li>
            </ul>
            <div class='form-title'>
                <h2>Χαρακτηριστικά συγκατοίκων</h2>
            </div>
            <ul>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Ηλικία από
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('min_age', array('label' => '',
                                'value' => isset($defaults['min_age']) ? $defaults['min_age'] : '',
                                'class' => 'input-elem'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        μέχρι
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('max_age', array('label' => '',
                                'value' => isset($defaults['max_age']) ? $defaults['max_age'] : '',
                                'class' => 'input-elem'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Φύλο
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('gender', array(   'label' => '',
                                'options' => $gender_options,
                                'value' => isset($defaults['gender']) ? $defaults['gender'] : '2'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Καπνιστής
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('smoker', array('label' => '',
                                'options' => $select_options,
                                'value' => isset($defaults['smoker']) ? $defaults['smoker'] : '2'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Κατοικίδιο
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('pet', array('label' => '',
                                'options' => $select_options,
                                'value' => isset($defaults['pet']) ? $defaults['pet'] : '2'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Παιδί
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('child', array('label' => '',
                                'options' => $select_options,
                                'value' => isset($defaults['child']) ? $defaults['child'] : '2'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Ζευγάρι
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('couple', array('label' => '',
                                'options' => $select_options,
                                'value' => isset($defaults['couple']) ? $defaults['couple'] : '2'));
                        ?>
                    </div>
                </li>
                <li class='form-line form-buttons'>
                    <div class='form-elem form-submit'>
                        <?php
                            echo $this->Form->submit('αναζήτηση', array('name' => 'simple_search', 'class' => 'button'));
                        ?>
                    </div>
                    <div class='form-elem form-submit'>
                        <?php
                            echo $this->Form->submit('καθαρισμός', array('name' => 'reset_fields', 'class' => 'button'));
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
            </ul>
            <div class='form-title'>
                <h2>Οι προτιμήσεις μου</h2>
            </div>
            <ul>
                <li class='form-line form-buttons'>
                    <div class='form-elem form-submit'>
                        <?php
                            echo $this->Form->submit('αποθήκευση', array('name' => 'save_search', 'class' => 'button'));
                        ?>
                    </div>
                    <div class='form-elem form-submit'>
                        <?php
                            echo $this->Form->submit('φόρτωση', array('name' => 'load_prefs', 'class' => 'button'));
                        ?>
                    </div>
                </li>
            </ul>
        </div>
        <?php
            echo $this->Form->end();
        ?>
    </div>
</div>
<div id='main-inner'>
    <div class='results'>
        <?php if(isset($results)){ ?>
        <div class='search-title'>
            <h2>Αποτελέσματα αναζήτησης</h2>
        </div>
        <?php
            $count = $this->Paginator->counter(array('format' => '%count%'));
            $foundmessage = 'Δεν βρέθηκαν σπίτια';
            if($count == 1) {
                $foundmessage = 'Βρέθηκε '.$count.' σπίτι';
            }else{
                $foundmessage = 'Βρέθηκαν '.$count.' σπίτια';
            }
        ?>
        <div class='search-subtitle'>
            <?php echo $foundmessage; ?>
        </div>
        <div class="pagination">
            <ul>
                <?php
                    /* show first page */
                    //echo $paginator->first('⇤ Πρώτη ');
                    /* show the previous link */
                    echo $paginator->prev('« Προηγούμενη ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                    /* show pages */
                    echo $paginator->numbers(array('first' => 3, 'last' => 3, 'modulus' => '4', 'separator' => ' ', 'tag' => 'li'));
                    /* Shows the next link */
                    echo $paginator->next(' Επόμενη » ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                    /* show last page */
                    //echo $paginator->last('Τελευτευταία ⇥');
                    /* prints X of Y, where X is current page and Y is number of pages */
                    //echo " Σελίδα ".$paginator->counter(array('separator' => ' από '));
                    ?>
            </ul>
        </div>
        <ul>
            <?php foreach($results as $house){ ?>
            <li class='result-cont'>
                <div class='result'>
                    <div class='result-photo'>
                        <?php
							// thumbnail icon if found
							$house_id = $house['House']['id'];
							$house_image = 'house.gif';
                            if(!empty($house['Image']['location'])) {
                                $house_image = 'uploads/houses/'.$house_id.'/thumb_'.$house['Image']['location'];
                            }
							echo $this->Html->image($house_image, array('alt' => 'εικόνα '.$house['House']['address']));
						?>
                    </div>
                    <div class='result-desc'>
                        <div class='desc-title'>
                            <?php
                                echo $this->Html->link($house['House']['address'],
                                    array('controller' => 'houses','action' => 'view',$house['House']['id']));
                            ?>
                        </div>
                        <div class='desc-info'>
                            <?php
                                echo 'Ενοίκιο '.$house['House']['price'].'€, Εμβαδόν '.$house['House']['area'].' τ.μ. ';
                                echo $house['House']['furnitured'] ? 'Επιπλωμένο' : 'Μη επιπλωμένο';
                                echo '<br />Δήμος '.$municipalities[$house['House']['municipality_id']].'<br />';
                                if($house['House']['disability_facilities']) echo 'Προσβάσιμο από ΑΜΕΑ<br />';
                                echo 'Διαθέσιμες θέσεις '.$house['House']['free_places'].'<br />';
                            ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php } // foreach $results ?>
        </ul>
        <div class="pagination">
            <ul>
                <?php
                    /* show first page */
                    //echo $paginator->first('⇤ Πρώτη ');
                    /* show the previous link */
                    echo $paginator->prev('« Προηγούμενη ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                    /* show pages */
                    echo $paginator->numbers(array('first' => 3, 'last' => 3, 'modulus' => '4', 'separator' => ' ', 'tag' => 'li'));
                    /* Shows the next link */
                    echo $paginator->next(' Επόμενη » ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                    /* show last page */
                    //echo $paginator->last('Τελευτευταία ⇥');
                    /* prints X of Y, where X is current page and Y is number of pages */
                    //echo " Σελίδα ".$paginator->counter(array('separator' => ' από '));
                ?>
            </ul>
        </div>
        <?php
            } // isset($results)
        ?>
    </div>
</div>

