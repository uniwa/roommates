<?php
Configure::load('authority');

class UsersController extends AppController{

	var $name = "Users";
    var $uses = array("Profile", "User", "Preference", "Municipality", "RealEstate");
    var $components = array('Token', 'Recaptcha.Recaptcha', 'Email');
    //var $helpers = array('RecaptchaPlugin.Recaptcha');
    var $helpers = array('Auth', 'Html');

    function beforeFilter() {
        parent::beforeFilter();
        /* dont redirect automatically, needed for login() to work */
        $this->Auth->autoRedirect = false;

        $this->Auth->allow('publicTerms');
        $this->Auth->allow('faq');
        $this->Auth->allow('register');
        $this->Auth->allow('pdf');
        $this->Auth->allow('registerowner');
        $this->Auth->allow('registerrealestate');

        if( $this->params['action'] === 'register' && $this->Auth->user() ) {

            $this->cakeError( 'error403' );
        }
    }

    function login() {
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'login');
        $this->set('title_for_layout', 'Σύνδεση χρήστη');

        /*In case user tries to login with some credentials
         *while terms are not accepted, redirect him to terms action.
         *If rules are accepted, redirect him to main page
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
            $this->User->id = $this->Auth->user('id');  //target correct record
            $this->User->saveField('last_login', date(DATE_ATOM));  //save login time
            $this->redirect( $this->Auth->redirect() );
        }
    }

	function logout(){
		//Provides a quick way to de-authenticate someone,
        //and redirect them to where they need to go
        $this->Session->destroy();
		$this->redirect( $this->Auth->logout() );
	}

    // Produces a registration application html form for the given user $id.
    // Currently, only users of role 'RealEstate' are supported. This function
    // is invoked by (vendors/html2ps) PdfComponent's member function process()
    // in order to produce the pdf version of that html. Only local access
    // should be allowed to this function (by means of .htaccess or otherwise).
    function pdf($id = null) {

        if( is_null($id) )    return;

        $this->User->id = $id;
        $user = $this->User->read();

        $this->set('data', $user);

        // get municipality (name) in order to print it onto the 
        // email which is to be sent for registration approval
        $municipality = $user['RealEstate']['municipality_id'];

        if( isset($municipality) ) {
            $municipality = $this->getMunicipalityData($municipality);
        }
        $this->set('municipality', $municipality);

        $this->layout = false;
    } 

    // Initiates the creation of the pdf equivalent of the application form.
    private function getRegistrationPdf($id) {

        if(is_null($id))   return;

        App::import('Component', 'Pdf');
        $filename = 'user_id_' . $id;
        // files are stored under html2ps/out/ directory
        $outDir = APP.'vendors'.DS.'html2ps'.DS.'out'.DS;
        $filepath = $outDir . $filename . '.pdf';

        $Pdf = new PdfComponent();
        $Pdf->filename = $filename; // Without .pdf
        $Pdf->output = 'file';
        $Pdf->init();
        $Pdf->process(Router::url('/', true) . 'users/pdf/' . $id);

        return $filepath;
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
        $profile["Profile"]["visible"] = 1;
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

           App::import( 'Lib', 'ldap' );
            $ldap = new ldap();

            if( $ldap->uidCheck( $this->data['User']['username'] ) ){

                $this->Session->setFlash('Το όνομα χρήστη που εισήχθει χρησιμοποιείται' ,'default', array('class'=>'flashRed'));
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

                    // uses $this->data
                    $this->notifyOfRegistration();

                    //"Η εγγραφή σας ολοκληρώθηκε με επιτυχία."
                    $this->Session->setFlash("Η εγγραφή πραγματοποιήθηκε."
                            ." Ελέγξτε την εισερχόμενη αλληλογραφία σας.",
                        'default', array('class' => 'flashBlue'));

                    $this->redirect('login');
                }
            }

            /* clear password fields */
            $this->data['User']['password'] = $this->data['User']['password_confirm'] = "";
        }
    }

    function registerowner(){
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'register');
        $this->set('title_for_layout','Εγγραφή νέου ιδιώτη');
        $this->set('municipalities', $this->Municipality->find('list', array('fields' => array('name'))));
        $this->register();
    }

    function registerrealestate(){
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'register');
        $this->set('title_for_layout','Εγγραφή νέου μεσιτικού γραφείου');
        $this->set('municipalities', $this->Municipality->find('list', array('fields' => array('name'))));
        $this->register();
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
        $realestate["RealEstate"]["type"] = $data["RealEstate"]["type"];

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

    // Simple wrapper function for sending email both as html and plain text.
    private function sendEmail($from, $to, $subject, $body, $attachments,
    $template, $layout ) {
        $this->Email->reset();
        $this->Email->from = $from;
        $this->Email->to = $to;
        $this->Email->subject = $subject;
        $this->set('content_for_layout', $body);
        $this->Email->template = $template;
        $this->Email->layout = $layout;
        if( isset($attachments) ) {
            $this->Email->attachments = $attachments;
        }

        $this->Email->sendAs = 'both';
        $this->Email->send();
    }

    // Sends emails to applicant and authority.
    private function notifyOfRegistration() {
        if( empty( $this->data) )   return;

        // get municipality (name) in order to print it onto the 
        // email which is to be sent for registration approval
        $municipality = $this->data['RealEstate']['municipality_id'];
        if( isset($municipality) ) {
            $municipality = $this->getMunicipalityData($municipality);
        }
        $this->set('data', $this->data );
        $this->set('municipality', $municipality);

        $this->notifyAuthority();

        $filepath = $this->getRegistrationPdf($this->User->id);
        if( file_exists($filepath) ) {

            $attachments = array('roommates.pdf' => $filepath);
            $this->notifyApplicant(
                $this->data['RealEstate']['email'], $attachments);
        }
    }

    // Sends the registration application form to the supplied $email address.
    // The $attachments is an array of files to be included.
    private function notifyApplicant($email, $attachments) {
        if( empty($email) ) return;

        $subject = Configure::read('registration.applicant_subject');
        if( empty($subject) ) {
            $subject = 'Αίτηση εγγραφής στην υπηρεσία roommates';
        }
        $body =
            'Το παρόν μήνυμα εστάλη ως αποτέλεσμα της εγγραφής σας ως πάροχο'
            . ' χώρων στέγασης στο σύστημα εύρεσης συγκατοίκων του ΤΕΙ'
            . ' Αθήνας. Το συνημμένο αρχείο αποτελεί την αίτηση η οποία πρέπει'
            . ' να ελεγχθεί, υπογραφεί και υποβληθεί στην αρμόδια αρχή'
            . ' έγκρισης μέσω τηλεομοιότυπου (fax).';

        $this->sendEmail(
            'admin@roommates.edu.teiath.gr', $email, $subject, $body,
            $attachments, 'default', 'default' );
    }

    // Sends email to the Authority to inform of user registration. The
    // recipients' emails are read from the Configuration. The $attachments
    // array is optional, but any file included *must* be accessible.
    private function notifyAuthority($attachments=null) {
        $recipients = Configure::read('registration.authority_recipients');
        $subject = Configure::read('registration.authority_subject');
        if( empty($subject) ) {
            $subject = 'Αίτηση εγγραφής στην υπηρεσία roommates';
        }

        foreach( $recipients as $to ) {
            $this->sendEmail(
                'admin@roommates.edu.teiath.gr', $to, $subject, '',
                $attachments, 'registered', 'registered' );
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

