<?php

App::import('Sanitize');
class ProfilesController extends AppController {

    var $name = 'Profiles';
    var $components = array('RequestHandler', 'Email', 'Common');
    var $paginate = array('limit' => 15);
    var $uses = array('Profile', 'House', 'Municipality', 'Image');

    // max avatar width, height
    var $avatar_size = array('width' => 100, 'height' => 100);

    function index() {
        // Block access for all
        $this->cakeError('error403');

        $this->set('title_for_layout','Δημόσια προφίλ');
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
							//'Profile.user_id !=' => $this->Auth->user('id'),
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
        $this->denyRole('realestate');

        $this->set('title_for_layout','Προφίλ χρήστη');

    	$this->checkExistence($id);
        $this->Profile->id = $id;
        $this->Profile->recursive = 2;
        // get profile  contains:
        //      Profile + Preference + User + House
        $profile = $this->Profile->read();

        // this variable is used to display properly
        // the selected element on header
        if ($profile['Profile']['user_id'] == $this->Auth->User('id')) {
            $this->set('selected_action', 'profiles_view');
        }

        // hide banned users unless we are admin
        if ($this->Auth->User('role') != 'admin' &&
            $this->Auth->User('id') != $profile['Profile']['user_id']) {
            if ($profile["User"]["banned"] == 1) {
                $this->cakeError('error404');
            }
        }

        $this->set('profile', $profile);

        $pref_municipality = $profile['Preference']['pref_municipality'];
        if(isset($pref_municipality)){
            $options['fields'] = array('Municipality.name');
            $options['conditions'] = array('Municipality.id = '.$pref_municipality);
            $municipality = $this->Municipality->find('list', $options);
            $municipality = $municipality[$pref_municipality];
            $this->set('municipality', $municipality);
        }
        // get house id of this user - NULL if he doesn't own one
        if(isset($profile["User"]["House"][0]["id"])){
            $houseid = $profile["User"]["House"][0]["id"];
            $this->House->id = $houseid;
            $house = $this->House->read();
            $this->set('house', $house);
            if($profile['User']['House'][0]['visible'] == 1){
                $imgDir = 'uploads/houses/';
                $image = $this->House->Image->find('first',array('conditions' => array('Image.is_default' => 1)));
                if($image != ''){
                    $imageFile = $imgDir.$houseid.'/thumb_'.$image['Image']['location'];
                    $this->set('image', $imageFile);
                }
            }
        }else{
            $houseid = NULL;
            $house = NULL;
        }
    }

/*
    function add(){
    	if (!empty($this->data)) {
             //var_dump($this->data); die();

            if ($this->Profile->saveAll($this->data, array('validate'=>'first'))) {
                 $this->Session->setFlash('Το προφίλ προστέθηκε.',
                    array('class' => 'flashBlue'));
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
             $this->Session->setFlash('Το προφίλ διεγράφη.',
                array('class' => 'flashBlue'));
             $this->redirect(array('action'=> 'index'));
    	  }
    }
*/

    function edit($id = null){
        $this->denyRole('realestate');
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'profiles_view');

        $this->set('title_for_layout','Επεξεργασία προφίλ');
        $this->checkExistence($id);
    	$this->checkAccess( $id );
        $this->Profile->id = $id;
        $this->Profile->recursive = 2;
        $profile = $this->Profile->read();
        $current_avatar = $profile['Profile']['avatar'];

        if(empty($this->data)){
             $this->data = $profile;
        }else{
	        $this->data['Profile']['firstname'] = $profile['Profile']['firstname'];
	        $this->data['Profile']['lastname'] = $profile['Profile']['lastname'];
            $this->data['Profile']['email'] = $profile['Profile']['email'];

            // check if image is uploaded
            // here we catch images not uploaded due to large file size
            if ( ! is_uploaded_file($this->data['Profile']['avatar']['tmp_name'])) {
                $this->Profile->invalidate('avatar', 'Υπερβλικά μεγάλο μέγεθος εικόνας.');
            }

            // check avatar file type
            $valid_types = array('png', 'jpeg', 'jpg', 'gif');
            if (! in_array($this->Common->upload_file_type($this->data['Profile']['avatar']['tmp_name']),
                $valid_types)) {

                $this->Profile->invalidate('avatar', 'Μη αποδεκτός τύπος εικόνας');
            }

            // check dimensions
            list($width, $height) = $this->Common->get_image_dimensions($this->data['Profile']['avatar']['tmp_name']);
            if (($width > $this->avatar_size['width']) or ($height > $this->avatar_size['height'])) {
                $this->Profile->invalidate('avatar', 'Υπερβολικά μεγάλο μέγεθος εικόνας');
            }

            if ($this->Profile->validates() == true) {
                // save image on FS
                $this->Image->create();
                $newName = $this->Image->saveImage($id, $this->params['data']['Profile']['avatar'],100,"ht",80, 'profile');
                if ($newName == NULL) {
                    $this->Profile->invalidate('avatar', 'Αδυναμία αποθήκευσης εικόνας, παρακαλώ επικοινωνήστε με τον διαχειριστή');
                } else {
                    if (! empty($current_avatar)) {
                        $this->Image->delProfileImage($id, $current_avatar);
                    }
                    $this->data['Profile']['avatar'] = $newName;
                }
            }

            if ($this->Profile->validates() == true) {
                if ($this->Profile->saveAll($this->data, array('validate'=>'first'))){
                    $this->Session->setFlash('Το προφίλ ενημερώθηκε.','default',
                        array('class' => 'flashBlue'));
                    $this->redirect(array('action'=> "view", $id));
                }
            }
		}
        $dob = array();
        foreach ( range((int)date('Y') - 17, (int)date('Y') - 80) as $year ) {
            $dob[$year] = $year;
        }
        $this->set('available_birth_dates', $dob);

        // hide banned users unless we are admin
        if ($this->Auth->User('role') != 'admin' &&
            $this->Auth->User('id') != $profile['Profile']['user_id']) {
            if ($profile["User"]["banned"] == 1) {
                $this->cakeError('error404');
            }
        }
        /* FIXME wtf? why ['House'][0]? */
        if(isset($profile['User']['House'][0]['id'])){
            if($profile['User']['House'][0]['visible'] === 1){
                $imgDir = 'uploads/houses/';
                $houseid = $profile["User"]["House"][0]["id"];
                $this->House->id = $houseid;
                $house = $this->House->read();
                $image = $this->House->Image->find('first',array('conditions' => array(
                    'Image.is_default' => 1, 'Image')));
                $imageFile = $imgDir.$houseid.'/thumb_'.$image['Image']['location'];
                $this->set('image', $imageFile);
            }
        }
        $this->set('profile', $profile['Profile']);
     }


    function search() {
        // Deny access to real estates
        $this->denyRole('realestate');
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'profiles_search');

        $this->set('title_for_layout','Αναζήτηση συγκατοίκων');
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
			$ownerId = $this->Profile->User->House->find('all', array(
			    'fields' => 'DISTINCT user_id',
			    'conditions' => array('House.visible' => 1)));
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
        //$searchconditions['Profile.user_id !='] = $this->Auth->user('id');

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
        $this->set('pagination_limit', $this->paginate['limit']);
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
*/
        $this->Session->setFlash('Τα κριτήρια αναζήτησης αποθηκεύτηκαν στις προτιμήσεις σας.',
            'default', array('class' => 'flashBlue'));
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
        // sets ban status for user with the given profile id
        $this->Profile->id = $id;
        $profile = $this->Profile->read();

        // exit if this profile belongs to another admin
        if ($profile["User"]["role"] == "admin") {
            $this->Session->setFlash('Ο διαχειριστής δεν μπορεί να κλειδώσει άλλο διαχειριστή.',
                'default', array('class' => 'flashBlue'));
            $this->redirect(array("action" => "view", $id));
        }

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
            $this->Session->setFlash('Ο λογαριασμός χρήστη κλειδώθηκε με επιτυχία.',
                'default', array('class' => 'flashBlue'));
            $this->email_banned_user($id);
        } else {
            $this->Session->setFlash(
                'Παρουσιάστηκε σφάλμα κατά την αλλαγή στοιχείων του λογαριασμού του χρήστη.',
                'default', array('class' => 'flashRed'));
        }
        $this->redirect(array('action'=> "view", $id));
    }

    function unban($id) {
        if ($this->Auth->user('role') != 'admin') {
            $this->cakeError('error403');
        }
        $success = $this->set_ban_status($id, 0);
        if ($success) {
            $this->Session->setFlash('Ο λογαριασμός χρήστη ξεκλειδώθηκε με επιτυχία.',
            'default', array('class' => 'flashBlue'));
        } else {
            $this->Session->setFlash(
                'Παρουσιάστηκε σφάλμα κατά την αλλαγή στοιχείων του λογαριασμού του χρήστη.',
                'default', array('class' => 'flashRed'));
        }
        $this->redirect(array('action'=> "view", $id));

    }

    private function email_banned_user($id) {
        // TODO: make more abstract to use in other use cases
        $this->Profile->id = $id;
        $profile = $this->Profile->read();
        $this->Email->to = $profile["Profile"]["email"];
        $this->Email->subject = 'Κλείδωμα λογαριασμού της υπηρεσίας roommates ΤΕΙ Αθήνας';
        //$this->Email->replyTo = 'support@example.com';
        $this->Email->from = 'admin@roommates.edu.teiath.gr';
        $this->Email->template = 'banned';
        $this->Email->layout = 'default';
        $this->Email->sendAs = 'both';
        $this->Email->send();
    }

    private function denyRole($role){
        if($this->Session->read('Auth.User.role') == $role){
            $this->cakeError('error403');
        }
    }
}
?>
