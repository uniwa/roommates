<div id='login-frame' class='loginFrame'>
    <h1 class="login-title">Είσοδος χρήστη</h1>
    <div id='login-inner'>
        <div id="login-main">
        <?php
            echo $this->Session->flash('auth');
            echo $this->Form->create('User', array('action' => 'login',"class" => "loginForm"));
            echo $this->Form->input('username', array('label' => 'Όνομα χρήστη:' ) );
            echo $this->Form->input('password', array('label' => 'Συνθηματικό:' ) );
            echo $this->Form->end('Είσοδος');
        ?>
        </div>
    </div>
    <div class="rssIcon">
        <?php echo $this->Html->image(  "rss.png",
                                        array('url' => array(   'controller' => 'houses',
                                                                'action' => 'index',
                                                                'ext' => 'rss'  )));
        ?>
    </div>
</div>
