<?php

App::import('Sanitize');
class ProfilesController extends AppController {

    var $name = 'Profiles';
    var $components = array('RequestHandler', 'Email');
    var $paginate = array('limit' => 15);
    var $uses = array("Profile", "House");

    function index() {
        /*if ($this->RequestHandler->isRss()) {
            $profiles = $this->Profile->find('all', array('conditions' => array('Profile.visible' => 1), 
				    			  'limit' => 20, 
				    			  'order' => 'Profile.modified.DESC'));
            return $this->set(compact('profiles'));
        }*/

    	$genderLabels = Configure::read('GenderLabels');
    	$this->set('genderLabels', $genderLabels);

		$order = array('Profile.modified' => 'desc');
		$selectedOrder = 0;
		if(isset($this->params['named']['selection'])){
			$selectedOrder = $this->params['named']['selection'];
		}
		
		$order = $this->getSortOrder($selectedOrder);
        if ($this->Auth->user('role') != 'admin'){
            $this->paginate = array(
                        'conditions' => array(
							'Profile.visible' => 1,
							'Profile.user_id !=' => $this->Auth->user('id'),
                            'User.banned' => 0
							),
                        'order' => $order);
        }
        else {
            $this->paginate = array(
				'order' => $order,
				'limit' => 15
				);
        }

        $profiles = $this->paginate('Profile');
        $this->set('profiles', $profiles);
    }

    function view($id = null) {

	$this->checkExistence($id);
        $this->Profile->id = $id;
        $this->Profile->recursive = 2;
        /* get profile  contains:
                Profile + Preference + User + House
		*/
        $profile = $this->Profile->read();
        if ($profile["User"]["banned"] == 1) $this->cakeError('error404');
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
        $this->checkExistence($id);
    	$this->checkAccess( $id );
        $this->Profile->id = $id;

        if (empty($this->data)) {
             $this->data = $this->Profile->read();
	}
        else {
            if ($this->Profile->saveAll($this->data, array('validate'=>'first'))){
                    $this->Session->setFlash('Το προφίλ ενημερώθηκε.');
                    $this->redirect(array('action'=> "view", $id));
            }
		}
        $dob = array();
        foreach ( range((int)date('Y') - 17, (int)date('Y') - 80) as $year ) {
            $dob[$year] = $year;
        }
        $this->set('available_birth_dates', $dob);
     }

    function search() {
		if($this->data){
		    // Set up the URL that we will redirect to
		    $url = array('controller' => 'profiles', 'action' => 'search');
		    // If we have parameters, loop through and URL encode them
			if(!empty($this->data['hasHouse'])){
				$this->data['Profile']['has_house'] = true;
			}
		    if(is_array($this->data['Profile'])){
			  foreach($this->data['Profile'] as &$profile){
				 $profile = urlencode($profile);
			  }
			}
			
		    // Set search type
			if(isset($this->params['form']['simplesearch'])) {
				$searchType = 'simple';
			}
			if(isset($this->params['form']['searchbyprefs'])){
				$searchType = 'byprefs';
			}
			if(isset($this->params['form']['savesearch'])) {
				$searchType = 'saveprefs';
			}
			if(isset($this->params['form']['resetvalues'])) {
				$searchType = 'resetvalues';
			}else{
				// Merge our URL-encoded data with the URL parameters set above...
				$params = array_merge($url, $this->data['Profile']);
				$params = array_merge($params, array('searchtype' => $searchType));
				// Do the (magical) redirect
				$this->redirect($params);
			}
		}

		// Set things up so the form values are set when the page reloads...
		$this->data['Profile'] = $this->params['named'];

		$this->getSortOrder(0);
		
		if(isset($this->params['named']['searchtype'])){
			$searchType = $this->params['named']['searchtype'];
			
			switch($searchType){
				case 'resetvalues':
					unset($this->params['named']);
					$this->simpleSearch();
					break;
				case 'simple':
					$this->simpleSearch();
					break;
				case 'byprefs':
					$this->searchBySavedPrefs();
					break;
				case 'saveprefs':
					$this->saveSearchPreferences();
					$this->simpleSearch();
					break;
			}
		}
    }

    private function simpleSearch(){
		$searchArgs = $this->params['named'];

        // set the conditions
        $searchconditions = array('Profile.visible' => 1, 'User.banned' => 0);

		if(isset($searchArgs['has_house'])){
			$ownerId = $this->Profile->User->House->find('all', array('fields' => 'DISTINCT user_id'));
			$ownerId = Set::extract($ownerId, '/House/user_id');
			$searchconditions['Profile.user_id'] = $ownerId;
		};

        if(isset($searchArgs['age_min'])) {
            $searchconditions['Profile.dob <='] = $this->age_to_year($searchArgs['age_min']);
        }

        if(isset($searchArgs['age_max'])) {
            $searchconditions['Profile.dob >='] = $this->age_to_year($searchArgs['age_max']);
        }

        if(($searchArgs['pref_gender'] != '') && ($searchArgs['pref_gender'] < 2)) {
            $searchconditions['Profile.gender'] = $searchArgs['pref_gender'];
        }

        if(($searchArgs['pref_smoker'] != '') && ($searchArgs['pref_smoker'] < 2)) {
            $searchconditions['Profile.smoker'] = $searchArgs['pref_smoker'];
        }

        if(($searchArgs['pref_pet'] != '') && ($searchArgs['pref_pet'] < 2)) {
            $searchconditions['Profile.pet'] = $searchArgs['pref_pet'];
        }

        if(($searchArgs['pref_child'] != '') && ($searchArgs['pref_child'] < 2)) {
            $searchconditions['Profile.child'] = $searchArgs['pref_child'];
        }

        if(($searchArgs['pref_couple'] != '') && ($searchArgs['pref_couple'] < 2)) {
            $searchconditions['Profile.couple'] = $searchArgs['pref_couple'];
        }
        // exclude logged user's profile
        $searchconditions['Profile.user_id !='] = $this->Auth->user('id');

		$order = array('Profile.modified' => 'desc');
		$selectedOrder = 0;
		if(isset($searchArgs['orderby'])){
			$selectedOrder = $searchArgs['orderby'];
		}
		
		$order = $this->getSortOrder($selectedOrder);
		$this->paginate = array(
				'conditions' => $searchconditions,
				'limit' => 15,
				'contain' => '',
				'order' => $order
			);

        $profiles = $this->paginate('Profile');
		$this->set('searchargs', $searchArgs);
        $this->set('profiles', $profiles);
/*        $this->set('defaults', array(   'age_min' => $searchArgs['age_min'],
                                        'age_max' => $searchArgs['age_max'],
                                        'pref_gender' => $searchArgs['pref_gender'],
                                        'pref_smoker' => $searchArgs['pref_smoker'],
                                        'pref_pet' => $searchArgs['pref_pet'],
                                        'pref_child' => $searchArgs['pref_child'],
                                        'pref_couple' => $searchArgs['pref_couple'],
                                        'has_house' => !empty($this->data['User']['hasHouse'])  ));
*/    }

    private function age_to_year($age) {
        return date('Y') - $age;
    }

    private function saveSearchPreferences() {
        $profile = $this->Profile->find('first', array('conditions' => array(
                                                       'Profile.user_id' => $this->Auth->user('id'))));
        $search_args = $this->params['named'];
		$ageMin = (isset($search_args['age_min']))?$search_args['age_min']:NULL;
		$ageMax = (isset($search_args['age_max']))?$search_args['age_max']:NULL;
        $this->Profile->Preference->save(array(
                        'id' => $profile['Preference']['id'],
                        'age_min' => $ageMin,
                        'age_max' => $ageMax,
                        'pref_gender' => $search_args['pref_gender'],
                        'pref_smoker' => $search_args['pref_smoker'],
                        'pref_pet' => $search_args['pref_pet'],
                        'pref_child' => $search_args['pref_child'],
                        'pref_couple' => $search_args['pref_couple']
        ));
/*        $this->set('defaults', array(   'age_min' => $search_args['age_min'],
                                        'age_max' => $search_args['age_max'],
                                        'gender' => $search_args['pref_gender'],
                                        'smoker' => $search_args['pref_smoker'],
                                        'pet' => $search_args['pref_pet'],
                                        'child' => $search_args['pref_child'],
                                        'couple' => $search_args['pref_couple'],
                                        'has_house' => !empty($this->data['User']['hasHouse'])  ));
*/        $this->Session->setFlash('Τα κριτήρια αναζήτησης αποθηκεύτηκαν στις προτιμήσεις σας.');
    }

    private function searchBySavedPrefs() {
        $profile = $this->Profile->find('first', array('conditions' => array(
                                                       'Profile.user_id' => $this->Auth->user('id'))));
        $prefs = $profile['Preference'];
		unset($this->params['named']);
		$this->params['named'] = $prefs;
		$this->simpleSearch();
    }

    //check user's access
    private function checkAccess($profile_id){
        $this->Profile->id = $profile_id;
        $profile = $this->Profile->read();
        $user_id = $profile['User']['id'];
        if( $this->Auth->user('id') != $user_id){
            $this->cakeError('error403');
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

	//check user's existence
	private function checkExistence($profile_id){
		$this->Profile->id = $profile_id;
		$profile = $this->Profile->read();
		if( $profile == NULL ){
            $this->cakeError('error404');
        }
    }

    private function set_ban_status($id, $status) {
        /* sets ban status for user with the given profile id */
        $this->Profile->id = $id;
        $profile = $this->Profile->read();

        $user["User"] = $profile["User"];
        $user["User"]["banned"] = $status;

        $this->User->begin();
        $this->User->id = $profile["Profile"]["user_id"];
        if ($this->User->save($user, array('validate'=>'first'))) {
            $this->User->commit();
            return True;
        } else {
            $this->User->rollback();
            return False;
        }

    }

    function ban($id) {
        if ($this->Auth->user('role') != 'admin') {
            $this->cakeError('error403');
        }
        $success = $this->set_ban_status($id, 1);
        if ($success) {
            $this->Session->setFlash("Ο λογαριασμός χρήστη απενεργοποιηθηκε με επιτυχία.");
            $this->email_banned_user($id);
        } else {
            $this->Session->setFlash('Παρουσιάστηκε σφάλμα κατά την αλλαγή στοιχείων του λογαριαμού του χρήστη.');
        }
        $this->redirect(array('action'=> "view", $id));
    }

    function unban($id) {
        if ($this->Auth->user('role') != 'admin') {
            $this->cakeError('error403');
        }
        $success = $this->set_ban_status($id, 0);
        if ($success) {
            $this->Session->setFlash("Ο λογαριασμός χρήστη ενεργοποιήθηκε με επιτυχία.");
        } else {
            $this->Session->setFlash('Παρουσιάστηκε σφάλμα κατά την αλλαγή στοιχείων του λογαριαμού του χρήστη.');
        }
        $this->redirect(array('action'=> "view", $id));

    }

    private function email_banned_user($id) {
        /* TODO: make more abstract to use in other use cases */
        $this->Profile->id = $id;
        $profile = $this->Profile->read();
        $this->Email->to = $profile["Profile"]["email"];
        $this->Email->subject = 'Απενεργοποίηση λογαριασμού της υπηρεσίας roommates ΤΕΙ Αθήνας';
        //$this->Email->replyTo = 'support@example.com';
        $this->Email->from = 'admin@roommates.edu.teiath.gr';
        $this->Email->template = 'banned';
        $this->Email->sendAs = 'both';
        $this->Email->send();
    }
}
?>
