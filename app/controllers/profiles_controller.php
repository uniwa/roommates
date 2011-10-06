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

		$genderLabels = Configure::read('GenderLabels');
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
    }

    private function age_to_year($age) {
        return date('Y') - $age;
    }

}
?>
