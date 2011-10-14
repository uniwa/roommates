<div id='login-frame' class='loginFrame'>
	<div id='login-inner'>
	   <?php
	      echo $this->Session->flash('auth');
              echo $this->Form->create('User', array('action' => 'login',"class" => "loginForm"));
	      echo $this->Form->label('Είσοδος χρήστη');
	      echo $this->Form->input('username', array('label' => 'Όνομα χρήστη:' ) );
	      echo $this->Form->input('password', array('label' => 'Συνθηματικό:' ) );
          echo $this->Form->end('Είσοδος');
          echo '<div class=rssIcon>';
          echo $this->Html->link(
                $this->Html->image( "rss.png",
                array( 'url' => array( 'controller' => 'houses', 'action' => 'index', 'ext' => 'rss') ) ),
                "houses/index.rss",
                array( 'escape' => false) );
          echo '</div>';
	   ?>
	</div>
</div>
