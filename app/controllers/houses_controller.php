<?php

App::import('Sanitize');

class HousesController extends AppController {

    var $name = 'Houses';
    var $components = array('RequestHandler', 'Token');
    var $helpers = array('Text', 'Time', 'Html');
    var $paginate = array('limit' => 15);

    function index() {
        $this->set('title_for_layout','Σπίτια');
        if ($this->RequestHandler->isRss()) {
            $conditions = array("User.banned" => 0, 'House.visible' => 1);
            $houses = $this->House->find('all',
                        array('limit' => 50,
                              'order' => 'House.modified DESC',
                              'conditions' => $conditions)
            );
            return $this->set(compact('houses'));
        }

		$order = array('House.modified' => 'desc');
		$selectedOrder = 0;

		if(isset($this->params['named']['selection'])){
			$selectedOrder = $this->params['named']['selection'];
			$ascoptions = array('asc', 'desc');
			$orderField = 'House.modified';
			switch($selectedOrder){
				case 0:
					$orderField = 'House.modified';
					$ascDesc = $ascoptions[1];
					break;
				case 1:
					$orderField = 'House.price';
					$ascDesc = $ascoptions[0];
					break;
				case 2:
					$orderField = 'House.price';
					$ascDesc = $ascoptions[1];
					break;
				case 3:
					$orderField = 'House.municipality_id';
					$ascDesc = $ascoptions[0];
					break;
				case 4:
					$orderField = 'House.municipality_id';
					$ascDesc = $ascoptions[1];
					break;
				case 5:
					$orderField = 'House.area';
					$ascDesc = $ascoptions[0];
					break;
				case 6:
					$orderField = 'House.area';
					$ascDesc = $ascoptions[1];
					break;
				case 7:
					$orderField = 'House.free_places';
					$ascDesc = $ascoptions[0];
					break;
				case 8:
					$orderField = 'House.free_places';
					$ascDesc = $ascoptions[1];
					break;
			}
			//$order = array($orderField => $ascDesc);
            $options['order'] = array($orderField => $ascDesc);
		}

        $orderOptions = array(  'τελευταία ενημέρωση',
                                'ενοίκιο αύξουσα',
                                'ενοίκιο φθίνουσα',
                                'δήμος αύξουσα',
                                'δήμος φθίνουσα',
                                'τετραγωνικά αύξουσα',
                                'τετραγωνικά φθίνουσα',
                                'διαθέσιμες θέσεις αύξουσα',
                                'διαθέσιμες θέσεις φθίνουσα');
        $this->set('order_options', array('options' => $orderOptions, 'selected' => $selectedOrder));

        /* using the banned condition here means that even admin cannot view
            the houses of the banned users in the index - admin has his own
            banned users view in /admin/banned
        */
        //$options['conditions'] = array('User.banned !=' => 1);
        if ($this->Auth->User('role') != 'admin') {
            /* admin can see banned user's houses and invisible houses */
            $options['conditions'] = array('House.visible' => 1, 'User.banned' => 0);
        }

        $options['fields'] = array( 'House.*, Image.location,
                                    Municipality.name, Floor.type,
                                    HouseType.type' );

        // manually join all the required tables
        // in order to get only the default
        // image for each house with only
        // one query

        $options['joins'] = array(
                array(  'table' => 'images',
                        'alias' => 'Image',
                        'type'  => 'left',
                        'conditions' => array('House.default_image_id = Image.id')
                ),
                array(  'table' => 'users',
                        'alias' => 'User',
                        'type'  => 'left',
                        'conditions' => array('User.id = House.user_id')
                ),
                array(  'table' => 'municipalities',
                        'alias' => 'Municipality',
                        'type'  => 'left',
                        'conditions' => array('House.municipality_id = Municipality.id')
                ),
                array(  'table' => 'floors',
                        'alias' => 'Floor',
                        'type'  => 'left',
                        'conditions' => array('House.floor_id = Floor.id')
                ),
                array(  'table' => 'house_types',
                        'alias' => 'HouseType',
                        'type'  => 'left',
                        'conditions' => array('House.house_type_id = HouseType.id')
                )
            );

        $options['limit'] = 15;

        $this->paginate = $options;
        $this->House->recursive = -1;
        $houses = $this->paginate('House');
        $this->set('houses', $houses);
    }

    function beforeFilter() {
        parent::beforeFilter();
        if( $this->RequestHandler->isRss()){
            $this->Auth->allow( 'index' );
            $this->Auth->allow( 'search' );
        }

        if(!class_exists('L10n'))
            App::import('Core','L10n');

        $this->L10n = new L10n();
        $this->L10n->get('gr');
        // not sure for this solution
        $_SESSION['Config']['language'] = 'gr';
    }

    function view($id = null) {

        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'houses_view');

        $this->set('title_for_layout','Σπίτι');
        $this->checkExistance($id);
        $this->House->id = $id;
        $this->House->recursive = 2;
        $house = $this->House->read();

        if ($this->Auth->User('role') != 'admin' &&
            $this->Auth->User('id') != $house['House']['user_id']) {
            if (    $house["User"]["banned"] == 1 ||
                    (   $house['House']['visible'] == 0 &&
                        $house['House']['user_id'] != $this->Auth->User('id')
                    )
            )
                $this->cakeError('error404');
        }

        $this->set('house', $house);

        $images = $this->House->Image->find('all',array('conditions' => array('house_id'=>$id)));

        foreach ($images as $image) {
            if ($image['Image']['id'] == $house['House']['default_image_id']) {
                $this->set('default_image_location', $image['Image']['location']);
                $this->set('default_image_id', $image['Image']['id']);
            }
        }

        $this->House->Image->recursive = 0;
		$this->set('House.images', $this->paginate());
		$this->set('images', $images);
    }
 
    function add() {
        $this->set('title_for_layout','Προσθήκη σπιτιού');
        /* if user already owns a house bail out */
        $conditions = array("user_id" => $this->Auth->user('id'));
        $res = $this->House->find('first', array('conditions' => $conditions));
        if (isset($res["House"]["id"])) {
            $this->Session->setFlash('Έχετε ήδη ένα σπίτι αποθηκευμένο.',
                    'default', array('class' => 'flashRed'));
            $this->redirect(array('action' => 'index'));
        }

        if (!empty($this->data)) {
            $this->data['House']['user_id'] = $this->Auth->user('id');
            /* debug: var_dump($this->data); die(); */
            if ($this->House->save($this->data)) {
                $this->Session->setFlash('Το σπίτι αποθηκεύτηκε με επιτυχία.',
                    'default', array('class' => 'flashBlue'));
                $hid = $this->House->id;
                $this->redirect(array('action' => "view/$hid"));
            }
        }

        $this->setAddEditVars();
    }

    function delete($id) {
        $this->set('title_for_layout','Διαγραφή σπιτιού');
        $this->checkAccess( $id );
        $this->House->begin();
        /* delete associated images first */
        $conditions = array("house_id" => $id);
        if ( ! $this->House->Image->deleteAll($conditions) ) {
            $this->House->rollback();
            $this->Session->setFlash('Αδυναμία διαγραφής εικόνων από την βάση.',
                    'default', array('class' => 'flashRed'));
            $this->redirect(array('action'=>'index'));
        }
        else {
            /* remove from FS */
            if (! $this->House->Image->delete_all($id) ) {
                $this->House->rollback();
                $this->Session->setFlash('Αδυναμία διαγραφής εικόνων από το σύστημα αρχείων.',
                    'default', array('class' => 'flashRed'));
                $this->redirect(array('action'=>'index'));
            }
            else {
                /* delete house */
                if (! $this->House->delete( $id ) ) {
                    $this->House->rollback();
                    $this->Session->setFlash('Αδυναμία διαγραφής σπιτιού',
                        'default', array('class' => 'flashRed'));
                    $this->redirect(array('action'=>'index'));
                }
            }
        }
        $this->House->commit();
        $this->Session->setFlash('Το σπίτι διαγράφηκε με επιτυχία.',
                    'default', array('class' => 'flashBlue'));
        $this->redirect(array('action'=>'index'));
    }

    function edit($id = null) {
        $this->set('title_for_layout','Επεξεργασία σπιτιού');
        $this->checkExistance($id);
        $this->checkAccess($id);
        $this->House->id = $id;

        if (empty($this->data)) {
            $this->data = $this->House->read();
        }
        else {
            if ($this->House->save($this->data)) {
                $this->Session->setFlash('Το σπίτι ενημερώθηκε με επιτυχία.',
                    'default', array('class' => 'flashBlue'));
                $this->redirect(array('action' => "view/$id"));
            }
        }

        $this->setAddEditVars();
    }

    private function setAddEditVars() {
        $this->set('floors', $this->House->Floor->find('list', array('fields' => array('type'))));
        $this->set('houseTypes', $this->House->HouseType->find('list', array('fields' => array('type'))));
        $this->set('heatingTypes', $this->House->HeatingType->find('list', array('fields' => array('type'))));
        $this->set('municipalities', $this->House->Municipality->find('list', array('fields' => array('name'))));

        $entries = array();
        for($i = 1950; $i <= date('Y'); $i++) {
            $entries[$i] = $i;
        }
        $this->set('available_constr_years', $entries);

        $no_mates = array();
        for ($i = 1; $i <= 9; $i++){
            $no_mates[$i] = $i;
        }
        $this->set('places_availability', $no_mates);
    }

    private function checkAccess( $house_id ){
        $this->House->id = $house_id;
        $house = $this->House->read();
        $user_id = $house['User']['id'];


        if($this->Auth->user('id') != $user_id) {
            $this->cakeError( 'error403' );
        }
    }

    //check houses existance
    private function checkExistance( $house_id ){
        $this->House->id = $house_id;
        $house = $this->House->read();
        if($house == NULL ){
            $this->cakeError( 'error404' );
        }
    }

    private function age_to_year($age) {
        return date('Y') - $age;
    }

    function search () {

        // this variable is used to properly display
        // the selected element on header
        $this->set('selected_action', 'houses_search');

        $this->set('title_for_layout','Αναζήτηση σπιτιών');

        // RSS
        if ($this->RequestHandler->isRss()) {
            /*
             *  personalized rss feed
             */
            // extract token from url
            $token = $this->params['url']['token'];
            if ($token == "") return;

            $profile_id = $this->Token->to_id($token);
            if ($profile_id == NULL) return;

            // get user preferences
            $prefs = $this->loadSavedPreferences($profile_id);
            $results = $this->simpleSearch($prefs['house_prefs'],
                                           $prefs['mates_prefs'], null, false);

            $municipalities = $this->House->Municipality->find('list');
            $this->set('municipalities', $municipalities);

            return $this->set(compact('results'));
        } // RSS

        // drop down menu options
        $this->set('house_type_options',
                   $this->House->HouseType->find('list',array('fields' => array('type'))));
        $this->set('heating_type_options',
                   $this->House->HeatingType->find('list', array('fields' => array('type'))));
        $this->set('municipality_options',
                   $this->House->Municipality->find('list', array('fields' => array('name'))));
        $this->set('floor_options',
                   $this->House->Floor->find('list', array('fields' => array('type'))));

        $entries = array();
        for($i = 1950; $i <= date('Y'); $i++) {
            $entries[$i] = $i;
        }
        $this->set('construction_year_options', $entries);
        // drop down menu options

        $this->set('order_options', array('τελευταία ενημέρωση',
                                            'τιμή - αύξουσα',
                                            'τιμή - φθίνουσα',
                                            'εμβαδό - αύξουσα',
                                            'εμβαδό - φθίνουσα',
                                            'δήμο - αύξουσα',
                                            'δήμο - φθίνουσα',
                                            'διαθέσιμες θέσεις - αύξουσα',
                                            'διαθέσιμες θέσεις - φθίνουσα'));

        if(isset($this->params['url']['save'])) {
            $this->saveSearchPreferences();
            // store user's input
            $this->set('defaults', $this->params['url']);
        }

        if(isset($this->params['url']['search'])) {
            $results = $this->simpleSearch( $this->getHouseConditions(),
                                            $this->getMatesConditions(),
                                            $this->getOrderCondition($this->params['url']['order_by'])
                                          );
            $this->set('results', $results);
            // store user's input
            $this->set('defaults', $this->params['url']);
        }

        if(isset($this->params['url']['load'])) {
            $prefs = $this->loadSavedPreferences();

//          search manuall or automatically with saved preferences?
//          for now search manually (== press search button as well)
//             $results = $this->simpleSearch( $prefs['house_prefs'],
//                                             $prefs['mates_prefs'],
//                                             $this->getOrderCondition($this->params['url']['order_by'])
//                                           );
//
//             $this->set('results', $results);
            $this->set('defaults', $prefs['defaults']);
        }
    }

    private function simpleSearch(  $houseConditions, $matesConditions,
                                    $orderBy = null, $pagination = true ) {

        // The following SQL query is implemented
        // mates conditions are added to the inner join with profiles table
        // house conditions are added to the 'where' statement
        // ----------------------------------------------------------------
        // SELECT House.* FROM houses House
        // LEFT JOIN users User ON House.user_id = User.id
        // INNER JOIN profiles Profile ON Profile.user_id = User.id
        // LEFT JOIN images Image ON Image.id = House.default_image_id;

        $options['fields'] = array('House.*', 'Image.location');

        $options['joins'] = array(
            array(  'table' => 'users',
                    'alias' => 'User',
                    'type'  => 'left',
                    'conditions' => array('House.user_id = User.id')
            ),
            array(  'table' => 'profiles',
                    'alias' => 'Profile',
                    'type'  => 'inner',
                    'conditions' => $matesConditions
            ),
            array(  'table' => 'images',
                    'alias' => 'Image',
                    'type'  => 'left',
                    'conditions' => array('Image.id = House.default_image_id')
            )
        );

        $options['conditions'] = $houseConditions;
        if ($orderBy != null) {
            $options['order'] = $orderBy;
        }
        // required recursive value for joins
        $this->House->recursive = -1;
        // pagination options
        if($pagination) {
            $options['limit'] = 15;
            $this->paginate = $options;
            $results = $this->paginate('House');
        } else {
            $results = $this->House->find('all', $options);
        }
        return $results;
    }

    private function loadSavedPreferences($profile_id = NULL) {
        /*
         * get preferences for user with supplied profile id
         * if none supplied get use user id from Auth component
         */
        if ($profile_id == NULL) {
            // Get logged user's Profile.id
            $profile = $this->Profile->find('first',
                                            array('conditions' => array(
                                                  'Profile.user_id' =>
                                                        $this->Auth->user('id'))
                                                 ));
        }
        else {
            $this->Profile->id = $profile_id;
            $profile = $this->Profile->read();
        }
        $prefs = $profile['Preference'];
        $defaults = array();

        // house preferences
        $house_prefs = array();
        if (!empty($prefs['price_max'])) {
            $house_prefs['House.price <='] = $prefs['price_max'];
            $defaults['max_price'] = $prefs['price_max'];
        }
        if (!empty($prefs['area_min'])) {
            $house_prefs['House.area >='] = $prefs['area_min'];
            $defaults['min_area'] = $prefs['area_min'];
        }
        if (!empty($prefs['area_max'])) {
            $house_prefs['House.area <='] = $prefs['area_max'];
            $defaults['max_area'] = $prefs['area_max'];
        }
        if (!empty($prefs['pref_municipality'])) {
            $house_prefs['House.municipality_id'] = $prefs['pref_municipality'];
            $defaults['municipality'] = $prefs['pref_municipality'];
        }
        if ($prefs['pref_furnitured'] != null && $prefs['pref_furnitured'] < 2) {
            $house_prefs['House.furnitured'] = $prefs['pref_furnitured'];
            $defaults['furnitured'] = $prefs['pref_furnitured'];
        }
        if ($prefs['pref_disability_facilities'] == 1) {
            $house_prefs['House.disability_facilities'] = 1;
            $defaults['accessibility'] = 1;
        }
        if ($prefs['pref_has_photo'] == 1) {
            $house_prefs['House.default_image_id !='] = null;
            $defaults['has_photo'] = 1;
        }
        //$house_prefs['House.user_id !='] = $this->Auth->user('id');
        if($this->Auth->User('role') != 'admin') {
            $house_prefs['House.visible'] = 1;
        }
        $house_prefs['User.banned !='] = 1;

        // mates preferences
        $mates_prefs = array();
        if (!empty($prefs['age_min'])) {
            $mates_prefs['Profile.dob <='] = $this->age_to_year($prefs['age_min']);
            $defaults['min_age'] = $prefs['age_min'];
        }
        if (!empty($prefs['age_max'])) {
            $mates_prefs['Profile.dob >='] = $this->age_to_year($prefs['age_max']);
            $defaults['max_age'] = $prefs['age_max'];
        }
        if ($prefs['pref_gender'] != null && $prefs['pref_gender'] < 2) {
            $mates_prefs['Profile.gender'] = $prefs['pref_gender'];
            $defaults['gender'] = $prefs['pref_gender'];
        }
        if ($prefs['pref_smoker'] != null && $prefs['pref_smoker'] < 2) {
            $mates_prefs['Profile.smoker'] = $prefs['pref_smoker'];
            $defaults['smoker'] = $prefs['pref_smoker'];
        }
        if ($prefs['pref_pet'] != null && $prefs['pref_pet'] < 2) {
            $mates_prefs['Profile.pet'] = $prefs['pref_pet'];
            $defaults['pet'] = $prefs['pref_pet'];
        }
        if ($prefs['pref_child'] != null && $prefs['pref_child'] < 2) {
            $mates_prefs['Profile.child'] = $prefs['pref_child'];
            $defaults['child'] = $prefs['pref_child'];
        }
        if ($prefs['pref_couple'] != null && $prefs['pref_couple'] < 2) {
            $mates_prefs['Profile.couple'] = $prefs['pref_couple'];
            $defaults['child'] = $prefs['pref_couple'];
        }
        // required for the joins
        array_push($mates_prefs, 'User.id = Profile.user_id');

        return array(   'house_prefs' => $house_prefs,
                        'mates_prefs' => $mates_prefs,
                        'defaults'    => $defaults  );
    }

    private function saveSearchPreferences() {
        // Get logged user's Profile.id
        $profile = $this->Profile->find('first', array('conditions' => array(
                                                       'Profile.user_id' => $this->Auth->user('id'))));
        $search_args = $this->params['url'];
        //Profile preferences
		$ageMin = (isset($search_args['min_age']))?$search_args['min_age']:NULL;
		$ageMax = (isset($search_args['max_age']))?$search_args['max_age']:NULL;
        // House preferences
//		$priceMin = (isset($search_args['price_min']))?$search_args['price_min']:NULL;
		$priceMax = (isset($search_args['max_price']))?$search_args['max_price']:NULL;
		$areaMin = (isset($search_args['min_area']))?$search_args['min_area']:NULL;
		$areaMax = (isset($search_args['max_area']))?$search_args['max_area']:NULL;
//		$bedroomNumMin = (isset($search_args['bedroom_num_min']))?$search_args['bedroom_num_min']:NULL;
//		$bathroomNumMin = (isset($search_args['bathroom_num_min']))?$search_args['bathroom_num_min']:NULL;
//		$constructionYearMin = (isset($search_args['construction_year_min']))?$search_args['construction_year_min']:NULL;
//		$availabilityDateMin = (isset($search_args['availability_date_min']))?$search_args['availability_date_min']:NULL;
//		$rentPeriodMin = (isset($search_args['rent_period_min']))?$search_args['rent_period_min']:NULL;
//		$floorIdMin = (isset($search_args['floor_id_min']))?$search_args['floor_id_min']:NULL;
        $this->House->User->Profile->Preference->save(array(
                        'id' => $profile['Preference']['id'],
                        // House
//                        'price_min' => $priceMin,
                        'price_max' => $priceMax,
                        'area_min' => $areaMin,
                        'area_max' => $areaMax,
//                        'bedroom_num_min' => $bedroomNumMin,
//                        'bathroom_num_min' => $bathroomNumMin,
//                        'construction_year_min' => $constructionYearMin,
//                        'availability_date_min' => $availabilityDateMin,
//                        'rent_period_min' => $rentPeriodMin,
//                        'floor_id_min' => $floorIdMin,
                        'pref_municipality' => $search_args['municipality'],
//                        'pref_solar_heater' => $search_args['pref_solar_heater'],
                        'pref_furnitured' => $search_args['furnitured'],
//                        'pref_aircondition' => $search_args['pref_aircondition'],
//                        'pref_garden' => $search_args['pref_garden'],
//                        'pref_parking' => $search_args['pref_parking'],
//                        'pref_shared_pay' => $search_args['pref_shared_pay'],
//                        'pref_security_doors' => $search_args['pref_security_doors'],
                        'pref_disability_facilities' => !empty($search_args['accessibility']),
//                        'pref_storerooms' => $search_args['pref_storeroom'],
//                        'pref_house_type_id' => $search_args['pref_house_type_id'],
//                        'pref_heating_type_id' => $search_args['pref_heating_type_id'],
                        'pref_has_photo' => !empty($search_args['has_photo']),
                        // Profile
                        'age_min' => $ageMin,
                        'age_max' => $ageMax,
                        'pref_gender' => $search_args['gender'],
                        'pref_smoker' => $search_args['smoker'],
                        'pref_pet' => $search_args['pet'],
                        'pref_child' => $search_args['child'],
                        'pref_couple' => $search_args['couple']
        ));
        $this->Session->setFlash('Τα κριτήρια αναζήτησης αποθηκεύτηκαν στις
                                                            προτιμήσεις σας.',
                                 'default',
                                 array('class' => 'flashBlue'));
    }



//     function advanced_search () {
//         // this variable is used to display properly
//         // the selected element on header
//         $this->set('selected_action', 'houses_search');
// 
//         $this->set('title_for_layout','Αναζήτηση σπιτιών');
//         //---------------------drop down menus options--------------------//
//         $this->set('house_type_options',
//                    $this->House->HouseType->find('list',array('fields' => array('type'))));
//         $this->set('heating_type_options',
//                    $this->House->HeatingType->find('list', array('fields' => array('type'))));
//         $this->set('municipality_options',
//                    $this->House->Municipality->find('list', array('fields' => array('name'))));
//         $this->set('floor_options',
//                    $this->House->Floor->find('list', array('fields' => array('type'))));
// 
//         $entries = array();
//         for($i = 1950; $i <= date('Y'); $i++) {
//             $entries[$i] = $i;
//         }
//         $this->set('construction_year_options', $entries);
// 
//         $this->search_order_options = array('τελευταία ενημέρωση', 
//                                             'τιμή - αύξουσα', 
//                                             'τιμή - φθίνουσα', 
//                                             'εμβαδό - αύξουσα', 
//                                             'εμβαδό - φθίνουσα',
//                                             'δήμο - αύξουσα',
//                                             'δήμο - φθίνουσα',
//                                             'διαθέσιμες θέσεις - αύξουσα',
//                                             'διαθέσιμες θέσεις - φθίνουσα');
//         $this->set('search_order_options', $this->search_order_options);
// 	    //--------------------------------------------------------------------//
//         if(isset($this->params['url']['advanced_search'])) {
// 
//             $options['joins'] = array(
//                 array(  'table' => 'users',
//                         'alias' => 'User',
//                         'type'  => 'left',
//                         'conditions' => array('House.user_id = User.id')
//                 ),
//                 array(  'table' => 'profiles',
//                         'alias' => 'Profile',
//                         'type'  => 'inner',
//                         'conditions' => $this->getMatesConditions()
//                 ),
//                 array(  'table' => 'images',
//                         'alias' => 'Image',
//                         'type'  => 'left',
//                         'conditions' => array('Image.id = House.default_image_id')
//                 )
//             );

// 	    $cond = $this->getHouseConditions();
//         $adv_cond =  $this->getHouseConditions();
//         $options['fields'] = array('House.*', 'Image.location');
//         $options['conditions'] = $this->getHouseConditions();//array_merge($cond, $adv_cond);
//         $options['limit'] = 15;
//         $options['contain'] = '';
//         $options['order'] = $this->getOrderCondition($this->params['url']['order_by']);
//         $this->paginate = $options;
//         $this->House->recursive = -1;
//         $results = $this->paginate('House');
// 
//         $this->set('results', $results);
//         $this->set('defaults', $this->params['url']);
//        }
//     }

    private function getHouseConditions() {

        $house_prefs = $this->params['url'];
        $house_conditions = array();

        // primary conditions
        if(!empty($house_prefs['max_price']))
            $house_conditions['House.price <='] = $house_prefs['max_price'];

        if(!empty($house_prefs['min_area']))
            $house_conditions['House.area >='] = $house_prefs['min_area'];

        if(!empty($house_prefs['max_area']))
            $house_conditions['House.area <='] = $house_prefs['max_area'];

        if(!empty($house_prefs['municipality']))
            $house_conditions['House.municipality_id'] =
                                                $house_prefs['municipality'];

        if($house_prefs['furnitured'] < 2 && $house_prefs['furnitured'] != null)
            $house_conditions['House.furnitured'] = $house_prefs['furnitured'];

        if(isset($house_prefs['accessibility']))
            $house_conditions['House.disability_facilities'] = 1;

        if(isset($house_prefs['has_photo']))
            $house_conditions['House.default_image_id !='] = null;

        // secondary conditions
        if(!empty($house_prefs['house_type']))
            $house_conditions['House.house_type_id'] =
                                                    $house_prefs['house_type'];

        if(!empty($house_prefs['heating_type']))
            $house_conditions['House.heating_type_id'] =
                                                $house_prefs['heating_type'];

        if(!empty($house_prefs['bedroom_num_min']))
            $house_conditions['House.bedroom_num >='] =
                                                $house_prefs['bedroom_num_min'];

        if(!empty($house_prefs['bathroom_num_min']))
            $house_conditions['House.bathroom_num >='] =
                                            $house_prefs['bathroom_num_min'];

        if(!empty($house_prefs['construction_year_min']))
            $house_conditions['House.construction_year >='] =
                                        $house_prefs['construction_year_min'];

        if(!empty($house_prefs['floor_min']))
            $house_conditions['House.floor_id >='] = $house_prefs['floor_min'];

        if(!empty($house_prefs['rent_period_min']))
            $house_conditions['House.rent_period >='] =
                                                $house_prefs['rent_period_min'];

        if(isset($house_prefs['solar_heater']))
            $house_conditions['House.solar_heater'] = 1;

        if(isset($house_prefs['aircondition']))
            $house_conditions['House.aircondition'] = 1;

        if(isset($house_prefs['garden']))
            $house_conditions['House.garden'] = 1;

        if(isset($house_prefs['parking']))
            $house_conditions['House.parking'] = 1;

        if(isset($house_prefs['no_shared_pay']))
            $house_conditions['House.shared_pay'] = 0;

        if(isset($house_prefs['security_doors']))
            $house_conditions['House.security_doors'] = 1;

        if(isset($house_prefs['storeroom']))
            $house_conditions['House.storeroom'] = 1;

        if(!empty($house_prefs['available_from'])){
            $year  = $house_prefs['available_from']['year'];
            $month = $house_prefs['available_from']['month'];
            $day   = $house_prefs['available_from']['day'];

            $house_conditions['House.availability_date <='] =
                                            $year . '-' . $month . '-' . $day;
        }

        if($this->Auth->User('role') != 'admin')
            $house_conditions['House.visible'] = 1;

        $house_conditions['User.banned !='] = 1;

        return $house_conditions;
    }


//     private function getHouseAdvancedConditions(){
// 
//         $house_adv_prefs = $this->params['url'];
//         $house_adv_conditions = array();
//         if(!empty($house_adv_prefs['house_type'])){
//             $house_adv_conditions['House.house_type_id'] = $house_adv_prefs['house_type'];
//         }
//         if(!empty($house_adv_prefs['heating_type'])){
//             $house_adv_conditions['House.heating_type_id'] = $house_adv_prefs['heating_type'];
//         }
//         if(!empty($house_adv_prefs['bedroom_num_min'])){
//             $house_adv_conditions['House.bedroom_num >='] = $house_adv_prefs['bedroom_num_min'];
//         }
//         if(!empty($house_adv_prefs['bathroom_num_min'])){
//             $house_adv_conditions['House.bathroom_num >='] = $house_adv_prefs['bathroom_num_min'];
//         }
//         if(!empty($house_adv_prefs['construction_year_min'])){
//             $house_adv_conditions['House.construction_year >='] = $house_adv_prefs['construction_year_min'];
//         }
//         if(!empty($house_adv_prefs['floor_min'])){
//             $house_adv_conditions['House.floor_id >='] = $house_adv_prefs['floor_min'];
//         }
//         if(!empty($house_adv_prefs['rent_period_min'])){
//             $house_adv_conditions['House.rent_period >='] = $house_adv_prefs['rent_period_min'];
//         }
//         if(isset($this->params['url']['solar_heater'])){
//             $house_adv_conditions['House.solar_heater'] = 1;
//         }
//         if(isset($this->params['url']['aircondition'])){
//             $house_adv_conditions['House.aircondition'] = 1;
//         }
//         if(isset($this->params['url']['garden'])){
//             $house_adv_conditions['House.garden'] = 1;
//         }
//         if(isset($this->params['url']['parking'])){
//             $house_adv_conditions['House.parking'] = 1;
//         }
//         if(isset($this->params['url']['no_shared_pay'])){
//             $house_adv_conditions['House.shared_pay'] = 0;
//         }
//         if(isset($this->params['url']['security_doors'])){
//             $house_adv_conditions['House.security_doors'] = 1;
//         }
//         if(isset($this->params['url']['storeroom'])){
//             $house_adv_conditions['House.storeroom'] = 1;
//         }
//         if(!empty($house_adv_prefs['available_from'])){
//         //	pr($house_adv_prefs['available_from']);die();
//             $year = $house_adv_prefs['available_from']['year'];
//             $month = $house_adv_prefs['available_from']['month'];
//             $day = $house_adv_prefs['available_from']['day'];
//             $str_date = $year . '-' . $month . '-' . $day;
//             //pr($str_date);die();
// 
//             $house_adv_conditions['House.availability_date <='] = $str_date;
//         }
//         //	pr($house_adv_conditions);
//         return $house_adv_conditions;
//     }


    private function getMatesConditions() {
        $mates_prefs = $this->params['url'];

        $mates_conditions = array();
        if(!empty($mates_prefs['min_age'])) {
            $mates_conditions['Profile.dob <='] = $this->age_to_year($mates_prefs['min_age']);
        }
        if(!empty($mates_prefs['max_age'])) {
            $mates_conditions['Profile.dob >='] = $this->age_to_year($mates_prefs['max_age']);
        }
        if($mates_prefs['gender'] < 2 && $mates_prefs['gender'] != null) {
            $mates_conditions['Profile.gender'] = $mates_prefs['gender'];
        }
        if($mates_prefs['smoker'] < 2 && $mates_prefs['smoker'] != null) {
            $mates_conditions['Profile.smoker'] = $mates_prefs['smoker'];
        }
        if($mates_prefs['pet'] < 2 && $mates_prefs['pet'] != null) {
            $mates_conditions['Profile.pet'] = $mates_prefs['pet'];
        }
        if($mates_prefs['child'] < 2 && $mates_prefs['child'] != null) {
            $mates_conditions['Profile.child'] = $mates_prefs['child'];
        }
        if($mates_prefs['couple'] < 2 && $mates_prefs['couple'] != null) {
            $mates_conditions['Profile.couple'] = $mates_prefs['couple'];
        }
        // required condition for the left join
        array_push($mates_conditions, 'User.id = Profile.user_id');

        return $mates_conditions;
    }

    private function getOrderCondition($selected_order) {

        switch($selected_order) {
            case 0:
                $order = array('House.modified' => 'desc', 'House.id' => 'asc');
                break;
            case 1:
                $order = array('House.price' => 'asc');
                break;
            case 2:
                $order = array('House.price' => 'desc');
                break;
            case 3:
                $order = array('House.area' => 'asc');
                break;
            case 4:
                $order = array('House.area' => 'desc');
                break;
            case 5:
                $order = array('House.municipality_id' => 'asc');
                break;
            case 6:
                $order = array('House.municipality_id' => 'desc');
                break;
            case 7:
                $order = array('House.free_places' => 'asc');
                break;
            case 8:
                $order = array('House.free_places' => 'desc');
                break;
        }

        return $order;
    }
}
?>
