<style>
    #main-inner{
        padding: 24px;
        font-size: 1.1em;
    }   
    
    .loginMain{
        border-top: 6px solid #ddd;
        background-color: #fff;
        background: -moz-linear-gradient(top, #f4f4f4, #fff);
        background: -webkit-gradient(linear, left top, left bottom, from(#f4f4f4), to(#fff));
        margin: 0px auto 0px auto;
        padding: 32px 32px 16px 48px;
        width: 400px;
    }
    
    .loginContent{
/*        border: 1px solid #0f0;*/
        margin: 0px auto 0px auto;
        padding: 8px 0px 0px 0px;
        width: 340px;
    }
    
    .credential{
        margin: 0px 0px 16px 0px;
    }
    
    .credential .input label{
        display: block;
        margin: 0px 0px 4px 24px;
        padding: 4px 0px 0px 0px;
    }
    
    .credential .input input{
        border: 1px solid #ddd;
    }
    
    .inputCredential{
        border: 0px;
        margin: 6px 8px 0px 8px;
        padding: 6px;
    }
    
    .required{
        background-position: 10px top;
    }
    
    .button{
        border: 0px;
        margin: 8px 0px 0px 8px;
        width: 100px;
        height: 24px;
        cursor: pointer;
    }
    
    #registration{
        margin: 0px 0px 16px 0px;
        padding: 8px 0px 0px 0px;
        text-align: right;
    }
</style>
<div id='main-inner'>
    <div class='loginMain'>
        <?php
            echo $this->Form->create('User', array('action' => 'login', 'class' => 'loginForm'));
        ?>
        <div class='credential'>
            Συμπληρώστε το όνομα χρήστη και το συνθηματικό σας:
        </div>
        <div class='loginContent'>
            <div class='credential'>
                <?php
                    echo $this->Form->input('username',
                        array('label' => 'Όνομα χρήστη', 'class' => 'inputCredential'));
                ?>
            </div>
            <div class='credential'>
                <?php
                    echo $this->Form->input('password',
                        array('label' => 'Συνθηματικό', 'class' => 'inputCredential'));
                ?>
            </div>
            <div class='credential'>
                <?php
                    echo $this->Form->button('Είσοδος',
                        array( 'type' => 'submit', 'class' => 'button'));
                ?>
            </div>
        </div>
        <div id='registration'>
            <?php
                echo "Ανάρτηση ακινήτου<br /><br />";
                echo $this->Html->link('Εγγραφή ιδιώτη',
                    array('controller' => 'users', 'action' => 'registerowner'))
                    .'<br /><br />';
                echo $this->Html->link('Εγγραφή μεσιτικού γραφείου',
                    array('controller' => 'users', 'action' => 'registerrealestate'));
            ?> 
        </div>
        <?php
            echo $this->Form->end();
        ?>
    </div>
</div>

