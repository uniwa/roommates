<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Όροι Χρήσης</title>
    <?php echo $this->Html->css('global'); ?>


</head>
    <body>
        <div class="terms">

        <?php
            echo $this->element('terms');
        ?>
        <?php
            echo $this->Form->create( 'User', array( 'action' => 'terms' ) );
            echo $this->Form->label('Αποδέχομαι τους όρους χρήσης');
            echo $this->Form->checkbox('accept');
            echo $this->Form->button('Συνέχεια', array( 'type' => 'submit' ) );
            echo $this->Form->end();
        ?>
    </div>

    </body>
</html>
