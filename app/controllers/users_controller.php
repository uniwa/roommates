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
        /*In case user try to login with some credentials
         *and terms has not accepted redirect him in terms action.
         *If rules has accepted redirect him to main page
         */

        if( isset( $this->data ) && $this->Auth->user('terms_accepted') === '0' ){
            var_dump($this->Auth->user() );

            $this->redirect( array( 'controller' => 'users', 'action' => 'terms' ) );

        } else if( $this->Auth->user( 'terms_accepted' === "1" ) ) {

            $this->redirect( '/' );
        }

    }


	function logout(){
		//Provides a quick way to de-authenticate someone,
		//and redirect them to where they need to go
		$this->redirect( $this->Auth->logout() );
	}

    function terms(){

        /*When login or before filter snippet redirect to terms action
         *user by default takes a form with terms. if accept the terms
         *then terms action creates profile for new user else redirect
         *him to hell*/

        /*Checks if user has accepted the terms*/
        if( $this->Auth->user( "terms_accepted") === "1" ) {


            $this->Session->setFlash( 'Οι όροι έχουν γίνει αποδεκτοί', 'default' );
            $this->redirect( $this->referer() );
        }

        $this->layout = 'terms';
        $data = $this->data;
        if( !empty( $data ) ){

            if( $data['User']['accept'] == 1 ){

                $this->User->id = $this->Auth->user('id');
                $user = $this->User->read();
                /* Update user field which determines that user accepted the terms*/
                $this->User->set(  'terms_accepted', "1"  );
                $this->User->save();
                /*refresh session for this field*/
                $this->Auth->Session->write('Auth.User.terms_accepted', "1" );


                if( $user["Profile"]["id"] == null ) {
                    $pref_id = $this->create_preferences();
                    $profile_id = $this->create_profile($this->User->id, $pref_id);
                    $this->redirect(array('controller' => 'profiles', 'action' => 'edit', $profile_id));
                }

            } else {

                $this->Session->setFlash('Δεν έχετε δεχτεί τους όρους χρήσης', 'default' );
                $this->redirect ( array( 'controller' => 'users', 'action' => 'logout') );
            }

        }

    }

       function publicTerms(){

        // $this->layout = 'terms';
        $data = $this->data;
        }




 private function create_profile($id, $pref_id) {
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

    private function create_preferences() {
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
            $this->Preference->rollback();
            return NULL;
        }
        else {
            $this->Preference->commit();
            return $this->Preference->id;
        }
    }

}
?>
