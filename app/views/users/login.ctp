<div id='login-frame' class='loginFrame'>
	<div id='login-inner'>
		<?php
			echo $this->Session->flash('auth');
			echo $this->Form->create('User', array('action' => 'login'));
			echo $this->Form->inputs(array(
				'legend' => __('Είσοδος Χρήστη', true),
					'username',
					'password'));
			echo $this->Form->end('Login');
		?>
	</div>
</div>
