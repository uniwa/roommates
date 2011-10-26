<div id='top-frame' class='frame'>
    <div class='frame-container'>
        <div id='top-title' class='title'>
            <h1>Σύνθετη Αναζήτηση</h1>
        </div>

<div class='clear-both'></div>

<?php
    //pr($defaults);die();
    $select_options = array('Όχι', 'Ναι', 'Αδιάφορο');
    $gender_options = array('Άνδρας', 'Γυναίκα', 'Αδιάφορο');

    //modify the url for pagination    
    $get_vars = '';
    $urls = $this->params['url'];
    //pr($urls);die();
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
    //pr($get_vars);die();

    echo $this->Form->create('House', array('action' => 'advanced_search', 'type' => 'get'));

?>

<h2>Χαρακτηριστικά σπιτιού</h2><br/>

<table>
    <tr>
    </tr>
        <td>
            <?php echo $this->Form->input('max_price', array('label' => 'Ενοίκιο μέχρι ',
                                                             'class' => 'short-textbox',
                                                             'value' => isset($defaults) ? $defaults['max_price'] : '' ));
            ?>
        </td>
        <td>
            <?php echo $this->Form->input('min_area', array('label' => 'Εμβαδόν από ',
                                                            'class' => 'short-textbox',
                                                            'value' => isset($defaults) ? $defaults['min_area'] : '' ));
            ?>
        </td>
        <td>
            <?php echo $this->Form->input('max_area', array('label' => ' μέχρι ',
                                                            'class' => 'short-textbox',
                                                            'value' => isset($defaults) ? $defaults['max_area'] : '' ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=2>
            <?php echo $this->Form->input('municipality', array('label' => 'Δήμος ',
                                                                'options' => $municipality_options,
                                                                'value' => isset($defaults) ? $defaults['municipality'] : '',
                                                                'empty' => 'Αδιάφορο'  ));
            ?>
        </td>
        <td>
            <?php   echo $this->Form->input('furnitured', array('label' => 'Επιπλωμένο ',
                                                                'options' => $select_options,
                                                                'value' => isset($defaults) ? $defaults['furnitured'] : '2'   ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=2>
            <?php echo 'Προσβάσιμο από ΑΜΕΑ '.$this->Form->checkbox('accessibility',
                                                                    array(  'hiddenField' => false,
                                                                            'checked' => isset($defaults['accessibility'])  ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=3>
            <?php echo $this->Form->input('order_by', array('label' => 'Ταξινόμηση ανά: ',
                                                            'options' => $search_order_options,
                                                            'selected' => isset($defaults) ? $defaults['order_by'] : '0'   ));
            ?>
        </td>
    </tr>
</table>


<br/><h2>Προχωρημένα χαρακτηριστικά σπιτιού</h2><br/>

<table>
    <tr>
        <td colspan=8>
            <?php echo $this->Form->input('house_type', array('label' => 'Τύπος κατοικίας ',
							         'options' => $house_type_options,
                                                                 'value' => isset($defaults) ? $defaults['house_type'] : '',
								 'empty' => 'Αδιάφορο' ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=8>
            <?php echo $this->Form->input('heating_type', array('label' => 'Είδος θέρμανσης ',
                                                              'options' => $heating_type_options,
                                                            	'value' => isset($defaults) ? $defaults['heating_type'] : '',
								'empty' => 'Αδιάφορο' ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=8>
            <?php echo $this->Form->input('bedroom_num', array('label' => 'Ελάχιστος αριθμός υπνοδωματίων',
                                                           	   'class' => 'short-textbox',
                                                            	   'value' => isset($defaults) ? $defaults['bedroom_num'] : '' ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=8>
            <?php echo $this->Form->input('bathroom_num', array('label' => 'Ελάχιστος αριθμός μπάνιων',
                                                           	    'class' => 'short-textbox',
                                                            	    'value' => isset($defaults) ? $defaults['bathroom_num'] : '' ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=8>
            <?php 
		if ( isset( $defaults ) ) {
			//pr($defaults);
			$selected_date = $defaults[ 'available_from' ]; 
		}
		else {
			//$selected_date = null;
            $selected_date = strtotime('31-12-2016');
		}
		$this->field = 'available_from';
		echo 'Ημερομηνία διαθεσιμότητας μέχρι ' . $this->Form->dateTime('available_from', 'DMY', null, $selected_date, array('minYear' => date('Y'),
													    		             'maxYear' => date('Y') + 5,	
																     'empty' => false));
	    ?>
        </td>
    </tr>
    <tr>
        <td colspan=8>
            <?php echo $this->Form->input('rent_period', array('label' => 'Ελάχιστη περίοδος ενοικίασης ',
                                                               'class' => 'short-textbox',
                                                               'value' => isset($defaults) ? $defaults['rent_period'] : ''));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=8>
            <?php echo $this->Form->input('floor', array('label' => 'Όροφος από ',
							 'options' => $floor_options,
                                                         'value' => isset($defaults) ? $defaults['floor'] : '',
							 'empty' => 'Αδιάφορο' ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=8>
            <?php echo $this->Form->input('construction_year', array('label' => 'Έτος κατασκευής από ',
                                                                     'options' => $construction_year_options,
                                                                     'value' => isset($defaults) ? $defaults['construction_year'] : '',
								     'empty' => 'Αδιάφορο' ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=8>
            <?php echo 'Ηλιακός θερμοσίφωνας '.$this->Form->checkbox('solar_heater', array('hiddenField' => false,
                                                                            		   'checked' => isset($defaults['solar_heater'])  ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=8>
            <?php echo 'Κλιματισμός '.$this->Form->checkbox('aircondition', array('hiddenField' => false,
                                                                            	  'checked' => isset($defaults['aircondition'])  ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=8>
            <?php echo 'Κήπος '.$this->Form->checkbox('garden', array('hiddenField' => false,
                                                                      'checked' => isset($defaults['garden'])  ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=8>
            <?php echo 'Θέση πάρκινγκ '.$this->Form->checkbox('parking', array('hiddenField' => false,
                                                                               'checked' => isset($defaults['parking'])  ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=8>
            <?php echo 'Χωρίς κοινόχρηστα '.$this->Form->checkbox('no_shared_pay', array('hiddenField' => false,
                                                                            		 'checked' => isset($defaults['no_shared_pay'])  ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=8>
            <?php echo 'Πόρτα ασφαλείας '.$this->Form->checkbox('security_doors', array('hiddenField' => false,
                                                                          		'checked' => isset($defaults['security_doors'])  ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=8>
            <?php echo 'Αποθήκη '.$this->Form->checkbox('storeroom', array('hiddenField' => false,
                                                                           'checked' => isset($defaults['storeroom'])  ));
            ?>
        </td>
    </tr>
</table>


<br/><h2>Χαρακτηριστικά συγκατοίκων</h2><br/>

<table>
    <tr>
        <td>
            <?php echo $this->Form->input('min_age', array('label' => 'Ηλικία από ',
                                			   'class' => 'short-textbox',
                                			   'value' => isset($defaults) ? $defaults['min_age'] : ''));
	    ?>
        </td>
        <td>
            <?php echo $this->Form->input('max_age', array('label' => 'μέχρι ',
                                	  		   'class' => 'short-textbox',
                                	  		   'value' => isset($defaults) ? $defaults['max_age'] : ''));
	    ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $this->Form->input('gender', array('label' => 'Φύλο ',
                                                                     'options' => $gender_options,
                                                                     'value' => isset($defaults) ? $defaults['gender'] : '2'    ));
            ?>
        </td>
        <td>
            <?php echo $this->Form->input('smoker', array('label' => 'Καπνιστής ',
                                                          'options' => $select_options,
                                                          'value' => isset($defaults) ? $defaults['smoker'] : '2'    ));
            ?>
        </td>
        <td>
            <?php echo $this->Form->input('pet', array('label' => 'Κατοικίδιο ',
                                                       'options' => $select_options,
                                                       'value' => isset($defaults) ? $defaults['pet'] : '2'    ));
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $this->Form->input('child', array('label' => 'Παιδί ',
                                                         'options' => $select_options,
                                                         'value' => isset($defaults) ? $defaults['child'] : '2'    ));
            ?>
        </td>
        <td>
            <?php echo $this->Form->input('couple', array('label' => 'Ζευγάρι ',
                                                          'options' => $select_options,
                                                          'value' => isset($defaults) ? $defaults['couple'] : '2'    ));
            ?>
        </td>
    </tr>
</table>

<br/>

<table class="tableactions">
    <tr>
        <td>
            <?php echo $this->Form->submit('αναζήτηση', array('name' => 'advanced_search'));?>
        </td>
        <td>
            <?php echo $this->Form->submit('καθαρισμός πεδίων', array('name' => 'reset_fields'));?>
        </td>
    </tr>
</table>

<?php
    echo $this->Form->end();
    if(isset($results)){
?>
</div></div>

<div id='bottom-frame' class='frame'>
    <div class='frame-container'>
        <div id='bottom-title' class='title'>
            <h2>Αποτελέσματα αναζήτησης</h2>
        </div>
        <div id='bottom-subtitle' class='subtitle'>
            <?php
                $count = $this->Paginator->counter(array('format' => '%count%'));
                if($count == 0) {
                    echo 'Δεν βρέθηκαν σπίτια';
                } else if($count == 1) {
                    echo 'Βρέθηκε '.$count.' σπίτι';
                } else {
                    echo 'Βρέθηκαν '.$count.' σπίτια';
                }
            ?>
        </div>
        <div class="pagination">
            <ul>
                <?php
                    // set the URL
                    $paginator->options(array('url' => array('?' => $get_vars)));
                    /* show the previous link */
                    echo $paginator->prev('« Προηγούμενη ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                    /* show pages */
                    echo $paginator->numbers(array('first' => 3, 'last' => 3, 'modulus' => '4', 'separator' => ' ', 'tag' => 'li'));
                    /* Shows the next link */
                    echo $paginator->next(' Επόμενη » ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                ?>
            </ul>
        </div>
        <div id='results-houses' class='results'>
            <ul>
                <?php foreach($results as $house): ?>
                    <li>
                        <div class='card'>
                            <div class='card-inner'>
                                <div class='house-pic'>
					<?php
						// thumbnail icon if exists
						$house_id = $house['House']['id'];
						$house_image = 'house.gif';
						if(isset($images[$house_id])){
							$house_image = 'uploads/houses/'.$house_id.'/thumb_'.$images[$house_id];
						}
						echo $this->Html->image($house_image, array('alt' => 'εικόνα '.$house['House']['address'], 'height' => 70));
					?>
                                </div>
                                <div class='house-info'>
                                    <div class='house-name'>
                                        <?php echo $this->Html->link($house['House']['address'],
                                                                     array(  'controller' => 'houses',
                                                                             'action' => 'view',
                                                                             $house['House']['id']   ));
                                        ?>
                                    </div>
                                    <div class='house-details'>
                                        <?php
                                            echo 'Ενοίκιο '.$house['House']['price'].'€, Εμβαδόν '.$house['House']['area'].' τ.μ. ';
                                            echo $house['House']['furnitured'] ? 'Επιπλωμένο<br/>' : 'Μη επιπλωμένο<br/>';
                                            echo 'Δήμος '.$municipality_options[$house['House']['municipality_id']].'<br/>';
                                            if($house['House']['disability_facilities']) echo 'Προσβάσιμο από ΑΜΕΑ<br/>';
                                            echo 'Διαθέσιμες θέσεις '.$house['House']['free_places'].'<br/>';
                                        ?>
                                    </div>
                                    <div class='house-house'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="pagination">
            <ul>
                <?php
                    /* show the previous link */
                    echo $paginator->prev('« Προηγούμενη ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                    /* show pages */
                    echo $paginator->numbers(array('first' => 3, 'last' => 3, 'modulus' => '4', 'separator' => ' ', 'tag' => 'li'));
                    /* Shows the next link */
                    echo $paginator->next(' Επόμενη » ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                ?>
            </ul>
        </div>
    </div>
</div>

<?php } // end if(isset($results)) ?>
