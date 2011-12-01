<style>
    #main-inner{
        margin: 0px auto;
        padding: 0px 0px 0px 0px;
        width: 540px;
    }
</style>

<div id='main-inner'>
    <?php
        echo $this->Form->create(false, array('action' => 'help'));
        echo "<h2>Παρακαλώ συμπληρώστε τα πεδία:</h2>";
        echo $this->Form->input('subject', array('label' => 'θέμα'));
        echo $this->Form->input('description', array('label' => 'περιγραφή'));
        echo $this->Form->submit('αποστολή');
        echo $this->Form->end();
    ?>
</div>

