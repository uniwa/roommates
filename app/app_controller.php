<?php
class  AppController extends Controller{

	var $components = array('Auth', 'Session');
	var $Helpers  = Array('Html', 'Form', 'Session');
	var $uses = array('User');

	function beforeFilter(){

		$this->Auth->loginError = "Δώστε έγκυρο όνομα χρήστη και συνθηματικό.";
		$this->Auth->authError = "Δώστε έγκυρο όνομα χρήστη και συνθηματικό.";
	}

	/*
	 * Return basic info about logged in user
	 * Contains: User's Profile and House info
	 */ 
	protected function getAuthUserIds(){

		$user_id = $this->Auth->user('id');
		
		$this->loadModel('User');

		$this->User->id = $user_id;

		$user = $this->User->read();


		return array(  	'User' => array( 'id' => $user['User']['id'] ), 

				'Profile' =>array( 'id' => $user['Profile']['id'], 
					   'firstname' => $user['Profile']['firstname'],
					   'lastname' => $user['Profile']['lastname'] ),

				'House' => array('id' => $user['House'][0]['id'] ) );	
	
	}
}
?>
