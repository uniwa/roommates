<style>
    #leftbar{
        float: left;
        margin: 0px 32px 0px 32px;
        padding: 32px;
        width: 150px;
    }
    
    #main-inner{
        float: left;
        border-left: 1px dotted #333;
        margin: 10px 0px 20px 0px;
        padding: 24px 24px 24px 64px;
    }
    
    #profilePic{
        margin: 6px;
        padding: 2px;
        width: 128px;
        height: 128px;
    }
    
    #profileName{
        text-align: center;
        font-size: 1.2em;
        font-weight: bold;
    }
    
    #profileEmail{
        margin: 8px 0px 0px 0px;
        text-align: center;
        font-size: 1.2em;
    }
    
    .profileFrame{
        clear: both;
        padding: 0px 0px 24px 0px;
    }
    
    .editTitle{
        margin: 0px 0px 24px 16px;
        font-size: 1.2em;
        font-weight: bold;
    }

    .input select{
        border: 1px solid #ddd;
        padding: 4px;
        font-size: 12px;
    }

    .textarea label {
        float: left;
    }

    .input{
        clear: both;
        padding: 3px 0;
    }
    
    .radio,.checkbox{
        margin: 12px 0px 16px 64px;
    }
    
    .input label{
        float: left;
        margin: 8px 12px 0px 0px;
        width: 160px;
        text-align: right;
    }

    .checkbox input{
        float: left;
        margin: 8px 0px 0px 8px;
    }

    .checkbox label{
        float: left;
        margin: 8px 0px 0px 8px;
        width: auto;
    }
    
    .input input[type=text]{
        border: 1px solid #ddd;
        padding: 4px;
    }
    
    .longBox{
        width: 100px;
    }
    
    .shortBox{
        width: 30px;
    }
    
    .button{
        border: 0px;
        margin: 16px 0px 16px 24px;
        width: 100px;
        height: 24px;
        cursor: pointer;
    }
    
    .required{
        background-position: 160px 4px;
    }
</style>
<?php
    $name = $profile['firstname'].' '.$profile['lastname'];
    $email = $profile['email'];
    $emailUrl = $this->Html->link($email, 'mailto:'.$email);
    if (empty($profile['avatar'])) {
        $picture = ($profile['gender'])?'female.jpg':'male.jpg';
    } else {
        $picture = 'uploads/profiles/'.$profile['id'].'/'.$profile['avatar'];
    }
    $profileThumb = $this->Html->image($picture, array('alt' => $name));
?>
<div id='leftbar'>
    <div id='profilePic'>
        <?php
            echo $profileThumb;
        ?>
    </div>
    <div class='profileData'>
        <div id='profileName'>
            <?php echo $name; ?>
        </div>
        <div id='profileEmail'>
            <?php echo $emailUrl; ?>
        </div>
    </div>
</div>
<div id='main-inner'>
    <div class='profileFrame'>
        <?php echo $this->Form->create('Profile', array('type' => 'file')); ?>
        <div class='editTitle'>
            <h2>Εικόνα χρήστη</h2>
        </div>
        <div id='avatarField'>
            <?php
                echo $this->Form->input('avatar', array('type' => 'file', 'label' => 'Επιλέξτε εικόνα προφίλ'));
            ?>
        </div>

        <div class='editTitle'>
            <h2>Στοιχεία χρήστη</h2>
        </div>
        <?php
            echo "<div class='radio'>".$this->Form->radio('gender',
                array('0' => 'Άνδρας', '1' => 'Γυναίκα'),
                array('legend' => false))."</div>";
            echo $this->Form->input('dob', array(
                'label' => 'Ημερομηνία γέννησης',
                'type' => 'select',
                'options' => $available_birth_dates));
            echo $this->Form->input('Profile.phone',
                array('label' => 'Τηλέφωνο', 'class' => 'longBox'));
            echo $this->Form->input('Profile.we_are',
                array('label' => 'Είμαστε', 'class' => 'shortBox'));
            echo $this->Form->input('Profile.max_roommates',
                array('label' => 'Ζητούνται', 'class' => 'shortBox'));
            echo $this->Form->input('Profile.smoker', array('label' => 'Είμαι καπνιστής'));
            echo $this->Form->input('Profile.pet', array('label' => 'Έχω κατοικίδιο'));
            echo $this->Form->input('Profile.child', array('label' => 'Έχω παιδί'));
            echo $this->Form->input('Profile.couple', array('label' => 'Συζώ'));
        ?>
    </div>
    <div class='profileFrame'>
        <div class='editTitle'>
            <h2>Προτιμήσεις συγκατοίκου</h2>
        </div>
        <?php
            $genderLabels = array('άνδρας', 'γυναίκα', 'αδιάφορο');
            $options = array('όχι', 'ναι', 'αδιάφορο');
            echo '<div id="agefields">';
            echo '<div class="agefrom">';
            echo $this->Form->input('Preference.age_min', array(
                'label' => 'Ηλικία από', 'maxlength' => '2', 'class' => 'shortBox'));
            echo '</div>';
            echo $this->Form->input('Preference.age_max', array(
                'label' => 'έως','maxlength'=>'2', 'class' => 'shortBox'));
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
        ?>
    </div>
    <div class='profileFrame'>
        <?php
            echo $this->Form->input('Profile.visible', array(
                'label' => 'Να γίνομαι ορατός σε αναζητήσεις χρηστών με βάση τα στοιχεία του προφίλ μου'));
            echo $this->Form->input('Profile.get_mail', array(
                'label' => 'Να λαμβάνω ενημερώσεις μέσω e-mail για τα νέα σπίτια που αναρτώνται'));
        ?>
    </div>
    <div class='profileFrame'>
        <?php
            echo $this->Form->submit('Ενημέρωση', array('name' => 'edit', 'class' => 'button'));
            echo $this->Form->end();
        ?>
    </div>
</div>

