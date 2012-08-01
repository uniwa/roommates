<?php
    $title = _('μετάβαση');
    $url = array('controller' => 'user', 'action' => 'transition');
    $transition_link = $this->Html->link($title, $url);
?>
<div class="freshman-msg">
    <p>Είστε συνδεδεμένος με τον προσωρινό σας λογαριασμό.</p>
    <p>Αν έχετε ήδη ολοκληρώσει την εγγραφή σας στο Ίδρυμα, ολοκληρώστε τη <?php echo $transition_link; ?> στο λογαριασμό με τα στοιχεία πρόσβασης του Ιδρύματος.</p>
</div>
