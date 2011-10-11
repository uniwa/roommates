<?php
class UsersController extends AppController{

	var $name = "Users";
    var $uses = array("Profile", "User");
		
    function beforeFilter() {
	parent::beforeFilter();
        /* dont redirect automatically, needed for login() to work */
        $this->Auth->autoRedirect = false;
    }

	function login() {
        if( !(empty($this->data)) && $this->Auth->user() ) {
            /* get login id */
            $login =  $this->Auth->user();
            $this->User->id = $login["User"]["id"];

            /* load user model */
            $user = $this->User->read();

            /* if user does not have a profile, create one */
            if ( $user["Profile"]["id"] == NULL ) {
                $this->create_profile($this->User->id);
            }
            $this->redirect($this->Auth->redirect());
        }
	}


	function logout(){
		//Provides a quick way to de-authenticate someone, 
		//and redirect them to where they need to go
		$this->redirect( $this->Auth->logout() );
	}

    function create_profile($id) {
        $this->Profile->begin();
        $this->Profile->create();

        /* TODO: get this info from LDAP */
        $profile["Profile"]["firstname"] = "dummyname";
        $profile["Profile"]["lastname"] = "lname";
        $profile["Profile"]["email"] = "test@teiath.gr";

        /* dummy sane data - user will edit his profile after login */
        $profile["Profile"]["dob"] = date('Y') - 18;
        $profile["Profile"]["gender"] = 0;
        $profile["Profile"]["visible"] = 1;
        $profile["Profile"]["user_id"] = $id;

        if ( $this->Profile->save($profile) === False) {
            $this->Profile->rollback();
        }
        else {
            $this->Profile->commit();
        }
    }
}
?>
