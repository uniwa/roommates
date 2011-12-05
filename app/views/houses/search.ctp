<?php
    echo $this->Html->script('search');
?>

<style>
    ul.collapsible{
        display: none;
    }

    .form-collapse{
        background-image: url('img/arrows.png');
        background-position: 98% 0px;
        background-repeat: no-repeat;
        height: 16px;
        cursor: pointer;
    }

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
        margin: 0px 0px 0px 0px;
        padding: 16px 0px 0px 16px;
        width: 320px;
    }

    #main-inner{
        float: left;
        border-left: 1px dotted #aaa;
        margin: 0px 0px 10px 2px;
        padding: 0px 0px 0px 0px;
        width: 620px;
        min-height: 800px;
        overflow: hidden;
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
        font-weight: bold;
    }

    .pagination ul li.disabled{
        color: #aaa;
    }

    h3 {
        font-size: 12px;
        font-style: italic;
        text-align: center;
        margin: -10px auto 24px;
    }

    .fbIcon{
        margin: 0px 4px 0px 0px;
        vertical-align: -30%;
    }

    .facebook-post{
        float: right;
        margin: 8px 12px 0px 0px;
    }

    .role{
        position: relative;
        top: -2px;
        left: 500px;
        margin: -2px 0px 0px 0px;
        width: 78px;
        height: 14px;
        color: #fff;
        font-size: 10px;
        font-weight: bold;
        text-align: center;
        text-shadow: #333 1px 1px 1px;
    }

    .student{
        background-color: #ddd;
    }

    .realestate{
        background-color: #88a;
    }

    .owner{
        background-color: #8a8;
    }

    .resultRE{
        border-color: #88a;
    }
</style>

<div id='leftbar'>
    <div class='left-form-cont'>

    <?php
        $select_options = array('Όχι', 'Ναι', 'Αδιάφορο');
        $gender_options = array('Άνδρας', 'Γυναίκα', 'Αδιάφορο');
        $role = $this->Session->read('Auth.User.role');
        //modify the url for pagination
        $get_vars = '';
        $urls = $this->params['url'];
        foreach($urls as $key => $value) {
            if($key == 'url' || $key == 'ext') continue;
	        if($key == 'available_from'){
		        foreach ($urls[$key]as $x => $y){
			        $get_vars .= urldecode($key.'['.$x.']').'='.$y.'&';
		        }
	        }else{
	                $get_vars .= urldecode($key).'='.urldecode($value).'&';
	        }
        }
        $get_vars = substr_replace($get_vars, '', -1, 'UTF-8'); // remove the last &

        echo $this->Form->create('House', array('action' => 'search', 'type' => 'get'));
    ?>

        <div class='left-form'>
            <ul>
                <li class='form-line form-buttons'>
                    <div class='form-elem form-submit'>
                        <?php
                            echo $this->Form->submit('αναζήτηση', array('name' => 'search', 'class' => 'button'));
                        ?>
                    </div>
                    <div class='form-elem form-submit'>
                        <?php
                            echo $this->Form->submit('καθαρισμός', array('name' => 'clear', 'class' => 'button'));
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
                                'options' => $municipality_options,
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
                                array('hiddenField' => false,
                                    'checked' => isset($defaults['accessibility']))).' Προσβάσιμο από ΑΜΕΑ';
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->checkbox('has_photo',
                                array('hiddenField' => false,
                                    'checked' => isset($defaults['has_photo']))).' Διαθέτει φωτογραφία';
                        ?>
                    </div>
                </li>

            <?php if ($this->Session->read('Auth.User.role') != 'realestate') {?>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->checkbox('with_roommate',
                                array('hiddenField' => false,
                                    'checked' => isset($defaults['with_roommate']))).' Μόνο σπίτια με συγκάτοικο';
                        ?>
                    </div>
                </li>
            <?php } ?>

            </ul>

            <?php if ($this->Session->read('Auth.User.role') != 'realestate') {?>

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

                <?php } // role != realestate ?>

            </ul>

            <?php if ($this->Session->read('Auth.User.role') != 'realestate') {?>

            <div class='form-title'>
                <h2>Οι προτιμήσεις μου</h2>
            </div>
            <ul>
                <li class='form-line form-buttons'>
                    <div class='form-elem form-submit'>
                        <?php
                            echo $this->Form->submit('αποθήκευση', array('name' => 'save', 'class' => 'button'));
                        ?>
                    </div>
                    <div class='form-elem form-submit'>
                        <?php
                            echo $this->Form->submit('φόρτωση', array('name' => 'load', 'class' => 'button'));
                        ?>
                    </div>
                </li>
            </ul>

            <?php } // role != realestate ?>

            <div class='form-title form-collapse expand'>
                <h2>Πρόσθετα χαρακτηριστικά σπιτιών</h2>
            </div>
            <ul class='collapsible'>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Τύπος σπιτιού
                    </div>
                    <div class='form-elem form-input'>
                        <?php
                            echo $this->Form->input('house_type', array('label' => '',
                                'options' => $house_type_options,
                                'value' => isset($defaults['house_type']) ? $defaults['house_type'] : '',
                                'empty' => 'Αδιάφορο' ));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Είδος θέρμανσης
                    </div>
                    <div class='form-elem form-input'>
                        <?php
	                        echo $this->Form->input('heating_type', array('label' => '',
                                'options' => $heating_type_options,
                                'value' => isset($defaults['heating_type']) ? $defaults['heating_type'] : '',
                                'empty' => 'Αδιάφορο' ));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Ελάχιστος αριθμός υπνοδωματίων
                    </div>
                    <div class='form-elem form-input'>
                        <?php
	                        echo $this->Form->input('bedroom_num_min', array('label' => '',
                                'class' => 'short-textbox',
                                'value' => isset($defaults['bedroom_num_min']) ? $defaults['bedroom_num_min'] : '',
                                'class' => 'input-elem'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Ελάχιστος αριθμός μπάνιων
                    </div>
                    <div class='form-elem form-input'>
                        <?php
	                        echo $this->Form->input('bathroom_num_min', array('label' => '',
                                'class' => 'short-textbox',
                                'value' => isset($defaults['bathroom_num_min']) ? $defaults['bathroom_num_min'] : '',
                                'class' => 'input-elem'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem'>
                        Ημερομηνία διαθεσιμότητας μέχρι
                    </div>
                <li class='form-line'>
                    <div class='form-elem'>
                        <?php
                            if ( isset( $defaults['available_from'] ) ) {
                                $selected_date = $defaults[ 'available_from' ];
                            }
                            else {
                                // TODO fix hardcoded date
                                $selected_date = strtotime('31-12-2016');
                            }
                            // next line is necessary for the search function
                            // to work properly
                            $this->field = 'available_from';
                            echo $this->Form->dateTime('available_from',
                                'DMY', null, $selected_date, array('minYear' => date('Y'),
                                'maxYear' => date('Y') + 5,
                                'empty' => false));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Ελάχιστη περίοδος ενοικίασης
                    </div>
                    <div class='form-elem form-input'>
                        <?php
	                        echo $this->Form->input('rent_period_min', array('label' => '',
                                'class' => 'short-textbox',
                                'value' => isset($defaults['rent_period_min']) ? $defaults['rent_period_min'] : '',
                                'class' => 'input-elem'));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Όροφος από
                    </div>
                    <div class='form-elem form-input'>
                        <?php
	                        echo $this->Form->input('floor_min', array('label' => '',
                                'options' => $floor_options,
                                'value' => isset($defaults['floor_min']) ? $defaults['floor_min'] : '',
                                'empty' => 'Αδιάφορο' ));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-label'>
                        Έτος κατασκευής από
                    </div>
                    <div class='form-elem form-input'>
                        <?php
	                        echo $this->Form->input('construction_year_min', array('label' => '',
                                'options' => $construction_year_options,
                                'value' => isset($defaults['construction_year_min']) ? $defaults['construction_year_min'] : '',
                                'empty' => 'Αδιάφορο' ));
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
	                        echo $this->Form->checkbox('solar_heater', array('hiddenField' => false,
                                'checked' => isset($defaults['solar_heater']))).' Ηλιακός θερμοσίφωνας';
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
	                        echo $this->Form->checkbox('aircondition', array('hiddenField' => false,
                                'checked' => isset($defaults['aircondition']))).' Κλιματισμός';
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
	                        echo $this->Form->checkbox('garden', array('hiddenField' => false,
                                'checked' => isset($defaults['garden']))).' Κήπος';
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
	                        echo $this->Form->checkbox('parking', array('hiddenField' => false,
                                'checked' => isset($defaults['parking']))).' Θέση πάρκινγκ';
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
	                        echo $this->Form->checkbox('no_shared_pay', array('hiddenField' => false,
                                'checked' => isset($defaults['no_shared_pay']))).' Χωρίς κοινόχρηστα';
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
	                        echo $this->Form->checkbox('security_doors', array('hiddenField' => false,
                                'checked' => isset($defaults['security_doors']))).' Πόρτα ασφαλείας';
                        ?>
                    </div>
                </li>
                <li class='form-line'>
                    <div class='form-elem form-input'>
                        <?php
	                        echo $this->Form->checkbox('storeroom', array('hiddenField' => false,
                                'checked' => isset($defaults['storeroom']))).' Αποθήκη';
                        ?>
                    </div>
                </li>
                <li class='form-line form-buttons'>
                    <div class='form-elem form-submit'>
                        <?php
                            echo $this->Form->submit('αναζήτηση', array('name' => 'search', 'class' => 'button'));
                        ?>
                    </div>
                    <div class='form-elem form-submit'>
                        <?php
                            echo $this->Form->submit('καθαρισμός', array('name' => 'clear', 'class' => 'button'));
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
    <div id='results'>
        <?php if(isset($results)){ ?>
        <div class='search-title'>
            <h2>Αποτελέσματα αναζήτησης</h2>
        </div>
        <?php
            $count = $this->Paginator->counter(array('format' => '%count%'));
            if ($count === '0') {
                $foundmessage = 'Δεν βρέθηκαν σπίτια';
            } else if ($count === '1') {
                $foundmessage = 'Βρέθηκε 1 σπίτι';
            } else {
                $foundmessage = 'Βρέθηκαν '.$count.' σπίτια';
            }
        ?>
        <div class='search-subtitle'>
            <?php echo $foundmessage; ?>
        </div>
        <?php if (isset($extra_results) && !isset($this->params['url']['extra'])) {
            echo "<h3>";
            echo "Επίσης βρέθηκαν ορισμένα σπίτια χωρίς συγκάτοικο,<br/>που ίσως να σας ενδιαφέρουν.";
            echo $this->Html->link(" Περισσότερα...",
                                   array('controller' => 'houses',
                                         'action' => 'search',
                                         '?' => $get_vars.'extra=1'));
            echo "</h3>";
         } ?>
        <div class="pagination">
            <ul>
                <?php
                if ($count > $pagination_limit) {
                    // set the URL
                    $paginator->options(array('url' => array('?' => $get_vars)));
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
                foreach($results as $house){
                    $role = $house['User']['role'];
                    $resultClass = 'result-cont';
                    // TODO: switch for realestate, student, owner
                    if($role == 'realestate'){
                        $resultClass .= ' resultRE';
                        $roleClass = 'realestate';
                        $roleTitle = 'μεσιτικό';
                    }else{
                        $roleClass = 'student';
                        $roleTitle = 'φοιτητής';
                    }
                echo "<li class='{$resultClass}'>";
                echo "<div class='result'>";
                echo "<div class='role {$roleClass}'>{$roleTitle}</div>";
            ?>
                    <div class='result-photo'>
                    <div class='result-photo-wrap'>
                    <div class='result-photo-cont'>
                    <div class='result-photo-inner'>
                        <?php
							// thumbnail icon if found
							$house_id = $house['House']['id'];
							$house_image = 'home.png';
                            if(!empty($house['Image'][0]['location'])) {
                                $house_image = 'uploads/houses/'.$house_id.'/thumb_'.$house['Image'][0]['location'];
                            }
                            $altText = 'εικόνα '.$house['House']['address'];
							$houseImage = $this->Html->image($house_image,
							    array('alt' => $altText));
							echo $this->Html->link($houseImage, array(
							    'controller' => 'houses',
							    'action' => 'view', $house['House']['id']),
							    array('title' => $altText, 'escape' => false));
						?>
                    </div>
                    </div>
                    </div>
                    </div>
                    <div class='result-desc'>
                        <?php
                            $furnished = $house['House']['furnitured'] ? 'Επιπλωμένο' : 'Μη επιπλωμένο';
                            $houseid = $house['House']['id'];
                            $housePrice = $house['House']['price'];
                            $houseMunicipality = $municipality_options[$house['House']['municipality_id']];
                            $houseType = $house_types[$house['House']['house_type_id']];
                            $houseArea = $house['House']['area'];
                            $houseTypeArea = $houseType.', '.$houseArea.' τ.μ.';
                            $geoDistance = $house['House']['geo_distance'];
                            // allow posts to Facebook only by a 'user' (as in role)
                            if($this->Session->read('Auth.User.role') == 'user'){
                                $this_url = substr($get_vars, 0, -1); //replace last character (ampersand)
                                $occupation_availability = null;
                                if($house['User']['role'] != 'user') {
                                    $occupation_availability = '';
                                }else{
                                    $occupation_availability = ', Διαθέσιμες θέσεις '
                                        .Sanitize::html($house['House']['free_places']);
                                }
                                $fbUrl = "http://www.facebook.com/dialog/feed";
                                $fbUrl .= "?app_id=".$facebook->getAppId();
                                $fbUrl .= "&name=".urlencode('Δείτε περισσότερα εδώ...');
                                $fbUrl .= "&link={$fb_app_uri}houses/view/{$houseid}";
                                $fbUrl .= "&caption=".urlencode('«Συγκατοικώ»');
                                $fbUrl .= "&description=".urlencode($houseTypeArea.'Ενοικίο '.$housePrice.' €,'
                                    .$furnished.', Δήμος '.$houseMunicipality.$occupation_availability);
                                $fbUrl .= "&redirect_uri={$fb_app_uri}houses/view/{$houseid}";
                                $fbImage = 'facebook.png';
                                $fbDisplay = $this->Html->image($fbImage, array(
                                    'alt' => 'Κοινoποίηση στο Facebook',
                                    'class' => 'fbIcon'))
                                    ." Post";
                                $fbLink = $this->Html->link($fbDisplay, $fbUrl,array(
                                    'title' => 'κοινοποίηση στο facebook', 'escape' => false,
                                    'target' => 'post_to_facebook'));
                                $fbPost = "<div class='facebook-post'>{$fbLink}</div>";
                                echo $fbPost;
                            }
                        ?>
                        <div class='desc-title houseClear'>
                            <?php
                                echo $this->Html->link("{$houseType}, {$houseArea} τ.μ.",
                                    array('controller' => 'houses','action' => 'view',$houseid));
                            ?>
                        </div>
                        <div class='desc-info'>
                            <?php
                                echo 'Ενοίκιο: '.$housePrice.'€, ';
                                echo $furnished;
                                echo '<br />Δήμος: '.$houseMunicipality.'<br />';
                                //echo 'Διεύθυνση '.$house['House']['address'].'<br />';
                                if($house['House']['disability_facilities']) echo 'Προσβάσιμο από ΑΜΕΑ<br />';
                                if ($house['User']['role'] != 'realestate') {
                                    echo 'Διαθέσιμες θέσεις: '.
                                        $house['House']['free_places'].'<br />';
                                }
                                if( !empty($geoDistance) ) {
                                    echo 'Απόσταση από ΤΕΙ: '
                                        . number_format( $geoDistance, 2 )
                                        . '&nbsp;χλμ.';
                                }
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
            } // isset($results)
        ?>
    </div>
</div>

