<?php

    echo "<div id='top-frame' class='frame'>";
    echo "<div class='frame-container'>";
    echo "<div id='top-title' class='title'>";
    echo "<h1>Σύνθετη αναζήτηση</h1>";
    echo "</div>";
    echo "<div class='clear-both'></div>";

    $select_options = array('Όχι', 'Ναι', 'Αδιάφορο');
    $gender_options = array('Άνδρας', 'Γυναίκα', 'Αδιάφορο');
    $min_age_options = array('label' => 'Ηλικία από ', 'class' => 'short-textbox');
    $max_age_options = array( 'label' => 'μέχρι ', 'class' => 'short-textbox');

    echo $this->Form->create('House', array('action' => 'search'));

?>

<h2>Προτιμήσεις συγκατοίκων</h2><br/>

<table>
    <tr>
        <td>
            <?php echo $this->Form->input('Preference.min_age', $min_age_options);?>
        </td>
        <td>
            <?php echo $this->Form->input('Preference.max_age', $max_age_options);?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $this->Form->input('Preference.gender', array(   'label' => 'Φύλο ',
                                                                        'options' => $gender_options,
                                                                        'default' => '2'    ));
            ?>
        </td>
        <td>
            <?php echo $this->Form->input('Preference.smoker', array(   'label' => 'Καπνιστής ',
                                                                        'options' => $select_options,
                                                                        'default' => '2'    ));
            ?>
        </td>
        <td>
            <?php echo $this->Form->input('Preference.pet', array( 'label' => 'Κατοικίδιο ',
                                                                    'options' => $select_options,
                                                                    'default' => '2'    ));
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $this->Form->input('Preference.child', array('label' => 'Παιδί ',
                                                                    'options' => $select_options,
                                                                    'default' => '2'    ));
            ?>
        </td>
        <td>
            <?php echo $this->Form->input('Preference.couple', array(   'label' => 'Ζευγάρι ',
                                                                        'options' => $select_options,
                                                                        'default' => '2'    ));
            ?>
        </td>
        <td>
            <?php echo 'Διαθέτει σπίτι '.$this->Form->checkbox( 'Preference.has_house',
                                                                array(  'checked' => false,
                                                                        'hiddenField' => false));
            ?>
        </td>
    </tr>
</table>

<br/><h2>Προτιμήσεις σπιτιού</h2><br/>

<table>
    <tr>
        <td>
            <?php echo $this->Form->input('max_price', array(   'label' => 'Τιμή μέχρι ',
                                                                'class' => 'short-textbox',
                                                                'default' => '' ));
            ?>
        </td>
        <td>
            <?php echo $this->Form->input('min_area', array('label' => 'Εμβαδό από ',
                                                            'class' => 'short-textbox',
                                                            'default' => '' ));
            ?>
        </td>
        <td>
            <?php echo $this->Form->input('max_area', array('label' => ' μέχρι ',
                                                            'class' => 'short-textbox',
                                                            'default' => '' ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=2>
            <?php echo $this->Form->input('municipality', array('label' => 'Δήμος ',
                                                                'options' => $municipalities,
                                                                'empty' => 'Αδιάφορο'  ));
            ?>
        </td>
        <td>
            <?php echo 'Επιπλομένο '.$this->Form->checkbox('furnitured', array( 'hiddenField' => false,
                                                                                'checked' => false  ));
            ?>
        </td>
    </tr>
    <tr>
        <td colspan=2>
            <?php echo 'Προσβάσιμο από ΑΜΕΑ '.$this->Form->checkbox('furnitured',
                                                                    array(  'hiddenField' => false,
                                                                            'checked' => false  ));
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
            <?php echo $this->Form->submit('καθαρισμός πεδίων', array(  'name' => 'reset_fields'    ));?>
        </td>
    </tr>
</table>

<?php
    echo $this->Form->end();
?>