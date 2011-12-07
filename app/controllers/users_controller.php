<?php
Configure::load('authority');

class UsersController extends AppController{

	var $name = "Users";
    var $uses = array("Profile", "User", "Preference", "Municipality", "RealEstate");
    var $components = array('Token', 'Recaptcha.Recaptcha', 'Email');
    //var $helpers = array('RecaptchaPlugin.Recaptcha');
    var $helpers = array('Auth');

    function beforeFilter() {
        parent::beforeFilter();
        /* dont redirect automatically, needed for login() to work */
        $this->Auth->autoRedirect = false;

        $this->Auth->allow('publicTerms');
        $this->Auth->allow('faq');
        $this->Auth->allow('register');
        $this->Auth->allow('pdf');


        if( $this->params['action'] === 'register' && $this->Auth->user() ) {

            $this->cakeError( 'error403' );
        }
    }

    function login() {
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'login');
        $this->set('title_for_layout', 'Σύνδεση χρήστη');

        /*In case user try to login with some credentials
         *and terms has not accepted redirect him in terms action.
         *If rules has accepted redirect him to main page
         */
        if( isset( $this->data ) && $this->Auth->user('terms_accepted') === '0' ){
            $this->redirect( array( 'controller' => 'users', 'action' => 'terms' ) );

        } else if( isset( $this->data ) &&  $this->Auth->user( 'terms_accepted' === "1" ) ) {
            if ($this->Auth->user('enabled') == '0') {
                $this->Session->setFlash('Ο λογαριασμός σας δεν έχει ενεργοποιηθεί από τον διαχειριστή.',
                        'default', array('class' => 'flashRed'));
                $this->redirect($this->Auth->logout());
            }
            /* redirect in pre-fixed url */
            $this->redirect( $this->Auth->redirect() );
        }

    }


	function logout(){
		//Provides a quick way to de-authenticate someone,
        //and redirect them to where they need to go
        $this->Session->destroy();
		$this->redirect( $this->Auth->logout() );
	}
	
    function pdf($id = null) {
        $this->layout = false;
        $this->set('uid', $id);
    } 

    private function download($id = null) {
        App::import('Component', 'Pdf');
        $Pdf = new PdfComponent();
        $Pdf->filename = 'your_invoice'; // Without .pdf
        $Pdf->output = 'download';
        $Pdf->init();
        $Pdf->process(Router::url('/', true) . 'users/pdf/' . $id);
        $this->render(false);
    }

	function help(){
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'help');
        $this->set('title_for_layout', 'Αναφορά προβλήματος');

        if(isset($this->data)){
            $userid = $this->Auth->user('id');
            $username = $this->Auth->user('username');

            $formData = array();
            $formData['subject'] = $this->data['subject'];
            $formData['category'] = "bug";
            $formData['userid'] = $userid;
            $formData['username'] = $username;
            $formData['description'] = $this->data['description'];
            $result = $this->createIssue($formData);
            if($result){
                $this->Session->setFlash( 'Η αναφορά καταχωρήθηκε με επιτυχία', 'default',
                    array('class' => 'flashBlue'));
                $this->redirect('/');
            }else{
                $this->Session->setFlash( 'Η αναφορά δεν ήταν δυνατό να καταχωρηθεί.', 'default',
                    array('class' => 'flashRed'));
                $this->redirect('help');
            }
        }
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
            $this->redirect( $this->Auth->redirect() );
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

        $ldap_data = $this->Session->read('LdapData');

        /*
         * On production mode we expect users that log-in to the service
         * (not real-estate or users that only rent houses) to have their
         * data retreived from ldap.
         *
         * This isn't the case on development as we need to insert users
         * directly in database.
         *
         * If we are on development and ldap does not supply data we insert
         * dummy data for testing.
         *
         * Warning: if no data are sent from ldap on production mode we
         * end up with a broken user (no profile). We need to handle this
         * more gracefully. * FIXME *
         */
        if (Configure::read('debug') != 0 ) {
            if (! isset($ldap_data) ) {
                $ldap_data['first_name'] = 'firsname';
                $ldap_data['last_name'] = 'lastname';
                $ldap_data['email'] = 'roommates@teiath.gr';
            }
        }

        $profile["Profile"]["firstname"] = $ldap_data['first_name'];
        $profile["Profile"]["lastname"] = $ldap_data['last_name'];
        $profile["Profile"]["email"] = $ldap_data['email'];

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
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'register');
        $this->set('title_for_layout','Εγγραφή νέου χρήστη');
        $this->set('municipalities', $this->Municipality->find('list', array('fields' => array('name'))));
        if ($this->data) {
            // user must accept the real estate terms
            if ($this->data["User"]["estate_terms"] != "1") {
                $this->Session->setFlash("Πρέπει να αποδεχθείτε τους όρους χρήσης 
για να ολοκληρωθεί η εγγραφή σας στο σύστημα.", 'default', array('class' => 'flashRed'));
                $this->data['User']['password'] = $this->data['User']['password_confirm'] = "";
                return;
            }

            // check for valid captcha
            if (! $this->Recaptcha->verify()) {
                $this->Session->setFlash($this->Recaptcha->error, 'default', array('class' => 'flashRed'));
                $this->data['User']['password'] = $this->data['User']['password_confirm'] = "";
                return;
            }

            $userdata["User"]["username"] = $this->data["User"]["username"];
            $userdata["User"]["password"] = $this->data["User"]["password"];
            $userdata["User"]["password_confirm"] = $this->data["User"]["password_confirm"];
            $userdata["User"]["role"] = 'realestate';
            $userdata["User"]["banned"] = 0;
            /* terms are shown on register page and cannot proceed without accepting */
            $userdata["User"]["terms_accepted"] = 1;
            /* we need enabled = 0 because all users are enabled in db by default */
            $userdata["User"]["enabled"] = 0;

            $this->User->begin();
            /* try saving user model */
            if ($this->User->save($userdata) === false) {
                $this->User->rollback();
                // pass custom validation errors to view
                $user_errors = $this->User->validationErrors;
                $this->set('user_errors', $user_errors);
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

                    // get municipality (name) in order to print it onto the 
                    // email which is to be sent for registration approval
                    $municipality =
                        $this->data['RealEstate']['municipality_id'];

                    if( isset($municipality) ) {
                        $municipality =
                            $this->getMunicipalityData($municipality);
                    }

                    $this->set('data', $this->data );
                    $this->set('municipality', $municipality);
                    $this->notifyOfRegistration();
        
                    $this->download($uid);

                    $this->Session->setFlash("Η εγγραφή σας ολοκληρώθηκε με επιτυχία.", 'default', array('class' => 'flashBlue'));
                    $this->redirect('login');
                }
            }

            /* clear password fields */
            $this->data['User']['password'] = $this->data['User']['password_confirm'] = "";
        }

    }

    private function create_estate_profile($id, $data) {
        $realestate["RealEstate"]["firstname"] = $data["RealEstate"]["firstname"];
        $realestate["RealEstate"]["lastname"] = $data["RealEstate"]["lastname"];
        $realestate["RealEstate"]["company_name"] = $data["RealEstate"]["company_name"];
        $realestate["RealEstate"]["email"] = $data["RealEstate"]["email"];
        $realestate["RealEstate"]["phone"] = $data["RealEstate"]["phone"];
        $realestate["RealEstate"]["fax"] = $data["RealEstate"]["fax"];
        $realestate["RealEstate"]["afm"] = $data["RealEstate"]["afm"];
        $realestate["RealEstate"]["doy"] = $data["RealEstate"]["doy"];
        $realestate["RealEstate"]["address"] = $data["RealEstate"]["address"];
        $realestate["RealEstate"]["postal_code"] = $data["RealEstate"]["postal_code"];
        $realestate["RealEstate"]["municipality_id"] = $data["RealEstate"]["municipality_id"];
        $realestate["RealEstate"]["user_id"] = $id;

        if ( $this->RealEstate->save($realestate) === false) {
            return false;
        }
        return $this->RealEstate->id;
    }

    private function createIssue($data){
        if(isset($data)){
            $reqData['subject'] = $data['subject'];
            $reqData['description'] = "{$data['userid']} {$data['username']}\n";
            $reqData['description'] .= "{$data['category']}\n";
            $reqData['description'] .= $data['description'];
            $reqXml = $this->createXmlRequest($reqData);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://redmine.edu.teiath.gr/issues.xml");
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_USERPWD, "7806cada9458077b3251b341ff3ffc072987e5bc:password");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $reqXml);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($ch, CURLOPT_FAILONERROR,1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $result = curl_exec($ch);
            curl_close($ch);
            
            return $result;
        }else{
            return false;
        }
    }
    
    private function createXmlRequest($data){
        $req = "<?xml version=\"1.0\"?>";
        $req .= "<issue>";
        $req .= "<subject>{$data['subject']}</subject>";
        $req .= "<description>{$data['description']}</description>";
        $req .= "<project_id>8</project_id>"; // TODO: change project id
        $req .= "</issue>";
        
        return $req;
    }

    private function email_registration($id){
        $this->RealEstate->id = $id;
        $realEstate = $this->RealEstate->read();
        $this->Email->to = $realEstate['RealEstate']['email'];
        $this->Email->subject = 'Εγγραφή στην υπηρεσίας roommates ΤΕΙ Αθήνας';
        //$this->Email->replyTo = 'support@example.com';
        $this->Email->from = 'admin@roommates.edu.teiath.gr';
        $this->Email->template = 'registration';
        $this->Email->sendAs = 'both';
        $this->Email->send();
    }
    

    //sends email to the Authority to inform that a user has subscribed
    private function notifyOfRegistration() {
        $recipients = Configure::read('authority.activation_recipients');
        $subject = Configure::read('authority.activation_subject');
        if(empty($subject)) {
            $subject = 'Αίτησης εγγραφής στο roommates';
        }

        foreach($recipients as $to) {
            $this->Email->reset();
            $this->Email->to = $to;
            $this->Email->subject = $subject;
            $this->Email->from = 'admin@roommates.edu.teiath.gr';
            $this->Email->template = 'registered';
            $this->Email->layout = 'registered';
            $this->Email->sendAs = 'both';
        }
    }

    private function getMunicipalityData($municipality_id) {
        $this->loadModel('Municipality');
        $data = $this->Municipality->find('first', array(
            'conditions' => array('Municipality.id' => $municipality_id)
        ));
        return $data;
    }
}
?>

