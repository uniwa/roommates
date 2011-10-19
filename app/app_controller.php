<?php
class  AppController extends Controller{

	var $components = array('Auth', 'Session');
	var $helpers  = Array('Html', 'Form', 'Session','Auth');
	var $uses = array('User');

	function beforeFilter(){

		$this->Auth->loginError = "Δώστε έγκυρο όνομα χρήστη και συνθηματικό.";
		$this->Auth->authError = " ";
	}

	/*
	 * Insert into $this->data the Auth array this Auth will extracted 
	 * from custom helper class AuthHelper to render this data in view 
	 */
	function beforeRender(){

		$this->data = am( $this->data, array( 'Auth' => $this->getAuthUserIds() ) );
	}

	/*
	 * Return basic info about logged in user
	 * Contains: User's id Profile id and House id info
	 * In future will include more info
	 */ 
	//TODO: Rename to authArray and change protected to private
	protected function getAuthUserIds(){

		$user_id = $this->Auth->user('id');
		
		$this->loadModel('User');

		$this->User->id = $user_id;

		$user = $this->User->read();


		return array(  	'User' => array( 'id' => $user['User']['id'] ), 

				'Profile' =>array( 'id' => $user['Profile']['id'] ),

				'House' => array('id' => isset( $user['House'][0]['id'] )?$user['House'][0]['id']:NULL ) );	
	
	}
}
?>
