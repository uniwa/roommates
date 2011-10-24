<?php
class  AppController extends Controller{

	var $components = array('Auth', 'Session', 'RequestHandler');
	var $helpers  = Array('Html', 'Form', 'Session','Auth');
	var $uses = array('User', 'Profile');

	function beforeFilter(){
		$this->Auth->loginError = "Δώστε έγκυρο όνομα χρήστη και συνθηματικό.";
		$this->Auth->authError = " ";

        $this->Auth->allow('publicTerms');
		// Define variables for active profiles and houses
		$active['houses'] = $this->Profile->find('count');//, array('conditions' => array('House.visible' => '1')));
		$active['profiles'] = $this->Profile->find('count', array('conditions' => array('Profile.visible' => '1')));
        $this->set('active', $active);

        /*
         * Redirects if:
         * 1) is not users actions
         * 2) is not rss file extension in /houses/index action
         * 3) user is alredy logged in and terms has not accepted from him
         */
        if( $this->params['controller'] != 'users'   
            && !( $this->params['controller'] == 'houses' && $this->params['action'] == 'index'
                     && $this->RequestHandler->isRss() ) && 
            ( $this->Auth->user() != null && $this->Auth->user('terms_accepted') === "0" )  ){

                  $this->redirect( array( 'controller' => 'users', 'action' => 'terms' ) );
              }
    }

	/*
	 * Insert into $this->data the Auth array. This Auth will be extracted 
	 * from custom helper class AuthHelper, to render this data in view 
	 */
	function beforeRender(){

		$this->data = am( $this->data, array( 'Auth' => $this->getAuthUserIds() ) );
	}

	/*
	 * Return basic info about logged in user
	 * Contains: Users id, Profile id and House id info
	 * More info will be in included shortly
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
