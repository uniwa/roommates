<?php

App::import('Sanitize');
class ProfilesController extends AppController {

    var $name = 'Profiles';
    var $components = array('RequestHandler');
    var $paginate = array('limit' => 15);

    function index() {
        if ($this->RequestHandler->isRss()) {
            $profiles = $this->Profile->find('all',
                            array('conditions' => array('Profile.visible' => 1),
                                  'limit' => 20,
                                  'order' => 'Profile.modified DESC'));
            return $this->set(compact('profiles'));
        }

		$genderLabels = Configure::read('GenderLabels');
		$this->set('genderLabels', $genderLabels);

        $profiles = $this->paginate('Profile', array('Profile.visible' => 1));
        $this->set('profiles', $profiles);
    }

    function view($id = null) {
        $this->Profile->id = $id;
	//$koko = $this->Profile->read();
	//echo '<pre>';print_r($koko);echo'</pre>';die();	
        $this->set('profile', $this->Profile->read());
    }


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


    function edit($id = null) {

        $this->Profile->id = $id;
//      $koko = $this->Profile->Preference->find('all', array('fields' =>'id'));
//      $koko = $this->data['Preference']['id'];
//      var_dump($koko); die();

        
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
        $searchArgs = $this->data['Profile'];

        // set the conditions
        $searchconditions = array('Profile.visible' => 1);

		if(!empty($this->data['User']['hasHouse'])){
			$ownerId = $this->Profile->House->find('all', array('fields' => 'DISTINCT profile_id'));
			$ownerId = Set::extract($ownerId, '/House/profile_id');
			$searchconditions['Profile.id'] = $ownerId;
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
                                        'mates' => $searchArgs['max_roommates']    ));
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
                        'mates_min' => $search_args['max_roommates'],
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
                                        'mates' => $search_args['max_roommates']    ));
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
        if($prefs['mates_min'] != null) {
            $search_conditions['Profile.max_roommates >='] = $prefs['mates_min'];
        }
        $this->set('profiles', $this->Profile->find('all', array('conditions' => $search_conditions)));
        $this->set('defaults', array(   'age_min' => $prefs['age_min'],
                                        'age_max' => $prefs['age_max'],
                                        'gender' => $prefs['pref_gender'],
                                        'smoker' => $prefs['pref_smoker'],
                                        'pet' => $prefs['pref_pet'],
                                        'child' => $prefs['pref_child'],
                                        'couple' => $prefs['pref_couple'],
                                        'mates' => $prefs['mates_min']  ));
    }
}
?>
