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
<div id='profileEditView'>
<div id='leftbar' class='leftGeneral'>
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
<div id='main-inner' class='mainGeneral'>
    <div class='profileFrame'>
        <?php echo $this->Form->create('Profile', array('type' => 'file')); ?>
        <div class='editTitle'>
            <h2>Στοιχεία χρήστη</h2>
        </div>
        <div class='editSubTitle'>
            <h3>Εικόνα χρήστη</h3>
        </div>
        <div class='avatarLabel'>
            Επιλέξτε εικόνα προφίλ (διαστάσεις μέχρι 100x100 pixels)
        </div>
        <div id='avatarField'>
            <?php
                echo $this->Form->input('avatar', array('type' => 'file', 'label' => ''));
            ?>
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
                'label' => "Να λαμβάνω ενημερώσεις μέσω e-mail για τους χρήστες και τα σπίτια<br />\nπου ταιριάζουν στις προτιμήσεις μου"));
        ?>
    </div>
    <div class='profileFrame'>
        <?php
            echo $this->Form->submit('Ενημέρωση', array('name' => 'edit', 'class' => 'button'));
            echo $this->Form->end();
        ?>
    </div>
</div>
</div>
