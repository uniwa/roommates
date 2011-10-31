<div id='leftbar'>
    <div class='left-form-cont'>

    <?php
        echo $this->Form->create('Profile', array('action' => 'search'));
        $defaults = $this->params['named'];
        $ageminoptions = array('label' => 'Ηλικία από ',
                               'class' => 'short-textbox',
                               'value' => (isset($defaults['age_min'])) ? $defaults['age_min'] : '',
                               'default' => '');
        $agemaxoptions = array('label' => 'μέχρι ',
                               'class' => 'short-textbox',
                               'value' => (isset($defaults['age_max'])) ? $defaults['age_max'] : '',
                               'default' => '');
        $genderoptions = array('Άνδρας', 'Γυναίκα', 'Αδιάφορο');
        $options = array('Όχι', 'Ναι', 'Αδιάφορο');
    ?>

        <div class='left-form'>
            <ul>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php echo $this->Form->input(('age_min'), array('label' => 'Ηλικία από ')); ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php echo $this->Form->input('age_max', array('label' => 'μέχρι ')); ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('pref_gender', array('label' => 'Φύλο ',
                                    'options' => $genderoptions,
                                    'value' => (isset($defaults['pref_gender']))
                                          ? $defaults['pref_gender']
                                          : '2',
                                    'default' => '2'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('pref_smoker', array('label' => 'Καπνιστής ',
                                'options' => $options,
                                'default' => '2',
                                'value' => (isset($defaults['pref_smoker'])) ? $defaults['pref_smoker'] : '2'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('pref_pet', array('label' => 'Κατοικίδιο ',
                                'options' => $options,
                                'default' => '2',
                                'value' => (isset($defaults['pref_pet'])) ? $defaults['pref_pet'] : '2'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('pref_child', array('label' => 'Παιδί ',
                                'options' => $options,
                                'default' => '2',
                                'value' => (isset($defaults['pref_child'])) ? $defaults['pref_child'] : '2'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('pref_couple', array('label' => 'Ζευγάρι ',
                                'options' => $options,
                                'default' => '2',
                                'value' => (isset($defaults['pref_couple'])) ? $defaults['pref_couple'] : '2'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->checkbox('has_house', array('value' => 1,
                                'checked' => isset($defaults['has_house']) ? $defaults['has_house'] : false, 'hiddenField' => false)) . ' Διαθέτει σπίτι';
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->submit('αναζήτηση', array('name' => 'simplesearch'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->submit('αποθήκευση', array('name' => 'savesearch'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->submit('φόρτωση προτιμήσεων', array('name' => 'searchbyprefs'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->submit('καθαρισμός', array('name' => 'resetvalues'));
                        ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<div id='main-inner'>
    <div class='results'>
        <?php
            if (isset($profiles)) {
        ?>
        <h2>Αποτελέσματα αναζήτησης</h2>
        <?php
            $foundmessage = "Δεν βρέθηκαν προφίλ";
            if (isset($profiles)) {
                $count = $this->Paginator->counter(array('format' => '%count%')); //count($profiles);
                if ($count > 0) {
                    $postfound = ($count == 1) ? 'ε ' : 'αν ';
                    $foundmessage = "Βρέθηκ" . $postfound . $count . " προφίλ\n";
                }
            }
            echo $foundmessage;
        ?>
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
            <?php
                foreach ($profiles as $profile){
                    $gender = ($profile['Profile']['gender']) ? 'fe' : '';
            ?>
            <li class='result-cont'>
                <div class='result'>
                    <div class='result-photo'>
                    </div>
                    <div class='result-desc'>
                        <div class='desc-title'>
                            <?php
                                echo $this->Html->link($profile['Profile']['firstname'] . ' ' . $profile['Profile']['lastname'],
                                    array('controller' => 'profiles', 'action' => 'view', $profile['Profile']['id']));
                            ?>
                        </div>
                        <div class='desc-info'>
                            <?php
                                echo $profile['Profile']['age'].", ";
                                $gender = ($profile['Profile']['gender']) ? 'γυναίκα' : 'άνδρας';
                                echo $gender."<br />\n";
	                            $email = $profile['Profile']['email'];
	                            $emailUrl = $this->Html->link($email, 'mailto:'.$email);
	                            echo "email: ".$emailUrl."<br />\n";
                                echo "επιθυμητοί συγκάτοικοι: " . $profile['Profile']['max_roommates']."\n";
                            ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php } // foreach $profiles?>
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
            } // isset($profiles)
        ?>
    </div>
</div>

