<style>
    #smallform table {
        border: none;
        font-size: 10px;
    }

    #smallform select {

        font-size: 10px;
    }

    #smallform {
border-color: #B9BBBF;
        margin: 0 auto;
        width: 680px;
    }

    #smallform input[type=text] {
        width: 30px;
        height: 20px;
        text-align: right;

    }

.middletable{
    padding:5px;
}

</style>


<?php
            if (isset($profiles)) {


    echo '<div id="smallform" class="frame">';

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


<table>

    <tr>
        <td>
            <?php
                          echo $this->Form->input('age_min', array('label' => 'Ηλικία απο '));
            echo '</td><td>';
            echo $this->Form->input('age_max', array('label' => 'έως '));
            echo '</td><td>';
            ?>
        </td>
        <td>
            <?php  echo 'Διαθέτει σπίτι ' . $this->Form->checkbox('has_house', array('value' => 1,
                                                                                    'checked' => isset($defaults['has_house'])
                                                                                            ? $defaults['has_house']
                                                                                            : false,
                                                                                    'hiddenField' => false));
            ?>
        </td>

        <td>
            <?php
                                  echo $this->Form->input('orderby', array(
                                                              'label' => 'Ταξινόμηση με: ',
                                                              'options' => $order_options['options'],
                                                              'default' => $order_options['selected']));
                ?>
        </td>
    </tr>
</table>
<table class="middletable">
    <tr>
        <td>

            <?php
                          echo $this->Form->input('pref_gender', array('label' => 'Φύλο ',
                                                          'options' => $genderoptions,
                                                          'value' => (isset($defaults['pref_gender']))
                                                                  ? $defaults['pref_gender']
                                                                  : '2',
                                                          'default' => '2'));
                echo '</td><td>';
                echo $this->Form->input('pref_smoker', array('label' => 'Καπνιστής ',
                                                            'options' => $options,
                                                            'default' => '2',
                                                            'value' => (isset($defaults['pref_smoker']))
                                                                    ? $defaults['pref_smoker'] : '2'));
                echo '</td><td>';
                echo $this->Form->input('pref_pet', array('label' => 'Κατοικίδιο ',
                                                         'options' => $options,
                                                         'default' => '2',
                                                         'value' => (isset($defaults['pref_pet']))
                                                                 ? $defaults['pref_pet']
                                                                 : '2'));
                ?>
        </td>

        <td>
            <?php

                echo $this->Form->input('pref_child', array('label' => 'Παιδί ',
                                                           'options' => $options,
                                                           'default' => '2',
                                                           'value' => (isset($defaults['pref_child']))
                                                                   ? $defaults['pref_child'] : '2'));
                echo '</td><td>';
                echo $this->Form->input('pref_couple', array('label' => 'Ζευγάρι ',
                                                            'options' => $options,
                                                            'default' => '2',
                                                            'value' => (isset($defaults['pref_couple']))
                                                                    ? $defaults['pref_couple'] : '2'));
                echo '</td>';

                ?>

    </tr>
</table>
<table>
    <tr>
        <td>
            <?php
                echo $this->Form->submit('αναζήτηση', array('name' => 'simplesearch'));
                echo '</td><td>';
                echo $this->Form->submit('αποθήκευση', array('name' => 'savesearch'));
                ?>
        </td>

        <td>
            <?php
                echo $this->Form->submit('φόρτωση προτιμήσεων', array('name' => 'searchbyprefs'));
                echo '</td><td>';
                echo $this->Form->submit('καθαρισμός', array('name' => 'resetvalues'));
                ?>
        </td>
    </tr>
</table>

</div><!--small form-->

<!--Αποτελεσματα Αναζητησης-->

<div id='bottom-frame' class='frame'>
<div class='frame-container'>
    <div id='bottom-title' class='title'>
        <h2>Αποτελέσματα αναζήτησης</h2>
    </div>
    <div id='bottom-subtitle' class='subtitle'>
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
<div id='results-profiles' class='results'>
<ul>
            <?php
                                    if (isset($profiles)) {
    ?>
    <?php foreach ($profiles as $profile): ?>
        <li>
            <div class='card'>
                <div class='card-inner'>
                    <?php
                                                $gender = ($profile['Profile']['gender']) ? 'fe' : '';
                    echo "<div class='profile-pic " . $gender . "male'>\n";
                    ?>
                </div>
                <div class='profile-info'>
                    <div class='profile-name'>
                    <?php
                                                        echo $this->Html->link($profile['Profile']['firstname'] . ' ' . $profile['Profile']['lastname'],
                                                                               array('controller' => 'profiles', 'action' => 'view', $profile['Profile']['id']));
                        ?>
                    </div>
                    <div class='profile-details'>
                    <?php
                                                        echo $profile['Profile']['age'] . ", ";
                        $gender = ($profile['Profile']['gender']) ? 'γυναίκα' : 'άνδρας';
                        echo $gender . "<br />\n";
						$email = $profile['Profile']['email'];
						$emailUrl = $this->Html->link($email, 'mailto:'.$email);
						echo "email: ".$emailUrl."<br />\n";
                        echo "επιθυμητοί συγκάτοικοι: " . $profile['Profile']['max_roommates'] . "<br />\n";
                        ?>
                    </div>
                    <div class='profile-house'>
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

}
    ?>
</div>
<?php } else { ?>

<div id='top-frame' class='frame'>
    <div class='frame-container'>
        <div id='top-title' class='title'>
            <h1>Αναζήτηση συγκατοίκων</h1>
        </div>

        <?php
            echo "<div class='clear-both'></div>";
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

        <table>
            <tr>
                <td>
        <?php
            echo $this->Form->input('age_min', $ageminoptions);
            echo '</td><td>';
            echo $this->Form->input('age_max', $agemaxoptions);
            echo '</td><td>';
            ?>
                </td>
            </tr>
            <tr>
                <td>

        <?php
            echo $this->Form->input('pref_gender', array('label' => 'Φύλο ',
                                                        'options' => $genderoptions,
                                                        'value' => (isset($defaults['pref_gender']))
                                                                ? $defaults['pref_gender']
                                                                : '2',
                                                        'default' => '2'));
            echo '</td><td>';
            echo $this->Form->input('pref_smoker', array('label' => 'Καπνιστής ',
                                                        'options' => $options,
                                                        'default' => '2',
                                                        'value' => (isset($defaults['pref_smoker']))
                                                                ? $defaults['pref_smoker'] : '2'));
            echo '</td><td>';
            echo $this->Form->input('pref_pet', array('label' => 'Κατοικίδιο ',
                                                     'options' => $options,
                                                     'default' => '2',
                                                     'value' => (isset($defaults['pref_pet'])) ? $defaults['pref_pet']
                                                             : '2'));
            ?>
                </td>
            </tr>
            <tr>
                <td>
        <?php

            echo $this->Form->input('pref_child', array('label' => 'Παιδί ',
                                                       'options' => $options,
                                                       'default' => '2',
                                                       'value' => (isset($defaults['pref_child']))
                                                               ? $defaults['pref_child'] : '2'));
            echo '</td><td>';
            echo $this->Form->input('pref_couple', array('label' => 'Ζευγάρι ',
                                                        'options' => $options,
                                                        'default' => '2',
                                                        'value' => (isset($defaults['pref_couple']))
                                                                ? $defaults['pref_couple'] : '2'));
            echo '</td><td>';
            echo 'Διαθέτει σπίτι ' . $this->Form->checkbox('has_house', array('value' => 1,
                                                                             'checked' => isset($defaults['has_house'])
                                                                                     ? $defaults['has_house'] : false,
                                                                             'hiddenField' => false));
            ?>
                </td>
            </tr>
            <tr>
                <td colspan='3'>
        <?php
                    echo $this->Form->input('orderby', array(
                                                            'label' => 'Ταξινόμηση με: ',
                                                            'options' => $order_options['options'],
                                                            'default' => $order_options['selected']));
            ?>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
        <?php
            echo $this->Form->submit('αναζήτηση', array('name' => 'simplesearch'));
            echo '</td><td>';
            echo $this->Form->submit('αποθήκευση', array('name' => 'savesearch'));
            ?>
                </td>

                <td>
        <?php
            echo $this->Form->submit('φόρτωση προτιμήσεων', array('name' => 'searchbyprefs'));
            echo '</td><td>';
            echo $this->Form->submit('καθαρισμός', array('name' => 'resetvalues'));
            ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<?php } ?>