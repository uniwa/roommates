<?php

App::import('Sanitize');
class ProfilesController extends AppController {

    var $name = 'Profiles';
    var $components = array('RequestHandler');
    var $paginate = array('limit' => 15);
    var $uses = array("Profile", "House");

    function index() {
        if ($this->RequestHandler->isRss()) {
            $profiles = $this->Profile->find('all', array('conditions' => array('Profile.visible' => 1), 
				    			  'limit' => 20, 
				    			  'order' => 'Profile.modified.DESC'));
            return $this->set(compact('profiles'));

        }

    	$genderLabels = Configure::read('GenderLabels');
    	$this->set('genderLabels', $genderLabels);

		$order = array('Profile.modified' => 'desc');
		$selectedOrder = 0;
		if(isset($this->params['form']['selection'])){
			$selectedOrder = $this->params['form']['selection'];
		}
		
		$order = $this->getSortOrder($selectedOrder);

        if ($this->Auth->user('role') != 'admin'){
            $this->paginate = array(
                        'conditions' => array('Profile.visible' => 1),
                        'order' => $order);
        }
        else {
            $this->paginate = array('order' => $order);
        }

        $profiles = $this->paginate('Profile');
        $this->set('profiles', $profiles);
    }

    function view($id = null) {
        $this->Profile->id = $id;
        $this->Profile->recursive = 2;
        /* get profile  contains:
                Profile + Preference + User + House
         */
        $profile = $this->Profile->read();
        $this->set('profile', $profile);
        /* get house id of this user - NULL if he doesn't own one */
        if ( isset($profile["User"]["House"][0]["id"]) ) {
            $houseid = $profile["User"]["House"][0]["id"];
        } else {
            $houseid = NULL;
        }
        $this->set('houseid', $houseid);
    }

/*
    function add(){
    	if (!empty($this->data)) {
             //var_dump($this->data); die();     

            if ($this->Profile->saveAll($this->data, array('validate'=>'first'))) {
                 $this->Session->setFlash('Το προφίλ προστέθηκε.');
                 $this->redirect(array('action' => 'index'));
            }
        }

        $dob = array();
        foreach ( range((int)date('Y') - 17, (int)date('Y') - 80) as $year ) {
            $dob[$year] = $year;
        }
		$genderLabels = Configure::read('GenderLabels');
		$this->set('genderLabels', $genderLabels);
        $this->set('available_birth_dates', $dob);
    }	


    function delete($id) {

          if ($this->Profile->delete($id, $cascade = true)) {
             $this->Session->setFlash('Το προφίλ διεγράφη.');
             $this->redirect(array('action'=> 'index'));
    	  }
    }
*/

    function edit($id = null) {
        $this->checkAccess( $id );
        $this->Profile->id = $id;

        if (empty($this->data)) {
             $this->data = $this->Profile->read();
        } else {
            if ($this->Profile->saveAll($this->data, array('validate'=>'first'))) {
                $this->Session->setFlash('Το προφίλ ενημερώθηκε.');
                $this->redirect(array('action'=> 'index'));
            }
        }

        $dob = array();
        foreach ( range((int)date('Y') - 17, (int)date('Y') - 80) as $year ) {
            $dob[$year] = $year;
        }
        $this->set('available_birth_dates', $dob);
     }

    function search() {
        if(isset($this->params['form']['simplesearch'])) {
            $this->simpleSearch();
        }

        if(isset($this->params['form']['searchbyprefs'])){
            $this->searchBySavedPrefs();
        }

        if(isset($this->params['form']['savesearch'])) {
            $this->saveSearchPreferences();
            $this->simpleSearch();
        }
    }

    private function simpleSearch() {
		$order = $this->getSortOrder(0);
        $searchArgs = $this->data['Profile'];

        // set the conditions
        $searchconditions = array('Profile.visible' => 1);

		if(!empty($this->data['User']['hasHouse'])){
			$ownerId = $this->Profile->User->House->find('all', array('fields' => 'DISTINCT user_id'));
			$ownerId = Set::extract($ownerId, '/House/user_id');
			$searchconditions['Profile.user_id'] = $ownerId;
		};

        if(!empty($searchArgs['agemin'])) {
            //$searchconditions['Profile.age >'] = $searchArgs['agemin'];
            $searchconditions['Profile.dob <='] = $this->age_to_year($searchArgs['agemin']);
        }

        if(!empty($searchArgs['agemax'])) {
            //$searchconditions['Profile.age <'] = $searchArgs['agemax'];
            $searchconditions['Profile.dob >='] = $this->age_to_year($searchArgs['agemax']);
        }

        if(($searchArgs['gender'] != '') && ($searchArgs['gender'] < 2)) {
            $searchconditions['Profile.gender'] = $searchArgs['gender'];
        }

        if(($searchArgs['smoker'] != '') && ($searchArgs['smoker'] < 2)) {
            $searchconditions['Profile.smoker'] = $searchArgs['smoker'];
        }

        if(($searchArgs['pet'] != '') && ($searchArgs['pet'] < 2)) {
            $searchconditions['Profile.pet'] = $searchArgs['pet'];
        }

        if(($searchArgs['child'] != '') && ($searchArgs['child'] < 2)) {
            $searchconditions['Profile.child'] = $searchArgs['child'];
        }

        if(($searchArgs['couple'] != '') && ($searchArgs['couple'] < 2)) {
            $searchconditions['Profile.couple'] = $searchArgs['couple'];
        }

        if(!empty($searchArgs['max_roommates']) && ($searchArgs['max_roommates'] > 1)) {
            $searchconditions['Profile.max_roommates >='] = $searchArgs['max_roommates'];
        }
		
        $this->set('profiles', $this->Profile->find('all', array('conditions' => $searchconditions)));
        $this->set('defaults', array(   'age_min' => $searchArgs['agemin'],
                                        'age_max' => $searchArgs['agemax'],
                                        'gender' => $searchArgs['gender'],
                                        'smoker' => $searchArgs['smoker'],
                                        'pet' => $searchArgs['pet'],
                                        'child' => $searchArgs['child'],
                                        'couple' => $searchArgs['couple'],
                                        'has_house' => !empty($this->data['User']['hasHouse'])  ));
    }

    private function age_to_year($age) {
        return date('Y') - $age;
    }

    private function saveSearchPreferences() {
        $profile = $this->Profile->find('first', array('conditions' => array(
                                                       'Profile.user_id' => $this->Auth->user('id'))));
        $search_args = $this->data['Profile'];
        $this->Profile->Preference->save(array(
                        'id' => $profile['Preference']['id'],
                        'age_min' => $search_args['agemin'],
                        'age_max' => $search_args['agemax'],
                        'pref_gender' => $search_args['gender'],
                        'pref_smoker' => $search_args['smoker'],
                        'pref_pet' => $search_args['pet'],
                        'pref_child' => $search_args['child'],
                        'pref_couple' => $search_args['couple']
        ));
        $this->set('defaults', array(   'age_min' => $search_args['agemin'],
                                        'age_max' => $search_args['agemax'],
                                        'gender' => $search_args['gender'],
                                        'smoker' => $search_args['smoker'],
                                        'pet' => $search_args['pet'],
                                        'child' => $search_args['child'],
                                        'couple' => $search_args['couple'],
                                        'has_house' => !empty($this->data['User']['hasHouse'])  ));
        $this->Session->setFlash('Τα κριτήρια αναζήτησης αποθηκεύτηκαν στις προτιμήσεις σας.');
    }

    private function searchBySavedPrefs() {
        $profile = $this->Profile->find('first', array('conditions' => array(
                                                       'Profile.user_id' => $this->Auth->user('id'))));
        $prefs = $profile['Preference'];
        $search_conditions = array('Profile.visible' => 1);
        if($prefs['age_min'] != null) {
            $search_conditions['Profile.dob <='] = $this->age_to_year($prefs['age_min']);
        }
        if($prefs['age_max'] != null) {
            $search_conditions['Profile.dob >='] = $this->age_to_year($prefs['age_max']);
        }
        if(($prefs['pref_gender'] < 2 && $prefs['pref_gender'] != null)) {
            $search_conditions['Profile.gender'] = $prefs['pref_gender'];
        }
        if(($prefs['pref_smoker'] < 2 && $prefs['pref_smoker'] != null)) {
            $search_conditions['Profile.smoker'] = $prefs['pref_smoker'];
        }
        if(($prefs['pref_pet'] < 2 && $prefs['pref_pet'] != null)) {
            $search_conditions['Profile.pet'] = $prefs['pref_pet'];
        }
        if(($prefs['pref_child'] < 2) && $prefs['pref_child'] != null) {
            $search_conditions['Profile.child'] = $prefs['pref_child'];
        }
        if(($prefs['pref_couple'] < 2 && $prefs['pref_couple'] != null)) {
            $search_conditions['Profile.couple'] = $prefs['pref_couple'];
        }
        $this->set('profiles', $this->Profile->find('all', array('conditions' => $search_conditions)));
        $this->set('defaults', array(   'age_min' => $prefs['age_min'],
                                        'age_max' => $prefs['age_max'],
                                        'gender' => $prefs['pref_gender'],
                                        'smoker' => $prefs['pref_smoker'],
                                        'pet' => $prefs['pref_pet'],
                                        'child' => $prefs['pref_child'],
                                        'couple' => $prefs['pref_couple'],
                                        'has_house' => !empty($this->data['User']['hasHouse'])  ));
    }

    //check user's access
    private function checkAccess($profile_id){
        $this->Profile->id = $profile_id;
        $profile = $this->Profile->read();
        $user_id = $profile['User']['id'];
        if( ($this->Auth->user('id') != $user_id) && ($this->Auth->user('role') != 'admin') ){
            /*
             * More info aboute params in app/app_error.php
             */
            $this->cakeError('error403'/*, array()*/ );
        }
    }

	private function getSortOrder($selectedOrder){
		$ascoptions = array('asc', 'desc');
		$orderField = 'Profile.modified';
		switch($selectedOrder){
			case 0:
				$orderField = 'Profile.modified';
				$ascDesc = $ascoptions[1];
				break;
			case 1:
				$orderField = 'Profile.dob';
				$ascDesc = $ascoptions[1];
				break;
			case 2:
				$orderField = 'Profile.dob';
				$ascDesc = $ascoptions[0];
				break;
			case 3:
				$orderField = 'Profile.max_roommates';
				$ascDesc = $ascoptions[0];
				break;
			case 4:
				$orderField = 'Profile.max_roommates';
				$ascDesc = $ascoptions[1];
				break;
			}
		$order = array($orderField => $ascDesc);

        $orderOptions = array('τελευταία ενημέρωση', 'ηλικία αύξουσα', 'ηλικία φθίνουσα', 'επιθ. συγκάτοικοι αύξουσα', 'επιθ. συγκάτοικοι φθίνουσα');
        $this->set('order_options', array('options' => $orderOptions, 'selected' => $selectedOrder));
		
		return $order;
	}
}
?>
