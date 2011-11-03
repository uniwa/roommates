<?php
    echo $this->Form->create('User', array('action' => 'register'));
    echo $this->Form->input('firstname', array('label' => 'Όνομα'));
    echo $this->Form->input('lastname', array('label' => 'Επίθετο'));
    echo $this->Form->input('company_name', array('label' => 'Επωνυμία Εταιρίας'));
    echo $this->Form->input('afm', array('label' => 'ΑΦΜ'));
    echo $this->Form->input('doy', array('label' => 'ΔΟΥ'));
    echo $this->Form->input('email', array('label' => 'e-mail'));
    echo $this->Form->input('phone', array('label' => 'Τηλέφωνο Επικοινωνίας'));
    echo $this->Form->input('fax', array('label' => 'φαξ'));
    echo $this->Form->input('address', array('label' => 'Διεύθυνση', 'type' => 'textarea', 'rows' => '2'));
    echo $this->Form->input('postal_code', array('label' => 'Τ.Κ.'));
    echo $this->Form->input('municipality_id', array('label' => 'Δήμος', 'empty' => 'Επιλέξτε...'));
    echo $this->Form->input('username', array('label' => 'Όνομα Χρήστη', 'autocomplete' => 'off'));
    echo $this->Form->input('password', array('label' => 'Συνθηματικό', 'type' => 'password', 'autocomplete' => 'off'));
    echo $this->Form->input('password_confirm', array('label' => 'Επιβεβαίωση Συνθηματικού', 'type' => 'password', 'autocomplete' => 'off'));
    echo $this->Form->end('Εγγραφή');
?>
