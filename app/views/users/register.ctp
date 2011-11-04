<style>
    .form-title{
        clear: both;
        margin: 16px 0px 32px 8px;
        font-size: 1.2em;
        font-weight: bold;
    }
    
    #main-inner ul{
        margin: 0px 0px 20px 0px;
    }
    
    #main-inner{
        margin: 0px auto;
        padding: 0px 0px 0px 0px;
        width: 540px;
    }

    .form-buttons{
        margin: 10px auto;
        width: 220px;
    }
    
    .form-elem{
        margin: 0px 8px 12px 0px;
        font-size: 1.2em;
    }

    .form-label{
        float: left;
        width: 160px;
        text-align: right;
    }
    
    .form-input{
        float: left;
        width: 240px;
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
    
    .form-input input.input-elem{
        border: 1px solid #ddd;
        padding: 2px;
        width: 220px;
        height: 14px;
    }
    
    .form-input textarea.input-elem{
        border: 1px solid #ddd;
        padding: 2px;
        width: 220px;
    }

    /* TODO: add custom class to differenciate between other views stars */
    .required {
        background: url("../img/required.gif") no-repeat scroll right top transparent;
    }
</style>

<div id='main-inner'>
<?php
    echo $this->Form->create('User', array('action' => 'register'));
    $inputelems = array();
    $inputelems['uname']['input'] = $this->Form->input('username', array(
        'label' => '', 'autocomplete' => 'off', 'class' => 'input-elem'));
    $inputelems['uname']['label'] = 'Όνομα χρήστη';
    $inputelems['pass1']['input'] = $this->Form->input('password', array(
        'label' => '', 'type' => 'password', 'autocomplete' => 'off', 'class' => 'input-elem'));
    $inputelems['pass1']['label'] = 'Συνθηματικό';
    $inputelems['pass2']['input'] = $this->Form->input('password_confirm', array(
        'label' => '', 'type' => 'password',
        'autocomplete' => 'off', 'class' => 'input-elem'));
    $inputelems['pass2']['label'] = 'Επιβεβαίωση συνθηματικού';
    /* user info end - real estate profile start */
    $inputelems['fname']['input'] = $this->Form->input('firstname', array(
        'label' => '', 'class' => 'input-elem'));
    $inputelems['fname']['label'] = 'Όνομα';
    $inputelems['lname']['input'] = $this->Form->input('lastname', array(
        'label' => '', 'class' => 'input-elem'));
    $inputelems['lname']['label'] = 'Επίθετο';
    $inputelems['cname']['input'] = $this->Form->input('company_name', array('label' => '', 'class' => 'input-elem'));
    $inputelems['cname']['label'] = 'Επωνυμία εταιρίας';
    $inputelems['afm']['input'] = $this->Form->input('afm', array('label' => '', 'class' => 'input-elem'));
    $inputelems['afm']['label'] = 'ΑΦΜ';
    $inputelems['doy']['input'] = $this->Form->input('doy', array('label' => '', 'class' => 'input-elem'));
    $inputelems['doy']['label'] = 'ΔΟΥ';
    $inputelems['email']['input'] = $this->Form->input('email', array('label' => '', 'class' => 'input-elem'));
    $inputelems['email']['label'] = 'Email';
    $inputelems['phone']['input'] = $this->Form->input('phone', array('label' => '', 'class' => 'input-elem'));
    $inputelems['phone']['label'] = 'Τηλέφωνο επικοινωνίας';
    $inputelems['fax']['input'] = $this->Form->input('fax', array('label' => '', 'class' => 'input-elem'));
    $inputelems['fax']['label'] = 'Φαξ';
    $inputelems['address']['input'] = $this->Form->input('address', array(
        'label' => '', 'class' => 'input-elem'));
    $inputelems['address']['label'] = 'Διεύθυνση';
    $inputelems['pc']['input'] = $this->Form->input('postal_code', array('label' => '', 'class' => 'input-elem'));
    $inputelems['pc']['label'] = 'Τ.Κ.';
    $inputelems['municipality']['input'] = $this->Form->input('municipality_id', array(
        'label' => '', 'empty' => 'Επιλέξτε...', 'class' => 'input-elem'));
    $inputelems['municipality']['label'] = 'Δήμος';
?>
    <ul>
        <div class='form-title'>
            <h2>Παρακαλώ συμπληρώστε τα στοιχεία σας:</h2>
        </div>
        <?php
            foreach($inputelems as $elem){
        ?>
        <li class='form-line'>
            <div class='form-elem form-label'>
                <?php echo $elem['label']; ?>
            </div>
            <div class='form-elem form-input'>
                <?php echo $elem['input']; ?>
            </div>
        </li>
    <?php } // foreach ?>
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
    <?php
        /*$publicKey = "6Ld7vMkSAAAAALw4jfDEI6LLyCxTN4pOIQ7GvPZx";
        echo recaptcha_get_html($publickey);*/
    ?>
</div>

