<?php
class ProfilesController extends AppController {

    var $name = 'Profiles';
    var $components = array('RequestHandler');
 //   var $profile = 
 //   var $preferences = 

    function index() {
        if ($this->RequestHandler->isRss()) {
            $profiles = $this->Profile->find('all',
                            array('conditions' => array('Profile.visible' => 1),
                                  'limit' => 20,
                                  'order' => 'Profile.modified DESC'));
            return $this->set(compact('profiles'));
        }

        $this->set('profiles', $this->Profile->find('all', array('conditions' => array('Profile.visible' => 1))));
    }

    function view($id = null) {
        $this->Profile->id = $id;
        $this->set('profile', $this->Profile->read());
    }

    function add(){
        if (!empty($this->data)) {
            if ($this->Profile->save($this->data)) {
                $this->Session->setFlash('Το προφίλ προστέθηκε.');
                $this->redirect(array('action' => 'index'));
            }
        }

        $dob = array();
        foreach ( range((int)date('Y'), 1920) as $year ) {
            $dob[$year] = $year;
        }
        $this->set('available_birth_dates', $dob);
    }
	
    function delete($id) {
        if ($this->Profile->delete($id)) {
            $this->Session->setFlash('Το προφίλ διεγράφη.');
            $this->redirect(array('action'=> 'index'));
        }
    }

    function edit($id = null) {
        $this->Profile->id = $id;
        if (empty($this->data)) {
            $this->data = $this->Profile->read();
        } else {
            if ($this->Profile->save($this->data)) {
                $this->Session->setFlash('Το προφίλ ενημερώθηκε.');
                $this->redirect(array('action'=> 'index'));
            }
        }
     }

    function search() {
        $searchArgs = $this->data['Profile'];

        // set the conditions
        $searchconditions = array('Profile.visible' => 1);

        if(!empty($searchArgs['agemin'])) {
            $searchconditions['Profile.age >'] = $searchArgs['agemin'];
        }

        if(!empty($searchArgs['agemax'])) {
            $searchconditions['Profile.age <'] = $searchArgs['agemax'];
        }

        $genderLabels = array('Άνδρας', 'Γυναίκα');
        if(($searchArgs['gender'] != '') && ($searchArgs['gender'] < 2)) {
            $searchconditions['Profile.gender'] = $genderLabels[$searchArgs['gender']];
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
}
?>
