<?php
Configure::load('authority');

class UsersController extends AppController{

	var $name = "Users";
    var $uses = array("Profile", "User", "Preference", "Municipality", "RealEstate");
    var $components = array('Token', 'Recaptcha.Recaptcha', 'Email', 'RequestHandler');
    //var $helpers = array('RecaptchaPlugin.Recaptcha');
    var $helpers = array('Auth', 'Html', 'Xml', 'Javascript');

    function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow('handleGetRequest');

        /* dont redirect automatically, needed for login() to work */
        $this->Auth->autoRedirect = false;

        $this->Auth->allow('publicTerms');
        $this->Auth->allow('api');
        $this->Auth->allow('faq');
        $this->Auth->allow('register');
        $this->Auth->allow('pdf');
        $this->Auth->allow('registerowner');
        $this->Auth->allow('registerrealestate');
        $this->Auth->allow('aboutstudents');
        $this->Auth->allow('aboutowners');

        if( $this->params['action'] === 'register' ) {
            if ($this->Auth->user() || $this->Auth->User('role') != 'admin') {

                $this->cakeError( 'error403' );
            }
        }
    }

    function aboutstudents(){
        $this->set('title_for_layout', 'Υπηρεσίες για φοιτητές');
    }
    
    function aboutowners(){
        $this->set('title_for_layout', 'Υπηρεσίες για ιδιώτες και μεσιτικά γραφεία');
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
        if(isset( $this->data ) && $this->Auth->user()) {
$this->log('user '.$this->data['User']['username'].' login submit', 'info');
            if($this->Auth->user('terms_accepted') === '0' ){
                $this->Auth->Session->write('Auth.User.terms_flag', false );
                $this->redirect( array( 'controller' => 'users', 'action' => 'terms' ) );
            } elseif ($this->Auth->user('terms_accepted') == '1') {
                // save login time
                $this->User->id = $this->Auth->user('id');
                $this->User->saveField('last_login', date(DATE_ATOM));
            } else {
                if ($this->Auth->user('enabled') == '0') {
                    $this->Session->setFlash('Ο λογαριασμός σας δεν έχει ενεργοποιηθεί από τον διαχειριστή.',
                            'default', array('class' => 'flashRed'));
                    $this->redirect($this->Auth->logout());
                }
            }
            /* redirect in pre-fixed url */
            $this->redirect( $this->Auth->redirect() );
        }
    }

	function logout(){
		//Provides a quick way to de-authenticate someone,
        //and redirect them to where they need to go
$this->log('user '.$this->Auth->User('username').' logout', 'info');
        $this->Session->destroy();
		$this->redirect( $this->Auth->logout() );
	}

    // Transition freshman users from temporary account to ldap account
    function transition() {
        // define common redirect targets
        $target_transition = array(
            'controller' => 'users',
            'action' => 'transition');
        $target_profile = array(
            'controller' => 'profiles',
            'action' => 'view',
            /* before using this 'URL', the profile id must be set */);

        // redirect non-freshman users to homepage
        if (!$this->Auth->user('fresh')) {
            $this->redirect('/');
        }

        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'transition');
        $this->set('title_for_layout', 'Μετάβαση σε λογαριασμό του ΤΕΙ Αθήνας');

        if (isset($this->data) && $this->Auth->user()) {
            // check if username already exists in db and display warning
            $this->User->recursive = -1;
            $params = array(
                'conditions' => array(
                    'username' => $this->data['User']['username']));
            $user = $this->User->find('first', $params);

            if ($user) {
                $msg = "Υπάρχει ήδη χρήστης με το όνομα '{$user['User']['username']}' στην υπηρεσία";
                $this->Session->setFlash($msg, 'default',
                    array('class' => 'flashRed'));
                $this->redirect($target_transition);
            }

            // verify supplied credentials against ldap
            $userdata = array(
                'User.username' => $this->data['User']['username'],
                'User.password' => $this->data['User']['ldap_password']);
            $ldapInfo = $this->Auth->_ldapAuth($userdata);
            if (!$ldapInfo) {
                $msg = "Λάθος όνομα χρήστη ή/και κωδικός πρόσβασης!";
                $msg .= "<br />Επιβεβαιώστε τα στοιχεία πρόσβασης και δοκιμάστε ξανά.";
                $this->Session->setFlash($msg, 'default',
                    array('class' => 'flashRed'));
                $this->redirect($target_transition);
            }

            // replace username, email, firstname, lastname, flag & empty password
            unset($ldapInfo['name']);
            $ldapInfo['user_id'] = $this->Auth->user('id');
            $ldapInfo['username'] = $this->data['User']['username'];
            $replaced = $this->replaceUserInfo($ldapInfo);

            if ($replaced) {
                $msg = "Η μετάβαση στο λογαριασμό του Ιδρύματος ολοκληρώθηκε με επιτυχία.";
                $msg .= "<br />Παρακαλώ αποσυνδεθείτε και ξανασυνδεθείτε με τα νέα στοιχεία πρόσβασης.";
                $this->Session->setFlash($msg, 'default',
                    array('class' => 'flashBlue'));

                $profile_id = $this->Profile->get_id($this->Auth->user('id'));
                $target_profile[] = $profile_id;
                $target = $target_profile;
            } else {
                $msg = "Δεν ήταν δυνατή η μετάβαση στο λογαριασμό του Ιδρύματος.";
                $msg .= "<br />Παρακαλώ επιβεβαιώστε τα στοιχεία πρόσβασης και προσπαθείστε ξανά.";
                $this->Session->setFlash($msg, 'default',
                    array('class' => 'flashRed'));
                $target = $target_transition;
            }

            $this->redirect($target);
        }
    }

    // Replace user info, including profile info with data from LDAP
    // $data contains the following:
    // user_id, username, first_name, last_name, email
    private function replaceUserInfo($data) {
        // get current user info
        $this->User->id = $data['user_id'];
        $this->User->recursive = 1;
        $user = $this->User->read();

        if (!empty($user)) {
            // unset unneeded fields
            unset($user['RealEstate']);
            unset($user['House']);

            // replace with new info
            $user['User']['username'] = $data['username'];
            $user['User']['password'] = '';
            $user['User']['fresh'] = 0;
            $user['Profile']['firstname'] = $data['first_name'];
            $user['Profile']['lastname'] = $data['last_name'];
            $user['Profile']['email'] = $data['email'];

            $options = array('validate' => false);
            $saved = $this->User->saveAll($user, $options);
            if ($saved) {
                // replace in session variables
                $this->Session->write('Auth.User.username', $data['username']);
                $this->Session->write('Auth.User.fresh', 0);
            }

            return $saved;
        }

        return false;
    }

    // Enables currently logged in 'admin' user to act with the permissions of
    // $id user. Currently, $id may only belong to a 'realestate' of type
    // 'owner'. If this function is invoked by a non-admin user (with or without
    // specifying an $id), the account will be reset to its initial permissions,
    // if applicable.
    function switchUser($id=null) {

        if( $this->Session->read('Auth.User.role') != 'admin' ) {

            // user may be a 'masked' admin (an admin logged in as realestate)
            // if so, re-acquire their account
            if( $this->Session->check('Manager.id') ) {

                $managerId = $this->Session->read('Manager.id');
                $managerRole = $this->Session->read('Manager.role');
                $managerUsername = $this->Session->read('Manager.username');

                // change Session Auth variable to re-acquire admin permissions
                $this->alterAuth($managerId, $managerRole, $managerUsername);

                $redirect_url =
                    $this->Session->check('Manager.return_url') ?
                    $this->Session->read('Manager.return_url') :
                    array(
                        'controller' => 'admins',
                        'action' => 'manage_realestates');

                // ensure that topuser.ctp will now display link to (casual)
                // logout
                $this->Session->delete('Manager');
                $this->redirect($redirect_url);
            }
            $this->cakeError('error404');
        }

        // make sure that no $id of improper account was requested
        $user = $this->ascertainManageable($id, 'owner');
        if( empty($user) )   $this->cakeError('error404');

        $this->transmuteRole($user);
        $this->redirect(array('controller' => 'pages', 'action' => 'display'));
    }

    // Alters id, role and username under 'Auth.User' session variable,
    // effectively changing the permissions of the currently logged in user to
    // those of the supplied one.
    private function alterAuth($id, $role, $username) {
        $this->Session->write('Auth.User.id', $id);
        $this->Session->write('Auth.User.role', $role);
        $this->Session->write('Auth.User.username', $username);
    }

    // Returns the realestate data that correspond to the supplied $user_id
    // given that it ($user_id) actually belongs to a $type realestate account.
    // This is the function to alter in order to weave a more sophisticated
    // (or intricate) record approval policy.
    private function ascertainManageable($user_id, $type) {
        return $this->RealEstate->find('first', array('conditions' => array(
            'RealEstate.user_id' => $user_id, 'RealEstate.type' => $type)));
    }

    // Does the actual switching of account by copying the current user's data
    // into the 'Manager' session variable and replacing 'Auth.User.' -id
    // -username and -role session variables with the $user info supplied.
    // $user must contain: id, role and username
    private function transmuteRole($user) {
        $managerId = $this->Session->read('Auth.User.id');
        $managerRole = $this->Session->read('Auth.User.role');
        $managerUsername = $this->Session->read('Auth.User.username');

        $userId = $user['User']['id'];
        $userUsername = $user['User']['username'];
        $userRole = $user['User']['role'];

        // store current user data so as to switch back to this account
        $this->Session->write('Manager.id', $managerId);
        $this->Session->write('Manager.role', $managerRole);
        $this->Session->write('Manager.username', $managerUsername);

        // url to redirect admin after they logout from the realestate account
        $this->Session->write( 'Manager.return_url', Router::url( array(
            'controller' => 'admins', 'action' => 'manage_realestates'), true));

        $this->alterAuth($userId, $userRole, $userUsername);
    }

    // Produces a registration application html form for the given user $id.
    // Currently, only users of role 'RealEstate' are supported. This function
    // is invoked by (vendors/html2ps) PdfComponent's member function process()
    // in order to produce the pdf version of that html. This function requires
    // a 'hash' parameter to be specified in the url the contents of which
    // should match the value returned by $this->getDataHash() of the supplied
    // $id data.
    function pdf($id=null) {

        if( is_null($id) )    return;

        if( empty( $this->params['url']['hash'] ) ) {
            $this->cakeError('error404');
        }

        $this->User->id = $id;
        $user = $this->User->read();

        if( empty($user) ) {
            $this->cakeError('error404');
        }

        $this->set('data', $user);
        $this->set(
            'authority_fax',
            Configure::read('registration.authority_fax'));

        $valid = $this->getDataHash($user);
        $hash = $this->params['url']['hash'];
        if( $hash !== $valid )  $this->cakeError('error404');

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
    // Returns the full path to the file created.
    private function getRegistrationPdf($data) {

        if(is_null($data))   return;

        $id = $data['User']['id'];

        App::import('Component', 'Pdf');
        $filename = 'user_id_' . $id;

        $outDir = constant('HTML2PS_PDF_OUT_DIR');

        $filepath = $outDir . $filename . '.pdf';

        $hash = $this->getDataHash($data);

        $Pdf = new PdfComponent();
        $Pdf->filename = $filename; // Without .pdf
        $Pdf->output = 'file';
        $Pdf->init();
        $Pdf->process(
            Router::url('/', true) . 'users/pdf/' . $id . '?hash='. $hash);

        return $filepath;
    }

	function help(){
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'help');
        $this->set('title_for_layout', 'Αναφορά προβλήματος');
        $issuesCategories = array('τεχνικό', 'μέριμνα');
        $this->set('issues_categories', $issuesCategories);

        if(isset($this->data)){
            $userid = $this->Auth->user('id');
            $username = $this->Auth->user('username');

            $formData = array();
            $formData['subject'] = $this->data['subject'];
            $formData['category'] = $issuesCategories[$this->data['category']];
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
                } else {
                    // if profile already exists for this user, get its id
                    $profile_id = $this->Profile->get_id($this->User->id);
                }

                $this->redirect(array('controller' => 'profiles',
                                      'action' => 'edit', $profile_id));
            } else {

                $this->Session->setFlash('Δεν έχετε δεχτεί τους όρους χρήσης', 'default',
                array('class' => 'flashRed'));
                $this->redirect ( array( 'controller' => 'users', 'action' => 'logout') );
            }

        }

    }

    function api() {
        $this->set('title_for_layout','Υπηρεσίες διαλειτουργικότητας (web services)');

        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'users_api');
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
        /*
        if (Configure::read('debug') != 0 ) {
            if (! isset($ldap_data) ) {
                $ldap_data['first_name'] = 'firsname';
                $ldap_data['last_name'] = 'lastname';
                $ldap_data['email'] = 'roommates@teiath.gr';
            }
        }
         */

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

        // get default values in order to create an empty profile
        $pref = array('Preference' => $this->Preference->defaults);

        if ( $this->Preference->save($pref) === False ) {
            $this->Preference->rollback();
            return NULL;
        }
        else {
            $this->Preference->commit();
            return $this->Preference->id;
        }
    }

    private function register($from_admin = false) {
        // core register function
        //
        // $from_admin parameter shows that admin registers
        // another user, used for bypassing legal notes and
        // captcha field

        if ($this->data) {
            if (! $from_admin) {
                // user must accept the real estate terms
                if ($this->data["User"]["estate_terms"] != "1") {
                    $this->Session->setFlash("Πρέπει να αποδεχθείτε τους όρους χρήσης
    για να ολοκληρωθεί η εγγραφή σας στο σύστημα.", 'default', array('class' => 'flashRed'));
                    $this->data['User']['password'] = $this->data['User']['password_confirm'] = "";
                    return;
                }
            }

            // check for valid captcha
            if (! $from_admin) {
                if (! $this->Recaptcha->verify()) {
                    $this->Session->setFlash($this->Recaptcha->error, 'default', array('class' => 'flashRed'));
                    $this->data['User']['password'] = $this->data['User']['password_confirm'] = "";
                    return;
                }
            }

            $userdata["User"]["username"] = $this->data["User"]["username"];
            $userdata["User"]["password"] = $this->data["User"]["password"];
            $userdata["User"]["password_confirm"] = $this->data["User"]["password_confirm"];
            $userdata["User"]["role"] = 'realestate';
            $userdata["User"]["banned"] = 0;
            /* terms are shown on register page and cannot proceed without accepting */
            $userdata["User"]["terms_accepted"] = 1;

            if ($from_admin) {
                /* if admin registers a user then enable by default */
                $userdata["User"]["enabled"] = 1;
            }
            else {
                /* we need enabled = 0 because all users are enabled in db by default */
                $userdata["User"]["enabled"] = 0;
            }

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
                $uname = $userdata["User"]["username"];
                if ( $this->create_estate_profile($uid, $this->data) == false) {
                    $this->User->rollback();
                }
                else {
                    $this->User->commit();
                    // registration successfull - send to login
                    // TODO: maybe redirect to some public page

                    // contains all the necessary info for the pdf
                    // note: the only reason for using this is to avoid adding
                    // the id element into $this->data manually
                    $fraction = array(
                        'User' => $this->data['User'],
                        'RealEstate' => $this->data['RealEstate']);
                    $fraction['User']['id'] = $uid;
                    $this->notifyOfRegistration($fraction);

                    $this->Session->setFlash("Η εγγραφή πραγματοποιήθηκε."
                            ." Ελέγξτε την εισερχόμενη αλληλογραφία σας.",
                        'default', array('class' => 'flashBlue'));

                    if ($from_admin) {
                        $this->Session->setFlash("Η εγγραφή εκ μέρους τρίτου πραγματοποιήθηκε με επιτυχία.",
                                'default', array('class' => 'flashBlue'));

                        // if admin registers on behalf of other users, redirect to RE management screen
                        $this->redirect(array('controller' => 'admins',
                                            'action' => 'manage_realestates',
                                            'name' => "$uname",
                                            'banned' => '0',
                                            'disabled' => '0'));
                    }
                    else {
                        $this->Session->setFlash("Η εγγραφή πραγματοποιήθηκε."
                                ." Ελέγξτε την εισερχόμενη αλληλογραφία σας.",
                                'default', array('class' => 'flashBlue'));
                        $this->redirect('login');
                    }
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

    function registerfromadmin() {
        $this->set('selected_action', 'register');
        $this->set('title_for_layout','Εγγραφή νέου ιδιώτη από τον διαχειριστή');
        $this->set('municipalities', $this->Municipality->find('list', array('fields' => array('name'))));
        $this->register(true);
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
            // read from redmine config
            $_redmine_url = Configure::read('redmine.url');
            $_redmine_token = Configure::read('redmine.token');

            $reqData['subject'] = $data['subject'];
            $reqData['description'] = "{$data['userid']} {$data['username']}\n";
            $reqData['description'] .= "{$data['category']}\n";
            $reqData['description'] .= $data['description'];
            $reqXml = $this->createXmlRequest($reqData);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $_redmine_url);
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_USERPWD, $_redmine_token);
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

    // Creates XML request to Redmine for reporting an issue
    private function createXmlRequest($data){
        // Set project id
        $_redmine_projectID = Configure::read('redmine.projectID');
        $req = "<?xml version=\"1.0\"?>";
        $req .= "<issue>";
        $req .= "<subject>{$data['subject']}</subject>";
        $req .= "<description>{$data['description']}</description>";
        $req .= "<project_id>{$_redmine_projectID}</project_id>";
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
    private function notifyOfRegistration($data) {
        if( empty($data) )   return;

        // get municipality (name) in order to print it onto the
        // email which is to be sent for registration approval
        $municipality = $data['RealEstate']['municipality_id'];
        if( isset($municipality) ) {
            $municipality = $this->getMunicipalityData($municipality);
        }
        $this->set('data', $data );
        $this->set('municipality', $municipality);

        $filepath = $this->getRegistrationPdf($data);

        if( file_exists($filepath) ) {

            $this->set('pdf_success', true);
            $attachmentName = 'roommates_'.$data['User']['username'].'.pdf';
            $attachments = array($attachmentName => $filepath);

            $this->notifyAuthority($attachments);
            $this->notifyApplicant($data['RealEstate']['email'], $attachments);

            // remove temporary files
            $this->deleteFiles(constant('HTML2PS_PDF_OUT_DIR'));
            $this->deleteFiles(constant('HTML2PS_PDF_CACHE_DIR'));
        } else {

            // inform authority that the application form could not be printed
            $this->set('pdf_success', false);
            $this->notifyAuthority();
            $this->notifyApplicant($data['RealEstate']['email']);
        }
    }

    // Sends the registration application form to the supplied $email address.
    // The $attachments is an array of files to be included; may be null
    private function notifyApplicant($email, $attachments=null) {
        if( empty($email) ) return;

        $subject = Configure::read('registration.applicant_subject');
        if( empty($subject) ) {
            $subject = 'Αίτηση εγγραφής στην υπηρεσία roommates';
        }

        $extra_info = array(
                        'fax' => Configure::read('registration.authority_fax'));

        $this->sendEmail(
            'admin@roommates.edu.teiath.gr', $email, $subject, $extra_info,
            $attachments, 'registration_applicant_notification', 'default' );
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
                $attachments, 'registration_authority_notification', 'default' );
        }
    }

    private function getMunicipalityData($municipality_id) {
        $this->loadModel('Municipality');
        $data = $this->Municipality->find('first', array(
            'conditions' => array('Municipality.id' => $municipality_id)
        ));
        return $data;
    }

    // [$data] must contain the following arrays: User and RealEstate.
    private function getDataHash($data=null) {

        if( is_null($data) )    return '';

        $hashThis =
            "{$data['RealEstate']['firstname']}."
            . "{$data['RealEstate']['lastname']}."
            . "{$data['RealEstate']['type']}."
            . "{$data['User']['id']}."
            . "{$data['User']['username']}";

        return Security::hash( $hashThis, 'sha1', true );
    }

    // Deletes all writable files in the given directory. First level childern
    // are only affected (ie, it does not delete files within sub-directories).
    private function deleteFiles($directory) {

        $contents = scandir($directory);
        foreach( $contents as $child ) {

            $fullpath = $directory.$child;
            if( is_file($fullpath) && is_writable($fullpath) ) {
                unlink($fullpath);
            }
        }
    }

    // ------------------------------------------------------------------------
    // REST - Web Services
    // ------------------------------------------------------------------------

    function handleGetRequest($id = null) {
        if ($this->RequestHandler->isGet()) {

            if ($_SERVER['HTTP_ACCEPT'] === 'application/json') {
                $contentType = 'json';
            }
            else {
                if ( isset($this->params['url']['callback']) ) {
                    $contentType = 'jsonp';
                }
                else {
                    $contentType = 'xml';
                }
            }

            $user_id = $this->authenticate();
            if ($user_id == null) {
                $this->webServiceStatus(403, $contentType);
                return;
            }

            $user_role = $this->get_role($user_id);
            if ($user_role === 'realestate') {
                $this->webServiceStatus(403, $contentType);
                return;
            }

            if (!$this->checWebservicekUri($id)) {
                $this->webServiceStatus(400, $contentType);
                return;
            }

            // if user requests a single profile/realestate
            if ($id !== null)
            {
                $this->User->recursive = 0;
                $fields = array_merge($this->getStudentXmlFields(),
                                      $this->getRealEstateXmlFields());
                array_push($fields,'User.role');

                $conditions = array('User.id' => $id);
                if ($this->get_role($user_id) === 'user') {
                    $conditions['User.banned'] = 0;
                    $conditions['User.enabled'] = 1;
                    $conditions['Profile.visible'] = 1;
                }

                $this->User->recursive = 0;
                $result = $this->User->find('first', array(
                            'fields' => $fields,
                            'conditions' => $conditions
                          ));

                if (empty($result)) {
                    $this->webServiceStatus(404, $contentType);
                    return;
                }

                if ($result['User']['role'] === 'user') {
                    $result = $this->modifyStudentResult($result);
                    unset($result['RealEstate']);
                }
                else if ($result['User']['role'] === 'realestate') {
                    $result = $this->modifyRealEstateResult($result);
                    unset($result['Profile']);
                }
                else {
                    $this->webServiceStatus(404, $contentType);
                    return;
                }

                $this->set('users', $result);

                if ($contentType === 'json') {
                    $this->layout = 'json/default';
                    $this->render('json/get');
                    return;
                }

                if ($contentType === 'jsonp') {
                    $this->layout = 'jsonp/default';
                    $this->render('jsonp/get');
                    return;
                }

                $this->layout = 'xml/default';
                $this->render('xml/get');
                return;
            }

            $conditions = $this->getSearchConditions();

            if ($conditions['student_only'] === true &&
                $conditions['estate_only'] === true)
            {
                $this->webServiceStatus(412, $contentType);
                return;
            }
            else if ($conditions['student_only'] === true) {
                $results = $this->findStudents($conditions['student']);
            }
            else if ($conditions['estate_only'] === true) {
                $results = $this->findRealEstates($conditions['real_estate']);
            }
            else {
                $results = array_merge($this->findStudents($conditions['student']),
                                       $this->findRealEstates($conditions['real_estate']));
            }

            $this->set('users', $results);

            if ($contentType === 'json') {
                $this->layout = 'json/default';
                $this->render('json/get');
                return;
            }

            if ($contentType === 'jsonp') {
                $this->layout = 'jsonp/default';
                $this->render('jsonp/get');
                return;
            }

            $this->layout = 'xml/default';
            $this->render('xml/get');
        } else {
            // if its not GET request
            $this->webServiceStatus(400, $contentType);
            return;
        }
    }

    private function findStudents($conditions) {
        $this->User->recursive = 0;
        $students = $this->User->find('all', array(
                        'fields' => $this->getStudentXmlFields(),
                        'conditions' => $conditions,
                    ));

        for ($i = 0; $i < count($students); $i++)
            $students[$i] = $this->modifyStudentResult($students[$i]);

        return $students;
    }

    private function findRealEstates($conditions) {
        $this->User->recursive = 0;
        $estates = $this->User->find('all', array(
                        'fields' => $this->getRealEstateXmlFields(),
                        'conditions' => $conditions,
                    ));

        for ($i = 0; $i < count($estates); $i++)
            $estates[$i] = $this->modifyRealEstateResult($estates[$i]);

        return $estates;
    }

    private function modifyStudentResult($student) {
        // Set the User.id as Profile.id in order to help the
        // xml serialization. Also change the key from Profile
        // to student.
        $student['Profile'] = array_merge(array('id' => $student['User']['id']),
                                                $student['Profile']);

        $student['Profile']['gender'] === '0'
            ? $student['Profile']['gender'] = 'male'
            : $student['Profile']['gender'] = 'female';

        $student['Profile']['avatar'] =
                        $this->get_profile_bin_image($student['User']['id']);

        unset($student['User']);
        $student['student'] = $student['Profile'];
        unset($student['Profile']);

        return $student;
    }

    private function modifyRealEstateResult($estate) {
        // This function aims to help the xml serialization
        $municipalities = $this->getMunicipalities();
        // Set the User.id as RealEstate.id
        $estate['RealEstate'] = array_merge(
            array('id' => $estate['User']['id']), $estate['RealEstate']);
        unset($estate['User']);

        // Replace the municipality id with its respective name
        $estate['RealEstate']['municipality'] =
            $estate['RealEstate']['municipality_id'] !== null
            ? $municipalities[$estate['RealEstate']['municipality_id']]
            : '' ;
        unset($estate['RealEstate']['municipality_id']);

        // If the RealEstate's type is owner unset the necessary fields
        // and rename the key to private_landowner.
        // Else, reorder the elements so as to help the xml serialization
        if ($estate['RealEstate']['type'] === 'owner')
        {
            unset($estate['RealEstate']['company_name']);
            unset($estate['RealEstate']['type']);
            $estate['private_landowner'] = $estate['RealEstate'];
            unset($estate['RealEstate']);
        } else {
            // if type == realestate
            $tmp = $estate['RealEstate']['company_name'];
            unset($estate['RealEstate']['company_name']);
            $estate['RealEstate']['company_name'] = $tmp;
            unset($estate['RealEstate']['type']);
        }

        return $estate;
    }

    private function get_credentials() {
        // get basic auth from http header
        // decode and return username and password
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            return NULL;
        }
        return array('username' => $_SERVER['PHP_AUTH_USER'],
                     'password' => $_SERVER['PHP_AUTH_PW']);
    }


    private function authenticate() {
        // return user id if user authenticates successfully
        // return NULL otherwise
        $credentials = $this->get_credentials();
        if ($credentials == NULL) return NULL;

        $user = array('User.username' => $credentials['username'],
                      'User.password' => $credentials['password']);

        if ($this->Auth->login($user) == false) {
            return NULL;
        }
        else {
            $conditions = array('User.username' => $credentials['username']);
            $user = $this->User->find('first',
                array('conditions' => $conditions, 'fields' => 'id'));
            return $user['User']['id'];
        }
    }

    private function get_role($id) {
        // return role of user with given id
        $this->User->recursive = -1;
        $conditions = array('User.id' => $id);
        $user = $this->User->find('first',
            array('conditions' => $conditions, 'fields' => 'role'));
        return $user['User']['role'];
    }

    private function getStudentXmlFields() {
        return array(
            'Profile.firstname',
            'Profile.lastname',
            'Profile.email',
            'Profile.phone',
            'Profile.gender',
            'Profile.dob',
            'Profile.smoker',
            'Profile.pet',
            'Profile.child',
            'Profile.couple',
            'Profile.avatar',
            'Profile.we_are',
            'Profile.max_roommates',
        );
    }

    private function getRealEstateXmlFields() {
        return array(
            'RealEstate.firstname',
            'RealEstate.lastname',
            'RealEstate.email',
            'RealEstate.phone',
            'RealEstate.afm',
            'RealEstate.doy',
            'RealEstate.address',
            'RealEstate.postal_code',
            'RealEstate.fax',
            'RealEstate.municipality_id',
            'RealEstate.company_name',
            'RealEstate.type',
        );
    }

    private function getSearchConditions() {
        $search_params = $this->params['url'];

        $estate_conds = array();
        $student_conds = array();
        $has_student = false;
        $has_estate = false;

        // common conditions for both students and real estates
        if (isset($search_params['firstname']) && $search_params['firstname'] != '')
        {
            $student_conds['Profile.firstname'] = $search_params['firstname'];
            $estate_conds['RealEstate.firstname'] = $search_params['firstname'];
        }

        if (isset($search_params['lastname']) && $search_params['lastname'] != '')
        {
            $student_conds['Profile.lastname'] = $search_params['lastname'];
            $estate_conds['RealEstate.lastname'] = $search_params['lastname'];
        }

        if (isset($search_params['email']) && $search_params['email'] != '')
        {
            $student_conds['Profile.email'] = $search_params['email'];
            $estate_conds['RealEstate.email'] = $search_params['email'];
        }

        if (isset($search_params['phone']) && $search_params['phone'] != '')
        {
            $student_conds['Profile.phone'] = $search_params['phone'];
            $estate_conds['RealEstate.phone'] = $search_params['phone'];
        }

        // student conditions
        if (isset($search_params['gender']))
        {
            if ($search_params['gender'] === 'female')
            {
                $student_conds['Profile.gender'] = 1;
                $has_student = true;
            }

            if ($search_params['gender'] === 'male')
            {
                $student_conds['Profile.gender'] = 0;
                $has_student = true;
            }
        }

        if (isset($search_params['dob']) && $search_params['dob'] != '')
            $student_conds['Profile.dob'] = $search_params['dob'];

        if (isset($search_params['smoker']))
        {
            if ($search_params['smoker'] === '1')
            {
                $student_conds['Profile.smoker'] = 1;
                $has_student = true;
            }

            if ($search_params['smoker'] === '0')
            {
                $student_conds['Profile.smoker'] = 0;
                $has_student = true;
            }
        }

        if (isset($search_params['pet']))
        {
            if ($search_params['pet'] === '1')
            {
                $student_conds['Profile.pet'] = 1;
                $has_student = true;
            }

            if ($search_params['pet'] === '0')
            {
                $student_conds['Profile.pet'] = 0;
                $has_student = true;
            }
        }

        if (isset($search_params['child']))
        {
            if ($search_params['child'] === '1')
            {
                $student_conds['Profile.child'] = 1;
                $has_student = true;
            }

            if ($search_params['child'] === '0')
            {
                $student_conds['Profile.child'] = 0;
                $has_student = true;
            }
        }

        if (isset($search_params['couple']))
        {
            if ($search_params['couple'] === '1')
            {
                $student_conds['Profile.couple'] = 1;
                $has_student = true;
            }

            if ($search_params['couple'] === '0')
            {
                $student_conds['Profile.couple'] = 0;
                $has_student = true;
            }
        }

        if (isset($search_params['we_are']) && $search_params['we_are'] != null)
        {
            $student_conds['Profile.we_are'] = $search_params['we_are'];
            $has_student = true;
        }

        if (isset($search_params['max_roommates']) && $search_params['max_roommates'] != null)
        {
            $student_conds['Profile.max_roommates'] = $search_params['max_roommates'];
            $has_student = true;
        }

        // real estates conditions
        if (isset($search_params['afm']) && $search_params['afm'] != null)
        {
            $estate_conds['RealEstate.afm'] = $search_params['afm'];
            $has_estate = true;
        }

        if (isset($search_params['doy']) && $search_params['doy'] != null)
        {
            $estate_conds['RealEstate.doy'] = $search_params['doy'];
            $has_estate = true;
        }

        if (isset($search_params['address']) && $search_params['address'] != null)
        {
            $estate_conds['RealEstate.address'] = $search_params['address'];
            $has_estate = true;
        }

        if (isset($search_params['postal_code']) && $search_params['postal_code'] != null)
        {
            $estate_conds['RealEstate.postal_code'] = $search_params['postal_code'];
            $has_estate = true;
        }

        if (isset($search_params['municipality']) && $search_params['municipality'] != null)
        {
            $municipality_id = $this->Municipality->find('first', array(
                                    'fields' => array('id'),
                                    'conditions' => array(
                                        'Municipality.name' => $search_params['municipality'])
                               ));
            if (!empty($municipality_id))
            {
                $estate_conds['RealEstate.municipality_id'] = $municipality_id['Municipality']['id'];
                $has_estate = true;
            }
        }

        $student_conds['User.role'] = 'user';
        $estate_conds['User.role'] = 'realestate';

        $user_id = $this->authenticate();
        if ($user_id !== null) {
            $user_role = $this->get_role($user_id);
            if ($user_role === 'user') {
                $student_conds['User.banned'] = 0;
                $student_conds['User.enabled'] = 1;
                $student_conds['Profile.visible'] = 1;

                $estate_conds['User.banned'] = 0;
                $estate_conds['User.enabled'] = 1;
            }
        }

        return array('student' => $student_conds,
                     'real_estate' => $estate_conds,
                     'student_only' => $has_student,
                     'estate_only' => $has_estate);
    }

    private function get_profile_bin_image($id) {
        // returns user avatar image for given user $id encoded in base64
        // params: $id -> user id
        if ($id == null) return null;

        $conditions = array('Profile.user_id' => $id);
        $name = $this->Profile->find('first',
            array('conditions' => $conditions, 'fields' => 'avatar'));

        if (empty($name)) {
            return null;
        }

        // build image file path
        $filepath = WWW_ROOT . "img/uploads/profiles/" . $id . "/" . $name['Profile']['avatar'];

        if (! file_exists($filepath)) {
            return null;
        }

        $bin = fread(fopen($filepath, "r"), filesize($filepath));
        return base64_encode($bin);
    }

    private function webServiceStatus ($id, $contentType = 'xml') {
        if (array_key_exists($id, $this->xml_status) ) {
            $this->set('code', $id);
            $this->set('msg', $this->xml_status[$id]);
        } else {
            die('ERROR: UNDEFINED STATUS CODE');
        }

        if ($contentType === 'json') {
            $this->layout = 'json/default';
            $this->render('json/status');
            return;
        }

        if ($contentType === 'jsonp') {
                $this->layout = 'json/default';
                $this->render('json/status');
                return;
        }

        $this->layout = 'xml/default';
        $this->render('xml/status');
    }

    private function checWebservicekUri($id) {
        // Checks if the request URI is correct, eg:
        // ".../webservice/users" => Correct
        // ".../webservice/users/{id}" => Wrong
        // ".../webservice/user/{id}" => Correct
        // ".../webservice/user" => Wrong
        $url = $this->params['url']['url'];
        if (strpos($url, 'users') !== false && $id === null)
            return true;

        if (strpos($url, 'user/') !== false && $id !== null)
            return true;

        return false;
    }

    private function getMunicipalities() {
        // Convenience function that returns an array of id => name
        // representing each municipality's id and name.
        $temp = $this->Municipality->find('all');
        $municipalities = array();
        for ($i = 1; $i <= count($temp); $i++)
        {
            $municipalities[$i] = $temp[$i - 1]['Municipality']['name'];
        }

        return $municipalities;
    }

    // ------------------------------------------------------------------------
    // REST - Web Services End
    // ------------------------------------------------------------------------

}
?>
