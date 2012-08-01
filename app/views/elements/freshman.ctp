<?php
    $title = _('μετάβαση');
    $url = array('controller' => 'users', 'action' => 'transition');
    $transition_link = $this->Html->link($title, $url);
?>
<div class="freshman-msg">
    <p>Είστε συνδεδεμένος με τον προσωρινό σας λογαριασμό.</p>
    <p>Αν έχετε ήδη εγγραφεί στο ΤΕΙ, ολοκληρώστε τη <?php echo $transition_link; ?></p>
    <p>στο λογαριασμό με τα στοιχεία πρόσβασης του Ιδρύματος.</p>
</div>
