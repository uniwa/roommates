<style>
    #main-inner{
        margin: 0px auto;
        padding: 0px 0px 0px 0px;
        width: 540px;
    }

    .form-title{
        clear: both;
        margin: 16px 0px 32px 8px;
        font-size: 1.2em;
        font-weight: bold;
    }

    .form-center{
        margin: 8px auto;
        text-align: center;
    }

    .form-buttons{
        margin: 20px auto;
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
        width: 300px;
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
        width: 300px;
        height: 14px;
    }

    .form-input textarea.input-elem{
        border: 1px solid #ddd;
        padding: 2px;
        width: 300px;
    }

</style>

<div id='main-inner'>
    <?php
//        $issuesCategories = array();
        echo $this->Form->create(false, array('action' => 'help'));
    ?>
    <div class='form-title'>
        <h2>Παρακαλώ συμπληρώστε τα πεδία:</h2>
    </div>
    <ul>
        <li class='form-line'>
            <div class='form-elem form-label'>
                Θέμα
            </div>
            <div class='form-elem form-input'>
                <?php
                    echo $this->Form->input('subject', array(
                        'label' => '', 'class' => 'input-elem'));
                ?>
            </div>
        </li>
<!--
        <li class='form-line'>
            <div class='form-elem form-label'>
                Τύπος προβλήματος
            </div>
            <div class='form-elem form-input'>
                <?php
                    echo $this->Form->input('category', array(
                        'label' => '', 'type' => 'select',
                        'options' => $issuesCategories, 'class' => 'input-elem'));
                ?>
            </div>
        </li>
-->
        <li class='form-line'>
            <div class='form-elem form-label'>
                Περιγραφή
            </div>
            <div class='form-elem form-input'>
                <?php
                    echo $form->input('description', array(
                        'label' => '', 'type' => 'textarea',
                        'rows' => '4', 'class' => 'input-elem'));
                ?>
            </div>
        </li>
        <li class='form-line form-buttons'>
            <div class='form-elem form-submit'>
                <?php
                    echo $this->Form->submit('Αποστολή', array(
                        'class' => 'button'));
                    echo $this->Form->end();
                ?>
            </div>
        </li>
    </ul>
</div>

