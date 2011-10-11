<div id='login-frame' class='loginFrame'>
	<div id='login-inner'>
	   <?php
	      echo $this->Session->flash('auth');
              echo $this->Form->create('User', array('action' => 'login',"class" => "loginForm"));
	      echo $this->Form->label('Είσοδος Χρήστη');
	      echo $this->Form->input('username', array('label' => 'Όνομα χρήστη:' ) );
	      echo $this->Form->input('password', array('label' => 'Συνθηματικό:' ) );
	      echo $this->Form->end('Είσοδος');
	   ?>
	</div>
</div>
