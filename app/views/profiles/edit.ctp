<style>
    #main-inner{
        margin: 0px 0px 0px 0px;
        padding: 32px 128px 32px 128px;
    }
    
    .edit-title{
        margin: 12px 0px 24px 0px;
        font-size: 1.2em;
        font-weight: bold;
    }

    select{
            font-size: 12px;
    }

    #ProfileAddForm label, #ProfileEditForm label {
        font-size: 12px;
        font-weight: bold;
        color: #21759B;
        line-height: 19px;
        min-width: 120px;
        display: inline-block;
        padding-left: 10px;
    }

    #ProfileAddForm input[type=text], #ProfileAddForm textarea, #ProfileEditForm input[type=text], #ProfileEditForm textarea {
        padding: 4px;
        border: solid 1px #C6C6C6;
        border-bottom: solid 1px #E3E3E3;
        color: #333;
        -moz-box-shadow: inset 0 4px 6px #ccc;
        -webkit-box-shadow: inset 0 4px 6px #ccc;
        box-shadow: inset 0 4px 6px #ccc;

    }

    #ProfileAddForm input[type=text], #ProfileEditForm input[type=text] {
        width: 50px;
        text-align: right;
    }

    .textarea label {
        float: left;
    }

    .input {
        padding: 3px 0;
    }
</style>
<div id='main-inner'>
    <div class='profileframe'>
        <div class='edit-title'>
            <h2>Στοιχεία χρήστη</h2>
        </div>
        <?php
            echo $this->Form->create('Profile');
            echo $this->Form->input('firstname', array('label' => 'Όνομα'));
            echo $this->Form->input('lastname', array('label' => 'Επώνυμο'));
            echo $this->Form->input('email', array('label' => 'Email'));
            //	echo $this->Form->input('age', array('label' => 'Ηλικία'));
            echo $form->input('dob', array(
                'label' => 'Ημερομηνία γέννησης',
                'type' => 'select',
                'options' => $available_birth_dates));
            echo $this->Form->radio('gender',
                array('0' => 'Άνδρας', '1' => 'Γυναίκα'),
                array('legend' => false));
            echo $this->Form->input('Profile.phone', array('label' => 'Τηλέφωνο'));
            echo $this->Form->input('Profile.smoker', array('label' => 'Είμαι καπνιστής'));
            echo $this->Form->input('Profile.pet', array('label' => 'Έχω κατοικίδιο'));
            echo $this->Form->input('Profile.child', array('label' => 'Έχω παιδί'));
            echo $this->Form->input('Profile.couple', array('label' => 'Συζώ'));
            echo $this->Form->input('Profile.we_are', array('label' => 'Είμαστε'));
            echo $this->Form->input('Profile.max_roommates', array('label' => 'Ζητούνται'));
            echo $this->Form->input('Profile.visible', array(
                'label' => 'Να γίνομαι ορατός σε αναζητήσεις χρηστών με βάση τα στοιχεία του προφίλ μου'));
            echo $this->Form->input('Profile.get_mail', array(
                'label' => 'Να λαμβάνω ενημερώσεις μέσω e-mail για τα νέα σπίτια που αναρτώνται'));
        ?>
    </div>
    <div class='profileframe'>
        <div class='edit-title'>
            <h2>Προτιμήσεις συγκατοίκου</h2>
        </div>
        <?php
            $genderLabels = array('άνδρας', 'γυναίκα', 'αδιάφορο');
            $options = array('όχι', 'ναι', 'αδιάφορο');
            echo '<div id="agefields">';
            echo '<div class="agefrom">';
            echo $this->Form->input('Preference.age_min', array('label' => 'Ηλικία από' ,'maxlength'=>'2'));
            echo '</div>';
            echo $this->Form->input('Preference.age_max', array('label' => 'έως','maxlength'=>'2'));
            echo '</div>';
            echo '<div id="labelspreferences">';
            echo $this->Form->input('Preference.pref_gender', array(
                'label' => 'Φύλο', 'type' => 'select',
                'options' => $genderLabels, 'default' => 2));
            echo $this->Form->input('Preference.pref_smoker', array(
                'label' => 'Είναι καπνιστής', 'type' => 'select',
                'options' => $options, 'default' => 2));
            echo $this->Form->input('Preference.pref_pet', array(
                'label' => 'Έχει κατοικίδιο', 'type' => 'select',
                'options' => $options, 'default' => 2));
            echo $this->Form->input('Preference.pref_child', array(
                'label' => 'Έχει παιδί', 'type' => 'select',
                'options' => $options, 'default' => 2));
            echo $this->Form->input('Preference.pref_couple', array(
                'label' => 'Συζεί', 'type' => 'select',
                'options' => $options, 'default' => 2));
            echo '</div>';
            echo $this->Form->input('Profile.id', array('type' => 'hidden'));
            echo $this->Form->input('Preference.id', array('type' => 'hidden'));
            echo $this->Form->end('Υποβολή');
        ?>
    </div>
</div>

