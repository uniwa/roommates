<div id='leftbar' class='leftSearch'>
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
                <li class='form-line form-buttons'>
                    <div class='form-elem form-submit'>
                        <?php
                            echo $this->Form->submit('αναζήτηση', array('name' => 'simplesearch', 'class' => 'button'));
                        ?>
                    </div>
                    <div class='form-elem form-submit'>
                        <?php
                            echo $this->Form->submit('καθαρισμός', array('name' => 'resetvalues', 'class' => 'button'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Ταξινόμηση
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('orderby',
                                array('label' => '',
                                'options' => $order_options['options'],
                                'selected' => isset($order_options['selected'])
                                    ?$order_options['selected']:'0',
                                'class' => 'input-elem'));
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
                        <?php echo $this->Form->input('age_min', array('label' => '',
                                'value' => isset($defaults['age_min'])
                                                ? $defaults['age_min']
                                                : '',
                                'class' => 'input-elem')); ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        μέχρι
                    </div>
                    <div class='form-elem form-input'>
                        <?php echo $this->Form->input('age_max', array('label' => '',
                                'value' => isset($defaults['age_max'])
                                                ? $defaults['age_max']
                                                : '',
                                'class' => 'input-elem')); ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Φύλο
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('pref_gender', array('label' => '',
                                'options' => $genderoptions,
                                'value' => (isset($defaults['pref_gender']))
                                      ? $defaults['pref_gender']
                                      : '2',
                                'default' => '2',
                                'class' => 'input-elem'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Καπνιστής
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('pref_smoker', array('label' => '',
                                'options' => $options,
                                'default' => '2',
                                'value' => (isset($defaults['pref_smoker'])) ? $defaults['pref_smoker'] : '2',
                                'class' => 'input-elem'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Κατοικίδιο
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('pref_pet', array('label' => '',
                                'options' => $options,
                                'default' => '2',
                                'value' => (isset($defaults['pref_pet'])) ? $defaults['pref_pet'] : '2',
                                'class' => 'input-elem'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Παιδί
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('pref_child', array('label' => '',
                                'options' => $options,
                                'default' => '2',
                                'value' => (isset($defaults['pref_child'])) ? $defaults['pref_child'] : '2',
                                'class' => 'input-elem'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Ζευγάρι
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('pref_couple', array('label' => '',
                                'options' => $options,
                                'default' => '2',
                                'value' => (isset($defaults['pref_couple'])) ? $defaults['pref_couple'] : '2',
                                'class' => 'input-elem'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input form-checkbox'>
                        <?php
                            echo $this->Form->checkbox('has_house', array('value' => 1,
                                'class' => 'input-elem',
                                'checked' => isset($defaults['has_house']) ? $defaults['has_house'] :
                                    false, 'hiddenField' => false)).' Διαθέτει σπίτι';
                        ?>
                    </div>
                </li>
            </ul>

            <?php if ($this->Session->read('Auth.User.role') != 'admin') { ?>

            <div class='form-title'>
                <h2>Οι προτιμήσεις μου</h2>
            </div>
            <ul>
                <li class='form-line form-buttons'>
                    <div class='form-elem form-submit'>
                        <?php
                            echo $this->Form->submit('αποθήκευση', array('name' => 'savesearch', 'class' => 'button'));
                        ?>
                    </div>
                    <div class='form-elem form-submit'>
                        <?php
                            echo $this->Form->submit('φόρτωση', array('name' => 'searchbyprefs', 'class' => 'button'));
                        ?>
                    </div>
                </li>
            </ul>

            <?php } // User.role != 'admin' ?>

        </div>
    </div>
</div>
<div id='main-inner' class='mainSearch'>
    <div id='results'>
        <?php
            if (isset($profiles)) {
        ?>
        <div class='search-title'>
            <h2>Αποτελέσματα αναζήτησης</h2>
        </div>
        <?php
            $foundmessage = "Δεν βρέθηκαν προφίλ";
            if (isset($profiles)) {
                $count = $this->Paginator->counter(array('format' => '%count%')); //count($profiles);
                if ($count > 0) {
                    $postfound = ($count == 1) ? 'ε ' : 'αν ';
                    $foundmessage = "Βρέθηκ" . $postfound . $count . " προφίλ\n";
                }
            }
        ?>
        <div class='search-subtitle'>
            <?php echo $foundmessage; ?>
        </div>
        <div class="pagination">
            <ul>
                <?php
                if ($count > $pagination_limit) {
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
                }
                ?>
            </ul>
        </div>
        <ul>
            <?php
                foreach ($profiles as $profile){
                    $result_class = 'result-cont';
                    $gender = ($profile['Profile']['gender']) ? 'fe' : '';

                    if ($this->Session->read('Auth.User.id') == $profile['Profile']['user_id']) {
                        $result_class .= ' result-myself';
                    }

                    echo "<li class='{$result_class}'>";
            ?>
            <!-- li class='result-cont' -->
                <div class='result'>
                    <div class='result-photo'>
                    <div class='result-photo-wrap'>
                    <div class='result-photo-cont'>
                    <div class='result-photo-inner'>
                        <?php
                            $profile_id = $profile['Profile']['id'];
                            if (empty($profile['Profile']['avatar'])) {
    							$imageLocation = ($profile['Profile']['gender'])?'female.jpg':'male.jpg';
                            } else {
                                $imageLocation = 'uploads/profiles/'.$profile_id.'/'.$profile['Profile']['avatar'];
                            }
                            $altText = "εικόνα {$profile['Profile']['firstname']} ";
                            $altText .= $profile['Profile']['lastname'];
							$profileImage = $this->Html->image($imageLocation,
							    array('alt' => $altText, 'class' => 'thumbImage'));
							echo $this->Html->link($profileImage, array(
							    'controller' => 'profiles',
							    'action' => 'view', $profile_id),
							    array('title' => $altText, 'escape' => false));
						?>
                    </div>
                    </div>
                    </div>
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
                                echo "<span class='bold'>{$gender}</span><br />";
	                            $email = $profile['Profile']['email'];
	                            $emailUrl = $this->Html->link($email, 'mailto:'.$email);
	                            echo "<span class='bold'>email:</span> {$emailUrl}<br />";
                                echo "<span class='bold'>επιθυμητοί συγκάτοικοι:</span> {$profile['Profile']['max_roommates']}";
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
                if ($count > $pagination_limit) {
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
                }
                ?>
            </ul>
        </div>
        <?php
            } // isset($profiles)
        ?>
    </div>
</div>

