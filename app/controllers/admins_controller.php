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
            // check if file is uploaded
            // check file type
            // call import function
        }
    }

    private function checkAccess() {
        if($this->Session->read('Auth.User.role') != 'admin'){
            $this->cakeError('error403');
        }
    }

    public function import_users() {
        // temporary csv file
        $filename = WWW_ROOT . 'rm_CSV.csv';
        $handle = fopen($filename, 'r');

        // show some message about error reading file
        if ($handle === false) {
            // ERROR
            return;
        }

        // TODO: what should happen if the previous 'fresh' students could not
        // be deleted?
        if ($this->delete_fresh_students()) {
            $this->create_fresh_student($handle, $delimiter);
        }

        fclose($handle);
        die;
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

    private function create_fresh_student($handle, $delimiter) {

        // locale needs to be set in order for fgetcsv() to accept greek letters
        setLocale(LC_ALL, 'el_GR.utf8');

        $fields = fgetcsv($handle, 0, CSV_DELIMITER);
        // check if the handle corresponds to an acceptable csv stream
        if ($fields === false) {
            //ERROR
            return;
        }

        // get the indices where the fields we are interested in lay
        // if any of the indices below equals null, then the process should be
        // terminated
        $i_am = array_search('Α_Μ', $fields);
        $i_fname = array_search('ΟΝΟΜΑ', $fields);
        $i_lname = array_search('ΕΠΩΝΥΜΟ', $fields);

        // set default values that apply to all new users
        $fresh = array(
            'User' => array('role' => 'user',
                            'fresh' => 1),
            'Profile' => array('dob' => date('Y') - 18,
            // TODO: can move these to a 'defaults' variable in Profile model
                               'gender' => 0,
                               'visible' => 1),
            'Preference' => $this->Profile->defaults
        );
        $options = array('validate' => false);

        // TODO: instead of writing one user at a time, create and store groups
        // of users
        while ($data = fgetcsv($handle, 0, $delimiter)) {

            $fresh['User']['username'] = $data[$i_am];
            // TODO: set the appropriate hash
            $fresh['User']['password'] = '8f9bc2b8007a93584efdf303b83619f1fc147016';

            $fresh['Profile']['firstname'] = $data[$i_fname];
            $fresh['Profile']['lastname'] = $data[$i_lname];

            // TODO: read email from some configuration
            $fresh['Profile']['email'] = 'default@email.com';
            // TODO: provide some (more) proper salt
            $fresh['Profile']['token'] = $this->Token->generate($data[$i_am]);

            // create new profile and an associated user and preferences
            $result = $this->Profile->saveAll($fresh, $options);
        }
    }
}
?>
