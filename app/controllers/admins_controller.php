<?php

class AdminsController extends AppController
{
    var $name = 'Admins';
    var $uses = array('User', 'Profile', 'Preference');
    var $components = array('Token');
    var $paginate = array(
        'RealEstate' => array('fields' => array('User.id','User.username','User.banned',
            'User.enabled','RealEstate.id','RealEstate.firstname','RealEstate.type',
            'RealEstate.lastname','RealEstate.company_name','RealEstate.email'),
            'limit' => 50),
        'User' => array('fields' => array('User.id','User.username','User.banned',
            'Profile.id','Profile.firstname','Profile.lastname','Profile.email'),
            'limit' => 50)
    );

    function manage_users(){
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'manage_user');
        $this->set('title_for_layout','Διαχείριση χρηστών');

        $this->checkAccess();
$this->log('admin '.$this->Auth->User('id').' manage users', 'info');
        App::import('Model', 'User');
        $paginate = array('fields' => array('User.username', 'User.banned',
            'Profile.id', 'Profile.firstname', 'Profile.lastname',
            'Profile.email'),
            'limit' => 50);
        $user = new User();
        $this->set('limit', $this->paginate['User']['limit']);

        if(isset($this->params['url']['name']) || isset($this->params['url']['banned'])){
            $parameters = $this->params['url'];
            if($parameters['banned'] != 1){
                $conditions = array('OR'=>array(
                    'User.username LIKE' => "%".$parameters['name']."%",
                    'Profile.lastname LIKE' => "%".$parameters['name']."%",
                    'Profile.firstname LIKE ' => "%".$parameters['name']."%"));
            }else{
                $conditions['banned'] = 1;
            }
            $conditions['User.role'] = 'user';
            $results = $this->paginate('User', $conditions);
            $this->set('results', $results);
        }else{
            $conditions['User.role'] = 'user';
            $results = $this->paginate('User', $conditions);
            $this->set('results', $results);
        }
    }

    function manage_realestates(){
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'manage_realestate');
        $this->set('title_for_layout','Διαχείριση εκμισθωτών');

        $this->checkAccess();
$this->log('admin '.$this->Auth->User('id').' manage realestates', 'info');
        App::import('Model', 'User');
        $user = new User();
        $this->set('limit', $this->paginate['RealEstate']['limit']);

        // work around named parameters in url
        if (! empty($this->params['named'])) {
            if (isset($this->params['named']['name'])) {
                $this->params['url']['name'] = $this->params['named']['name'];
            }
            if (isset($this->params['named']['banned'])) {
                $this->params['url']['banned'] = $this->params['named']['banned'];
            }
            if (isset($this->params['named']['disabled'])) {
                $this->params['url']['disabled'] = $this->params['named']['disabled'];
            }
        }

        if(isset($this->params['url']['name']) || isset($this->params['url']['banned'])
            || isset($this->params['url']['disabled'])){
            $parameters = $this->params['url'];
            if(isset($parameters['name']) && ($parameters['name'] != '')){
                $conditions = array('OR'=>array(
                    'User.username LIKE' => "%".$parameters['name']."%",
                    'RealEstate.company_name LIKE' => "%".$parameters['name']."%",));
            }
            if($parameters['banned'] == 1){
                $conditions['banned'] = 1;
            }
            if($parameters['disabled'] == 1){
                $conditions['enabled'] = 0;
            }
            $conditions['User.role'] = 'realestate';
            $results = $this->paginate('RealEstate', $conditions);
        }else{
            $conditions['User.role'] = 'realestate';
            $results = $this->paginate('RealEstate', $conditions);
        }
        $this->set('results', $results);
    }

    // Import CSV file with info about successful applicants
    function import_csv() {
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'import_csv');
        $this->set('title_for_layout','Επιτυχόντες ΤΕΙ Αθήνας');

        if (isset($this->data)) {

            $error_general = 'Προέκυψε κάποιο σφάλμα με το αρχείο';
            // check if file is uploaded
            $file = $this->data['Admin']['submittedfile'];
            if (is_uploaded_file($file['tmp_name'])) {
                // the file-type below depends on the extension of the uploaded
                // file and could be omitted; format-specific checks are
                // performed internally by the handler
                if ($file['type'] === 'text/csv') {
                    $handle = fopen($file['tmp_name'], 'r');

                    // show some message about error reading file
                    if ($handle === false) {
                        $msg = $error_general;
                        $class = 'flashRed';
                    } else {
                        $outcome = $this->handle_import($handle);
                        $msg = $outcome['msg'];
                        $class = $outcome['success'] === true ?
                                'flashBlue' : 'flashRed';
                    }
                } else {
                    $msg = 'Η μορφή του αρχείου δεν είναι η αναμενόμενη';
                    $class = 'flashRed';
                }
            } else {
                $msg = $error_general;
                $class = 'flashRed';
            }
            $this->Session->setFlash($msg, 'default', array('class' => $class));
            $this->redirect($this->referer());
            $this->log('admin '.$this->Auth->User('id')." import csv: ($msg)",
                       'info');
        }
    }

    private function checkAccess() {
        if($this->Session->read('Auth.User.role') != 'admin'){
            $this->cakeError('error403');
        }
    }

    // Returns an array with keys:
    //  [success] boolean; true means that the file has successfully been
    //  parsed - the required headers were missing all found; false, otherwise.
    //  Identifying how many records have been actually inserted out of the
    //  total number of records in the csv is not supported but it is an easy
    //  tast to implement.
    //  [msg] string; description of the outcome
    public function handle_import($handle) {
        $outcome = $this->create_fresh_student($handle);

        fclose($handle);

        if ($outcome === false) {
            $msg = 'Η μορφή του αρχείου δεν είναι η αναμενόμενη';
            $success = false;
        } else {
            $success = true;
            $new = $outcome['success'];
            switch ($new) {
                case 0: $msg = "Δεν εισήχθησαν νέοι φοιτητές"; break;
                case 1: $msg = 'Εισήχθη 1 νέος φοιτητής'; break;
                default:
                    $msg = "Εισήχθησαν {$outcome['success']} νέοι φοιτητές";
                    break;
            }
        }

        return array('success' => $success, 'msg' => $msg);
    }

    // Removes all user records that have their 'fresh' attribute set to true.
    // Should be invoked before importing new students, though it is not
    // necessary; this is done to remove obsolete/old records.
    public function delete_fresh_students() {

        // Profile is associated with Preferences with a belongsTo relationship
        // (but why? don't preferences define a profile so as to be declared
        // part of it? - anyway). Because of this, delete cannot cascade from
        // User to Profile and then to Preference, but only from User to
        // Profile. So, it is imperative to delete preferences manually (unless
        // the model association is changed -- but that might break something).
        $conditions = array(
            'User.fresh' => true,
            // avoid deleting users that have accepted the terms
            'User.terms_accepted' => false);

        $options = array(
            'conditions' => $conditions,
            'fields' => array('Profile.preference_id'));
        $profiles = $this->Profile->find('all', $options);
        // these are the preferences that correspond to profiles that are to be
        // deleted
        $prefs = Set::classicExtract($profiles, '{n}.Profile.preference_id');

        // TODO: the following deletion should run as transaction dependent on
        // the outcome of the deletion of preferences

        // delete users and profiles
        $success = $this->User->deleteAll($conditions);

        // manually delete preferences of the deleted profiles
        $this->Preference->deleteAll(array('Preference.id' => $prefs));

        return true;
    }

    // returns
    //  false: when the supplied handle does not correspond to a supported csv
    //  file or the mandatory fields are not included (i.e. there is no 'id',
    //  'firstname' or 'lastname' columns)
    //  or
    //  an array with keys:
    //  [total] the total number of records in the csv (without the headers)
    //  [success] the number of newly created students (User+Profile+Preference)
    private function create_fresh_student($handle) {

        // locale needs to be set in order for fgetcsv() to accept greek letters
        $defaultLocale = setLocale(LC_CTYPE, 0);
        setLocale(LC_CTYPE, 'el_GR.utf8');

        $fields = $this->csv_fields($handle);
        if (empty($fields)) return false;
        // variables defined: i_uname, i_fname and i_lname
        extract($fields);

        // set default values that apply to all new users
        $user = array('User' => array('role' => 'user',
                                      'fresh' => 1));
        $profile = array(
            'Profile' => array('dob' => date('Y') - 18,
            // TODO: can move these to a 'defaults' variable in Profile model
                               'gender' => 0,
                               'visible' => 1),
            'Preference' => $this->Profile->defaults);
        $save_options = array('validate' => false);

        $records_total = 0;
        $records_success = 0;
        $records_new = 0; // # of successfully created students
        $records_old = 0; // # of records ignored because username pre-existed
        $records_bad = 0; // # of malformed records (eg, empty firstname)
        $records_fail = 0; // # of failed db errors (only on extreme situations)

        // TODO: instead of writing one user at a time, create and store groups
        // of users
        while ($data = fgetcsv($handle, 0, FRESH_CSV_DELIMITER)) {
            ++$records_total;

            // if any of the indices are not set for a particular record, then
            // ingore it; do note, though, this simply ensures that the indices
            // exist (not that they actually correspond to the desired value)
            $uname = isset($data[$i_uname]) ? $data[$i_uname] : '';
            $fname = isset($data[$i_fname]) ? $data[$i_fname] : '';
            $lname = isset($data[$i_lname]) ? $data[$i_lname] : '';

            if (empty($uname) || empty($fname) || empty($lname)) {
                ++$records_bad;
                continue;
            }

            // ignore duplicate
            if ($this->User->findByUsername($uname)) {
                ++$records_old;
                continue;
            }

            // save User separately from the other models so as to get the id
            // and use it in the generation of the profile token
            $user['User']['username'] = $uname;
            // TODO: set the appropriate hash
            $user['User']['password'] = '8f9bc2b8007a93584efdf303b83619f1fc147016';

            $this->User->id = null;
            // creating the user is a two-step process because its id in needed
            // in order to create the profile token
            $user_source = $this->User->getDataSource();
            $user_source->begin($this->User);

            $result = $this->User->save($user, false);

            if ($result === false) {
                ++$records_fail;
                $user_source->rollback($this->User);
                continue;
            }
            $user_id = $this->User->id;

            $profile['Profile']['user_id'] = $user_id;
            $profile['Profile']['firstname'] = $fname;
            $profile['Profile']['lastname'] = $lname;

            // perhaps, use the email address from the csv ?
            $profile['Profile']['email'] = FRESH_EMAIL;
            // use user_id as salt (and be in accordance with how such tokens
            // are generated)
            $profile['Profile']['token'] = $this->Token->generate($user_id);

            // create new profile and an associated user and preferences
            $result = $this->Profile->saveAll($profile, $save_options);
            if ($result) {
                ++$records_success;
                // commit user it its profile and preferences have been created
                $user_source->commit($this->User);
            } else {
                ++$records_fail;
                $user_source->rollback($this->User);
            }
        }

        setLocale(LC_CTYPE, $defaultLocale);

        return array('total' => $records_total,
                     'success' => $records_success);
    }

    // Returns (mixed)
    // false, if the specified handle does not correspond to a csv file or if it
    // a csv file that does not contain the mandatory fields ('username',
    // 'firstname', 'lastname'),
    //
    // an array with keys:
    //  [i_uname]
    //  [i_fname]
    //  [i_lname]
    // with a value that corresponds to the index of each field
    // Uses constants to identify the actual value of the fields to pick (see
    // bootstrap.php).
    private function csv_fields($handle) {

        $fields = fgetcsv($handle, 0, FRESH_CSV_DELIMITER);
        // check if the handle corresponds to an acceptable csv stream
        if ($fields === false) return false;

        // get the indices where the fields we are interested in lay
        $i_uname = array_search(FRESH_CSV_UNAME, $fields);
        $i_fname = array_search(FRESH_CSV_FNAME, $fields);
        $i_lname = array_search(FRESH_CSV_LNAME, $fields);

        // if any of the mandatory indices cannot be found, then terminate the
        // process
        if ( $i_uname === false || $i_fname === false || $i_lname === false) {
            return false;
        }

        return array('i_uname' => $i_uname,
                     'i_fname' => $i_fname,
                     'i_lname' => $i_lname);
    }
}
?>
