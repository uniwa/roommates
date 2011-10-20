<?php
class UsersController extends AppController{

	var $name = "Users";
    var $uses = array("Profile", "User", "Preference");
    var $components = array('Token');
		
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
                $pref_id = $this->create_preferences();
                $profile_id = $this->create_profile($this->User->id, $pref_id);
                $this->redirect(array('controller' => 'profiles', 'action' => 'edit', $profile_id));
            }
            $this->redirect($this->Auth->redirect());
        }
	}


	function logout(){
		//Provides a quick way to de-authenticate someone, 
		//and redirect them to where they need to go
		$this->redirect( $this->Auth->logout() );
	}

    function create_profile($id, $pref_id) {
        $this->Profile->begin();
        $this->Profile->create();

        /* TODO: get this info from LDAP */
        $profile["Profile"]["firstname"] = "firstname";
        $profile["Profile"]["lastname"] = "lastname";
        $profile["Profile"]["email"] = "test@teiath.gr";

        /* dummy sane data - user will edit his profile after login */
        $profile["Profile"]["dob"] = date('Y') - 18;
        $profile["Profile"]["gender"] = 0;
        $profile["Profile"]["visible"] = 0;
        $profile["Profile"]["user_id"] = $id;
        /* supplied by create_preferences() */
        $profile["Profile"]["preference_id"] = $pref_id;
        $profile["Profile"]["token"] = $this->Token->generate($id);

        if ( $this->Profile->save($profile) === False) {
            $this->Profile->rollback();
        }
        else {
            $this->Profile->commit();
        }
        return $this->Profile->id;
    }

    function create_preferences() {
        $this->Preference->begin();
        $this->Preference->create();
        $pref["Preference"]["age_min"] = NULL;
        $pref["Preference"]["age_max"] = NULL;
        $pref["Preference"]["pref_gender"] = 2;
        $pref["Preference"]["pref_smoker"] = 2;
        $pref["Preference"]["pref_pet"] = 2;
        $pref["Preference"]["pref_child"] = 2;
        $pref["Preference"]["pref_couple"] = 2;

        if ( $this->Preference->save($pref) === False ) {
            $this->Preferene->rollback();
            return NULL;
        }
        else {
            $this->Preference->commit();
            return $this->Preference->id;
        }
    }
}
?>
