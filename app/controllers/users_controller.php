<?php


class UsersController extends AppController{

	var $name = "Users";
    var $uses = array("Profile", "User", "Preference", "Municipality", "RealEstate");
    var $components = array('Token', 'Recaptcha.Recaptcha');
    //var $helpers = array('RecaptchaPlugin.Recaptcha');

    function beforeFilter() {
        parent::beforeFilter();
        /* dont redirect automatically, needed for login() to work */
        $this->Auth->autoRedirect = false;

        $this->Auth->allow('publicTerms');
        $this->Auth->allow('faq');
        $this->Auth->allow('register');
    }

    function login() {
        /*In case user try to login with some credentials
         *and terms has not accepted redirect him in terms action.
         *If rules has accepted redirect him to main page
         */

        if( isset( $this->data ) && $this->Auth->user('terms_accepted') === '0' ){

            $this->redirect( array( 'controller' => 'users', 'action' => 'terms' ) );

        } else if( isset( $this->data ) &&  $this->Auth->user( 'terms_accepted' === "1" ) ) {
            /* redirect in pre-fixed url */
            $this->redirect( $this->Auth->redirect() );
        }

    }


	function logout(){
		//Provides a quick way to de-authenticate someone,
		//and redirect them to where they need to go
		$this->redirect( $this->Auth->logout() );
	}

    function terms(){
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'users_terms');
        $this->set('title_for_layout','Όροι χρήσης');

        /*When login or before filter snippet redirect to terms action
         *user by default takes a form with terms. if accept the terms
         *then terms action creates profile for new user else redirect
         *him to hell*/

        /*Checks if user has accepted the terms*/
        if( $this->Auth->user( "terms_accepted") === "1" ) {


            $this->Session->setFlash( 'Οι όροι έχουν γίνει αποδεκτοί', 'default',
                array('class' => 'flashBlue'));
            $this->redirect( $this->referer() );
        }

        $this->layout = 'terms';
        $data = $this->data;
        if( !empty( $data ) ){

            if( $data['User']['accept'] == 1 ){

                $this->User->id = $this->Auth->user('id');
                $user = $this->User->read();
                /* Update user field which determines that user accepted the terms*/
                $user["User"]["terms_accepted"] = 1;
                $this->User->save($user, false);
                /*refresh session for this field*/
                $this->Auth->Session->write('Auth.User.terms_accepted', "1" );


                if( $user["Profile"]["id"] == null ) {
                    $pref_id = $this->create_preferences();
                    $profile_id = $this->create_profile($this->User->id, $pref_id);
                    $this->redirect(array('controller' => 'profiles', 'action' => 'edit', $profile_id));
                }

            } else {

                $this->Session->setFlash('Δεν έχετε δεχτεί τους όρους χρήσης', 'default',
                array('class' => 'flashRed'));
                $this->redirect ( array( 'controller' => 'users', 'action' => 'logout') );
            }

        }

    }

    function faq() {
        $this->set('title_for_layout','Συχνές ερωτήσεις');

        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'users_faq');
    }

    function publicTerms() {
        $this->set('title_for_layout','Όροι χρήσης');

        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'users_terms');
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
        /* house preferences - only fields that don't default to NULL */
        $pref["Preference"]["pref_furnitured"] = 2;
        $pref["Preference"]["pref_has_photo"] = 0;
        $pref["Preference"]["pref_disability_facilities"] = 0;

        if ( $this->Preference->save($pref) === False ) {
            $this->Preference->rollback();
            return NULL;
        }
        else {
            $this->Preference->commit();
            return $this->Preference->id;
        }
    }

    function register() {
        $this->set('title_for_layout','Εγγραφή νέου χρήστη');
        if ($this->data) {
            if (! $this->Recaptcha->verify()) {
                $this->Session->setFlash($this->Recaptcha->error);
                $this->data['User']['password'] = $this->data['User']['password_confirm'] = "";
                return;
            }
            // TODO: check if accepted terms (depends on real estate terms story card)

            $userdata["User"]["username"] = $this->data["User"]["username"];
            $userdata["User"]["password"] = $this->data["User"]["password"];
            $userdata["User"]["password_confirm"] = $this->data["User"]["password_confirm"];
            $userdata["User"]["role"] = 'realestate';
            $userdata["User"]["banned"] = 0;
            /* terms are shown on register page and cannot proceed without accepting */
            $userdata["User"]["terms_accepted"] = 1;
            /* we need enabled = 0 because all users are enabled in db by default */
            $userdata["User"]["enabled"] = 0;

            $this->User->set($userdata);
            if (!$this->User->validates()) {
                $user_errors = $this->User->invalidFields();
                $this->set('user_errors', $user_errors);
                $this->data['User']['password'] = $this->data['User']['password_confirm'] = "";
                return;
            }

            $this->User->begin();
            /* try saving user model */
            if ($this->User->save($userdata) === false) {
                $this->User->rollback();
            }
            else {
                /* try saving real estate profile */
                $uid = $this->User->id;
                if ( $this->create_estate_profile($uid, $this->data) == false) {
                    $this->User->rollback();
                }
                else {
                    $this->User->commit();
                    // registration successfull - send to login
                    // TODO: maybe redirect to some public page
                    $this->Session->setFlash("Registration successfull.");
                    $this->redirect('login');
                }
            }

            /* clear password fields */
            $this->data['User']['password'] = $this->data['User']['password_confirm'] = "";
        }
        $this->set('municipalities', $this->Municipality->find('list', array('fields' => array('name'))));
    }

    private function create_estate_profile($id, $data) {
        $realestate["RealEstate"]["firstname"] = $data["User"]["firstname"];
        $realestate["RealEstate"]["lastname"] = $data["User"]["lastname"];
        $realestate["RealEstate"]["company_name"] = $data["User"]["company_name"];
        $realestate["RealEstate"]["email"] = $data["User"]["email"];
        $realestate["RealEstate"]["phone"] = $data["User"]["phone"];
        $realestate["RealEstate"]["fax"] = $data["User"]["fax"];
        $realestate["RealEstate"]["afm"] = $data["User"]["afm"];
        $realestate["RealEstate"]["doy"] = $data["User"]["doy"];
        $realestate["RealEstate"]["address"] = $data["User"]["address"];
        $realestate["RealEstate"]["postal_code"] = $data["User"]["postal_code"];
        $realestate["RealEstate"]["municipality_id"] = $data["User"]["municipality_id"];
        $realestate["RealEstate"]["user_id"] = $id;

        if ( $this->RealEstate->save($realestate) === false) {
            return false;
        }
        return $this->RealEstate->id;
    }

}
?>
