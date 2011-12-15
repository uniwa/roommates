<div id='registerView'>
<div id='mainCenter' class='mainLogin'>
<?php
    $registerType = ($type == 'owner' || $type == 'from_admin')?'registerowner':'registerrealestate';
    echo $this->Form->create('User', array('action' => $registerType));
    if ($type == 'from_admin') {
        // if we are admin, we register owners not real estates
        echo $this->Form->input('RealEstate.type', array('type' => 'hidden',
            'value' => 'owner'));
    }
    else {
        echo $this->Form->input('RealEstate.type', array('type' => 'hidden',
            'value' => $type));
    }
    $inputelems = array();
    $inputelems['uname']['input'] = $this->Form->input('User.username', array(
        'label' => '', 'autocomplete' => 'off', 'class' => 'input-elem'));
    $inputelems['uname']['label'] = 'Όνομα χρήστη';

    /* set error for username that already exists */
    if (isset($user_errors["username"])) {
        $inputelems['uname']['error'] = "<div class='error-message'>". $user_errors["username"] ."</div>";
    }

    /* set error for non-matching passowrds */
    if (isset($user_errors["password_confirm"])) {
        $inputelems['pass1']['error'] = "<div class='error-message'>". $user_errors["password_confirm"] ."</div>";
        $inputelems['pass2']['error'] = "<div class='error-message'>". $user_errors["password_confirm"] ."</div>";
    }

    $inputelems['pass1']['input'] = $this->Form->input('User.password', array(
        'label' => '', 'type' => 'password', 'autocomplete' => 'off', 'class' => 'input-elem'));
    $inputelems['pass1']['label'] = 'Συνθηματικό';
    $inputelems['pass2']['input'] = $this->Form->input('User.password_confirm', array(
        'label' => '', 'type' => 'password',
        'autocomplete' => 'off', 'class' => 'input-elem'));
    $inputelems['pass2']['label'] = 'Επιβεβαίωση συνθηματικού';
    /* user info end - real estate profile start */
    $inputelems['fname']['input'] = $this->Form->input('RealEstate.firstname', array(
        'label' => '', 'class' => 'input-elem'));
    $inputelems['fname']['label'] = 'Όνομα';
    $inputelems['lname']['input'] = $this->Form->input('RealEstate.lastname', array(
        'label' => '', 'class' => 'input-elem'));
    $inputelems['lname']['label'] = 'Επίθετο';
    if($type == 'owner' || $type = 'from_admin'){
        $inputType = 'hidden';
    }else{
        $inputType = 'text';
        $inputelems['cname']['label'] = 'Επωνυμία εταιρίας';
    }
    $inputelems['cname']['input'] = $this->Form->input('RealEstate.company_name', array('label' => '', 'class' => 'input-elem', 'type' => $inputType));
    $inputelems['afm']['input'] = $this->Form->input('RealEstate.afm', array('label' => '', 'class' => 'input-elem'));
    $inputelems['afm']['label'] = 'ΑΦΜ';
    $inputelems['doy']['input'] = $this->Form->input('RealEstate.doy', array('label' => '', 'class' => 'input-elem'));
    $inputelems['doy']['label'] = 'ΔΟΥ';
    $inputelems['email']['input'] = $this->Form->input('RealEstate.email', array('label' => '', 'class' => 'input-elem'));
    $inputelems['email']['label'] = 'Email';
    $inputelems['phone']['input'] = $this->Form->input('RealEstate.phone', array('label' => '', 'class' => 'input-elem'));
    $inputelems['phone']['label'] = 'Τηλέφωνο επικοινωνίας';
    $inputelems['fax']['input'] = $this->Form->input('RealEstate.fax', array('label' => '', 'class' => 'input-elem'));
    $inputelems['fax']['label'] = 'Φαξ';
    $inputelems['municipality']['input'] = $this->Form->input('RealEstate.municipality_id', array(
        'label' => '', 'empty' => 'Επιλέξτε...', 'class' => 'input-elem'));
    $inputelems['municipality']['label'] = 'Δήμος';
    $inputelems['address']['input'] = $this->Form->input('RealEstate.address', array(
        'label' => '', 'class' => 'input-elem'));
    $inputelems['address']['label'] = 'Διεύθυνση';
    $inputelems['pc']['input'] = $this->Form->input('RealEstate.postal_code', array('label' => '', 'class' => 'input-elem'));
    $inputelems['pc']['label'] = 'Τ.Κ.';

    /* model related form data end */
    // TODO: place terms text here (or load from file?!)
    $terms_text = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi velit mi, blandit sit amet volutpat ut, accumsan quis ante. Vivamus euismod, metus id molestie sagittis, neque tortor eleifend elit, in sodales neque erat eu eros. Nullam vitae ante libero. Vestibulum vehicula egestas sem, vitae viverra ipsum pharetra sit amet. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Quisque malesuada risus libero, et placerat velit. Phasellus ac interdum justo. Donec consequat viverra nisl vitae dapibus.";

?>
    <div class='form-title'>
        <h2>Παρακαλώ συμπληρώστε τα στοιχεία σας:</h2>
    </div>
    <ul>
        <?php
            foreach($inputelems as $elem){
        ?>
        <li class='form-line'>
                <?php
                    if (isset($elem['label'])) {
                ?>
                <div class='form-elem form-label'>
                    <?php echo $elem['label']; ?>
                </div>
                <?php } ?>
            <div class='form-elem form-input'>
                <?php
                    echo $elem['input'];
                    if (isset($elem['comment'])) {
                ?>
                <div class='register-form-comment'>
                    <?php echo $elem['comment']; ?>
                </div>
                <?php } ?>
            </div>
            <?php
                if (isset($elem['error'])) {
                    echo $elem['error'];
                }
            ?>
        </li>
        <?php } // foreach ?>
        <?php if ($type != 'from_admin') { // ignore recaptcha and legal notes ?>
        <li class='form-line'>
            <textarea rows="6" cols="80" readonly="readonly">
                <?php echo $terms_text; ?>
            </textarea>
        </li>
        <li class='form-line form-center'>
            <?php
                // TODO: fix "required" star's position
                echo $this->Form->checkbox('estate_terms', array('hidden' => false, 'checked' => false, 'class' => 'formCheckbox'));
                echo $this->Form->label('estate_terms', 'Διάβασα και αποδέχομαι τους όρους χρησης', array('class' => 'checkLabel required'));
            ?>
        </li>
        <li class='form-line form-center'>
            <?php echo $this->Recaptcha->display(); ?>
        </li>
        <?php } // END recaptcha and legal notes ignore ?>
        <li class='form-line form-buttons'>
            <div class='form-elem form-submit'>
                <?php
                    echo $this->Form->submit('Εγγραφή', array(
                        'class' => 'button'));
                    echo $this->Form->end();
                ?>
            </div>
        </li>
    </ul>
</div>
</div>

