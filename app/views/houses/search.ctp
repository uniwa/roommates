<div id='top-frame' class='frame'>";
    <div class='frame-container'>";
        <div id='top-title' class='title'>";
            <h1>Σύνθετη αναζήτηση</h1>";
        </div>";

<div class='clear-both'></div>";

<?php

    $select_options = array('Όχι', 'Ναι', 'Αδιάφορο');
    $gender_options = array('Άνδρας', 'Γυναίκα', 'Αδιάφορο');
    $min_age_options = array(   'label' => 'Ηλικία από ', 
                                'class' => 'short-textbox',
                                'value' => isset($defaults) ? $defaults['min_age'] : '');
    $max_age_options = array(   'label' => 'μέχρι ', 
                                'class' => 'short-textbox',
                                'value' => isset($defaults) ? $defaults['max_age'] : '');

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

<h2>Προτιμήσεις σπιτιού</h2><br/>

<table>
    <tr>
        <td>
            <?php echo $this->Form->input('max_price', array(   'label' => 'Τιμή μέχρι ',
                                                                'class' => 'short-textbox',
                                                                'value' => isset($defaults) ? $defaults['max_price'] : '' ));
            ?>
        </td>
        <td>
            <?php echo $this->Form->input('min_area', array('label' => 'Εμβαδό από ',
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
                                                                'options' => $municipalities,
                                                                'value' => isset($defaults) ? $defaults['municipality'] : '',
                                                                'empty' => 'Αδιάφορο'  ));
            ?>
        </td>
        <td>
            <?php   echo $this->Form->input('furnitured', array('label' => 'Επιπλωμένο ',
                                                                'options' => array( 'Όχι',
                                                                                    'Ναι',
                                                                                    'Αδιάφορο'),
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
                                                            'options' => $order_options,
                                                            'selected' => isset($defaults) ? $defaults['order_by'] : '0'   ));
            ?>
        </td>
    </tr>
</table>

<br/><h2>Προτιμήσεις συγκατοίκων</h2><br/>

<table>
    <tr>
        <td>
            <?php echo $this->Form->input('min_age', $min_age_options);?>
        </td>
        <td>
            <?php echo $this->Form->input('max_age', $max_age_options);?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $this->Form->input('gender', array(   'label' => 'Φύλο ',
                                                                        'options' => $gender_options,
                                                                        'value' => isset($defaults) ? $defaults['gender'] : '2'    ));
            ?>
        </td>
        <td>
            <?php echo $this->Form->input('smoker', array(   'label' => 'Καπνιστής ',
                                                                        'options' => $select_options,
                                                                        'value' => isset($defaults) ? $defaults['smoker'] : '2'    ));
            ?>
        </td>
        <td>
            <?php echo $this->Form->input('pet', array( 'label' => 'Κατοικίδιο ',
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
            <?php echo $this->Form->input('couple', array(   'label' => 'Ζευγάρι ',
                                                                        'options' => $select_options,
                                                                        'value' => isset($defaults) ? $defaults['couple'] : '2'    ));
            ?>
        </td>
    </tr>
</table>

<br/>

<table>
    <tr>
        <td>
            <?php echo $this->Form->submit('αναζήτηση', array('name' => 'simple_search'));?>
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
                                </div>
                                <div class='house-info'>
                                    <div class='house-name'>
                                        <?php echo $this->Html->link(   $house['House']['address'],
                                                                        array(  'controller' => 'houses',
                                                                                'action' => 'view',
                                                                                $house['House']['id']   ));
                                        ?>
                                    </div>
                                    <div class='house-details'>
                                        <?php
                                            echo 'Τιμή '.$house['House']['price'].'€, Εμβαδό '.$house['House']['area'].' τ.μ. ';
                                            echo $house['House']['furnitured'] ? 'Επιπλωμένο<br/>' : 'Μη επιπλωμένο<br/>';
                                            echo 'Δήμος '.$municipalities[$house['House']['municipality_id']].'<br/>';
                                            if($house['House']['disability_facilities']) echo 'Προσβάσιμο από ΑΜΕΑ';
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
