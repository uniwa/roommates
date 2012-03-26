<?php

App::import('Sanitize');
App::import( 'Vendor', 'facebook' );
Configure::load( 'facebook' );

class HousesController extends AppController {

    var $name = 'Houses';
    var $components = array('RequestHandler', 'Token');
    var $helpers = array('Text', 'Time', 'Html', 'Xml', 'Javascript');
    var $paginate = array('limit' => 15);
    var $uses = array('House', 'HouseType', 'Image',
                      'HeatingType', 'Municipality', 'Floor');

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
                                'διαθέσιμες θέσεις φθίνουσα',
                                'απόσταση από ΤΕΙ αύξουσα',
                                'απόσταση από ΤΕΙ φθίνουσα');
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

        $options['fields'] = array( 'House.*', 'Image.location',
                                    'Municipality.name', 'Floor.type',
                                    'HouseType.type' );

        // manually join all the required tables
        // in order to get only the default
        // image for each house with only
        // one query

        $options['joins'] = array(
                array(  'table' => 'images',
                        'alias' => 'Image',
                        'type'  => 'left',
                        'conditions' => array('House.id = Image.house_id')
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
        if( $this->RequestHandler->isRss() ){
            $this->Auth->allow( 'index' );
            $this->Auth->allow( 'search' );
        }

        $this->Auth->allow('handleGetRequest');
        $this->Auth->allow('handlePostRequest');
        $this->Auth->allow('handlePutRequest');
        $this->Auth->allow('handleDeleteRequest');

        if(!class_exists('L10n'))
            App::import('Core','L10n');

        $this->L10n = new L10n();
        $this->L10n->get('gr');
        // not sure for this solution
        $_SESSION['Config']['language'] = 'gr';

		/* facebook instance initialization */
		if( !$this->Session->check( 'facebook' ) ) {
		    $this->facebookInit( );
	    }

    }

    function view($id = null) {
        $this->set('title_for_layout','Εμφάνιση σπιτιού');
        $this->checkExistance($id);

        $this->House->id = $id;
        $this->House->recursive = 2;
        $house = $this->House->read();

        // this variable is used to display properly
        // the selected element on header
        if ($house['House']['user_id'] == $this->Auth->User('id')) {
            $this->set('selected_action', 'houses_view');
        }

        if ($this->Auth->User('role') != 'admin' &&
            $this->Auth->User('id') != $house['House']['user_id']) {
            if(($house["User"]["banned"] == 1)
                || ($house["User"]["enabled"] == 0)
                || ($house['House']['visible'] == 0)){
                    $this->cakeError('error404');
            }
        }

        $this->set('house', $house);
        $this->set('images', $house['Image']);

        foreach ($house['Image'] as $image) {
            if ($image['is_default'] == 1) {
                $this->set('default_image_location', $image['location']);
                $this->set('default_image_id', $image['id']);
                break;
            }
        }

		/* accessed by the View, in order to compile the appopriate link to post to Facebook */
        $fb_app_uri = Configure::read( 'fb_app_uri' );
        $fb_app_uri = $this->appendIfAbsent( $fb_app_uri, '/' );
		$this->set( 'fb_app_uri', $fb_app_uri );
		$this->set( 'facebook', $this->Session->read( 'facebook' ) );
    }

    function add() {
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'houses_view');
        $this->set('title_for_layout','Προσθήκη σπιτιού');

        /* if user already owns a house bail out
         * this does not apply for real estates  */
        if ($this->Auth->User('role') != 'realestate') {
            $conditions = array("user_id" => $this->Auth->user('id'));
            $res = $this->House->find('first', array('conditions' => $conditions));
            if (isset($res["House"]["id"])) {
                $this->Session->setFlash('Έχετε ήδη ένα σπίτι αποθηκευμένο.',
                        'default', array('class' => 'flashRed'));
                $this->redirect(array('action' => 'view', $res["House"]["id"]));
            }
        }

        if (!empty($this->data)) {
            if ($this->Auth->User('role') == 'realestate') {
                $this->data['House']['currently_hosting'] = 1;
                $this->data['House']['total_places'] = 9;
            }
            $this->data['House']['user_id'] = $this->Auth->user('id');
            /* debug: var_dump($this->data); die(); */

            $this->data['House']['geo_distance'] = $this->computeDistance();

            if ($this->House->save($this->data)) {
                $this->log('User '.$this->Auth->user('username').' add new house', 'activity');
                $this->Session->setFlash('Το σπίτι αποθηκεύτηκε με επιτυχία.',
                    'default', array('class' => 'flashBlue'));
                $hid = $this->House->id;

                // post requires municipality name, house type and user role
                // TODO check if recursive is actually required
//                 $this->House->recursice = 2;
                $house = $this->House->read();
                // post to facebook application wall
                if ( $this->data['House']['visible'] == 1 ) {
                    $this->postToAppWall( $house, false );
                }
                $this->redirect(array('action' => "view", $hid));
            }
        }

        $this->setAddEditVars();
    }

    function delete($id) {
        $this->set('title_for_layout','Διαγραφή σπιτιού');
        $this->checkAccess( $id );

        if($this->Auth->User('role') == 'realestate'){
            $redirectTarget = array(
                'controller' => 'houses', 'action'=> 'manage');
        }else if($this->Auth->User('role') == 'user'){
            $profileid = $this->Profile->find('first',
                array('fields' => 'Profile.id',
                'conditions' => array(
                    'Profile.user_id' => $this->Auth->user('id'))));
            $profileid = $profileid['Profile']['id'];
            $redirectTarget = array(
                'controller' => 'profiles', 'action'=> 'view', $profileid);
        }

        $this->House->begin();

        /* delete associated images first */
        if ( ! $this->House->Image->deleteAll(array("house_id" => $id)) ) {
            $this->House->rollback();
            $this->Session->setFlash('Αδυναμία διαγραφής εικόνων από την βάση.',
                    'default', array('class' => 'flashRed'));
            $this->redirect($redirectTarget);
        }
        else {
            /* remove from FS */
            if (! $this->House->Image->delete_all($id) ) {
                $this->House->rollback();
                $this->Session->setFlash('Αδυναμία διαγραφής εικόνων από το σύστημα αρχείων.',
                    'default', array('class' => 'flashRed'));
                $this->redirect($redirectTarget);
            }
            else {
                /* delete house */
                if (! $this->House->delete( $id ) ) {
                    $this->House->rollback();
                    $this->Session->setFlash('Αδυναμία διαγραφής σπιτιού',
                        'default', array('class' => 'flashRed'));
                    $this->redirect($redirectTarget);
                }

            }
        }
        $this->House->commit();

        $this->Session->setFlash('Το σπίτι διαγράφηκε με επιτυχία.',
            'default', array('class' => 'flashBlue'));

        $this->log( 'User '.$this->Auth->user('username').' delete his house', 'activity');
        $this->redirect($redirectTarget);
    }


    function edit($id = null) {
        $this->set('title_for_layout','Επεξεργασία σπιτιού');
        $this->checkExistance($id);
        $this->checkAccess($id);
        $this->House->id = $id;

        $house = $this->House->read();

        $this->set('house', $house);

        // get default image for house
        $imageThumbLocation = 'home.png';
        foreach($house['Image'] as $image) {
            if ($image['is_default'] == 1) {
                $imageThumbLocation = 'uploads/houses/'.$id.'/thumb_'.$image['location'];
                break;
            }
        }

        $this->set('imageThumbLocation', $imageThumbLocation);

        if (empty($this->data)) {
            $this->data = $house;
        }
        else {

            $this->data['House']['geo_distance'] = $this->computeDistance();

            if ($this->House->saveAll($this->data, array('validate'=>'first'))) {
                $this->Session->setFlash('Το σπίτι ενημερώθηκε με επιτυχία.',
                    'default', array('class' => 'flashBlue'));

                $this->log( 'User '.$this->Auth->user('username').' edit his house', 'activity');
                // post requires municipality name, house type and user role
                $this->House->recursive = 2;
                $house = $this->House->read();
                // post updated house on application's page on Facebook
                if ( $this->data['House']['visible'] == 1 ) {
                    $this->postToAppWall( $house, true );
                }

                $this->redirect(array('action' => "view", $id));
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
        unset($no_mates[1]);
        $no_mates[10] = 10;
        $this->set('places_availability_extra', $no_mates);
    }


    private function checkAccess( $house_id ){
        $this->House->id = $house_id;
        $house = $this->House->read();
        $user_id = $house['User']['id'];


        if($this->Auth->user('id') != $user_id) {

            $this->log( 'User '.$this->Auth->user('username').
                ' try to intervene'.$house['User']['username']."'s profile", 'warning');
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

    function getLastModified(){
        $order = array('House.modified' => 'desc');
        $conditions = array('House.visible' => 1,
            'House.user_id !=' => $this->Auth->User('id'),
            'User.banned' => 0, 'User.enabled' => 1);
        $results = $this->simpleSearch($conditions, null, $order, false);
        $results = array_slice($results, 0, 5);
        return $results;
    }

    function getLastPreferred(){
        $this->checkRole('user');
        $limit = 5;
        $prefs = $this->loadSavedPreferences();

        $order = array('House.modified' => 'desc', 'House.id' => 'asc');

        // exclude logged user's house
        array_push($prefs['house_prefs'], array(
            'House.user_id !=' => $this->Auth->User('id'), 'User.enabled' => 1));
        $results = $this->simpleSearch($prefs['house_prefs'],
                                       empty($prefs['mates_prefs']) ?
                                            null : $prefs['mates_prefs'],
                                       $order,
                                       false);

        $results = array_slice($results, 0, $limit);
        return $results;
    }

    function manage(){
        // this variable is used to properly display
        // the selected element on header
        $this->set('selected_action', 'houses_manage');
        $this->set('title_for_layout','Διαχείριση σπιτιών');

        $this->checkRole('realestate');

        // drop down menu options
        $this->set('house_type_options',
                   $this->House->HouseType->find('list',array('fields' => array('type'))));
        $this->set('order_options', array('τελευταία ενημέρωση',
                                            'τιμή - αύξουσα',
                                            'τιμή - φθίνουσα',
                                            'εμβαδό - αύξουσα',
                                            'εμβαδό - φθίνουσα',
                                            'δήμο - αύξουσα',
                                            'δήμο - φθίνουσα',
                                            'διαθέσιμες θέσεις - αύξουσα',
                                            'διαθέσιμες θέσεις - φθίνουσα',
                                            'απόσταση από ΤΕΙ - αύξουσα',
                                            'απόσταση από ΤΕΙ - φθίνουσα'));


        $uid = $this->Auth->User('id');
//        $houseConditions['House.visible'] = 1;
        $houseConditions['House.user_id'] = $uid;
        if(!empty($this->params['url']['house_type'])){
            $houseConditions['House.house_type_id'] .= $this->params['url']['house_type'];
        }
        $options['conditions'] = $houseConditions;

        if(isset($this->params['url']['order_by'])){
            $orderBy = $this->getOrderCondition($this->params['url']['order_by']);
            $options['order'] = $orderBy;
        }
        // pagination options
        $options['limit'] = 15;
        $this->paginate = $options;
        $results = $this->paginate('House');
        $this->set('results', $results);
        $this->set('limit', $this->paginate['limit']);
    }

    function search(){

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
                $prefs['mates_prefs'],
                array('House.modified' => 'DESC'),
                false);

            // return municipality names
            $municipalities = $this->House->Municipality->find('list');
            $this->set('municipalities', $municipalities);

            // return house type names
            $house_types = $this->HouseType->find('list', array('fields' => array('type')));
            $this->set('house_types', $house_types);

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
                                            'διαθέσιμες θέσεις - φθίνουσα',
                                            'απόσταση από ΤΕΙ - αύξουσα',
                                            'απόσταση από ΤΕΙ - φθίνουσα'));

        $fb_app_uri = Configure::read( 'fb_app_uri' );

        if(!isset($this->params['url']['search'])){
            $results = $this->simpleSearch( $this->getHouseConditions(),
                                                    null,
                                                    $this->getOrderCondition(0)
                                                  );

            $this->set('results', $results);
        }

        if(isset($this->params['url']['clear'])){
            $this->redirect('search');
        }

        if(isset($this->params['url']['save'])) {
            $this->saveSearchPreferences();
            // store user's input
            $this->set('defaults', $this->params['url']);

            if ($this->Auth->User('role') == 'realestate') {
                $results = $this->simpleSearch( $this->getHouseConditions(),
                                                null,
                                                $this->getOrderCondition($this->params['url']['order_by'])
                                              );
            } else {
                $results = $this->simpleSearch( $this->getHouseConditions(),
                                                $this->getMatesConditions(),
                                                $this->getOrderCondition($this->params['url']['order_by'])
                                              );
            }
            $this->set('results', $results);
        }

        if(isset($this->params['url']['search'])) {
            if ($this->Auth->User('role') == 'realestate') {
                $results = $this->simpleSearch( $this->getHouseConditions(),
                                                null,
                                                $this->getOrderCondition($this->params['url']['order_by'])
                                              );
            } else { // if user or admi
                $mates_conds = $this->getMatesConditions();
                if (isset($this->params['url']['with_roommate'])) {
                    $results = $this->simpleSearch( $this->getHouseConditions(),
                                                    $mates_conds,
                                                    $this->getOrderCondition($this->params['url']['order_by']),
                                                    true, "user"
                                                  );

                } else {
                    $results = $this->simpleSearch( $this->getHouseConditions(),
                                                    $mates_conds,
                                                    $this->getOrderCondition($this->params['url']['order_by'])
                                                  );
                }

                if ($mates_conds != null || isset($this->params['url']['with_roommate'])) {
                    $extra_results = $this->simpleSearch($this->getHouseConditions(),
                                                            null, null, false, "realestate");
                    if (!empty($extra_results)) {
                        $this->set("extra_results", true);
                    }
                }
            }

            $this->set('results', $results);
            // store user's input
            $this->set('defaults', $this->params['url']);

            /* accessed by the View, in order to compile the appopriate link to post to Facebook */
            $fb_app_uri = $this->appendIfAbsent( Configure::read('fb_app_uri'), '/' );
        }

        if (isset($this->params['url']['extra'])) {
            $results = $this->simpleSearch( $this->getHouseConditions(),
                                            null,
                                            $this->getOrderCondition($this->params['url']['order_by']),
                                            true,
                                            "realestate"
                                          );

            $this->set('results', $results);
            // store user's input
            $this->set('defaults', $this->params['url']);

            /* accessed by the View, in order to compile the appopriate link to post to Facebook */
            $fb_app_uri = $this->appendIfAbsent( Configure::read('fb_app_uri'), '/' );
        }

        if(isset($this->params['url']['load'])) {
            $prefs = $this->loadSavedPreferences();
            // store user's input
            $this->set('defaults', $prefs['defaults']);

            if ($this->Auth->User('role') == 'realestate') {
                $results = $this->simpleSearch( $prefs['house_prefs'],
                                                null,
                                                $this->getOrderCondition($this->params['url']['order_by'])
                                              );
            } else {
                $results = $this->simpleSearch( $prefs['house_prefs'],
                                                $prefs['mates_prefs'],
                                                $this->getOrderCondition($this->params['url']['order_by'])
                                              );
            }
            $this->set('results', $results);
        }

        /* accessed by the View, in order to compile the appopriate link to post to Facebook */
        $this->set( 'fb_app_uri', $fb_app_uri );
        $this->set( 'facebook', $this->Session->read( 'facebook' ) );
        $this->set('house_types', $this->HouseType->find('list', array('fields' => array('type'))));
    }


    private function simpleSearch(  $houseConditions, $matesConditions = null,
                                    $orderBy = null, $pagination = true,
                                    $user_role = null, $fields = null,
                                    $isWebService = false) {

        //----- WARNING -----//
        // $fields variable *must not* contain anything related to Image
        // model (e.g. 'Image.location', 'Image.is_default').
        // By default the results will contain all the fields of the default
        // image of each house and one must filter the result to get the
        // wanted output.

        if ($fields != null) $options['fields'] = $fields;

        $options['conditions'] = $matesConditions != null ?
                                 array_merge($houseConditions, $matesConditions) :
                                 $houseConditions;

        if ($orderBy != null) $options['order'] = $orderBy;

        // used in join with users table
        $user_conditions = array('House.user_id = User.id');
        if ($user_role === "user") {
            array_push($user_conditions, 'User.role = "user"');
        } else if ($user_role === "realestate") {
            array_push($user_conditions, 'User.role = "realestate"');
        }

        $this->House->bindModel(array(
            'belongsTo' => array(
                'User' => array(
                    'conditions' => $user_conditions
                ),
                'Profile' => array(
                    'foreignKey' => false,
                    'conditions' => array('Profile.user_id = User.id')
                ),
                'RealEstate' => array(
                    'foreignKey' => false,
                    'conditions' => array('RealEstate.user_id = User.id')
                )
            ),
            'hasMany' => array(
                'Image' => array(
                    'conditions' => array('Image.is_default' => 1)
                )
            )
        ), false);

        $this->House->recursive = 1;
        if($pagination === true) {
            $options['limit'] = 15;
            $this->paginate = $options;
            $results = $this->paginate('House');
            $this->set('pagination_limit', $options['limit']);
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
            $house_prefs['House.image_count >'] = 0 ;
            $defaults['has_photo'] = 1;
        }
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
            $defaults['couple'] = $prefs['pref_couple'];
        }

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
		$priceMax = (isset($search_args['max_price']))?$search_args['max_price']:NULL;
		$areaMin = (isset($search_args['min_area']))?$search_args['min_area']:NULL;
		$areaMax = (isset($search_args['max_area']))?$search_args['max_area']:NULL;
        $this->House->User->Profile->Preference->save(array(
                        'id' => $profile['Preference']['id'],
                        // House
                        'price_max' => $priceMax,
                        'area_min' => $areaMin,
                        'area_max' => $areaMax,
                        'pref_municipality' => $search_args['municipality'],
                        'pref_furnitured' => $search_args['furnitured'],
                        'pref_disability_facilities' => !empty($search_args['accessibility']),
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


   private function getHouseConditions( ) {


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

        if (isset($house_prefs['furnitured']) && $house_prefs['furnitured'] < 2)
            $house_conditions['House.furnitured'] = $house_prefs['furnitured'];

        if(isset($house_prefs['accessibility']))
            $house_conditions['House.disability_facilities'] = 1;

        if(isset($house_prefs['has_photo']))
            $house_conditions['House.image_count >'] = 0;

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


        if( $this->Auth->User('role') != 'admin' ){
            $house_conditions['House.visible'] = 1;
        }

        $house_conditions['User.banned !='] = 1;
        $house_conditions['User.enabled'] = 1;

        return $house_conditions;
    }


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

        if(empty($mates_conditions)) return null;


        // required condition for the left join
        array_push($mates_conditions, 'User.id = Profile.user_id');

        return $mates_conditions;
    }


    private function getOrderCondition($selected_order) {
        $order = '';

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
            case 9:
                // order by distance - asc
                $order = array('geo_distance' => 'IS NULL ASC', 'House.geo_distance' => 'ASC');
                break;
            case 10:
                // order by distance - desc
                $order = array('House.geo_distance' => 'DESC');
                break;
        }

        return $order;
    }

    // ------------------------------------------------------------------------
    // Manual [geo_distance] ordering. -- SECTION START *DEPRECATED*
    // ------------------------------------------------------------------------

    // The contents of the array are sorted based on their 'geo_distance' field.
    // The sorting order is determined by the url parameter 'order_by'.
    /*
    private function orderByDistance( &$array ) {
        $order;

        if( !array_key_exists( 'url', $this->params ) ) return $array;
        $url = $this->params['url'];

        if( !array_key_exists( 'order_by', $url ) )   return $array;
        $order = $url['order_by'];

        if( empty( $order ) )   return $array;

       switch( $order ) {
            case 9:
                usort( $array, array( "HousesController", "distanceInAsc" ) );
                break;
            case 10:
                usort( $array, array( "HousesController", "distanceInDesc" ) );
                break;
        }
        return $array;
    }

    // Compares two House arrays based on their [geo_distance] attribute. This
    // method is to be used with php function usort() or equivalent, so as to
    // reposition the elements of an array of Houses in ascending order based on
    // their [geo_distance] attribute.
    static function distanceInAsc($h1, $h2) {
        $d1 = $h1['House']['geo_distance'];
        $d2 = $h2['House']['geo_distance'];

        // both are null and, thus, equal
        if( is_null( $d1 ) && is_null( $d2 ) )  return 0;

        // house with no computed distance is sent to the bottom of the results
        if( is_null( $d1 ) )    return 1;
        if( is_null( $d2 ) )    return -1;

        // when both distances are valid, compare them and return integer
        if( $d1 < $d2 ) return -1;
        if( $d1 > $d2 ) return 1;

        return 0;
    }

    // Compares two House arrays based on their [geo_distance] attribute. This
    // method is to be used with php function usort() or equivalent, so as to
    // reposition the elements of an array of Houses in descending order based
    // on their [geo_distance] attribute.
    static function distanceInDesc($h1, $h2) {
        $d1 = $h1['House']['geo_distance'];
        $d2 = $h2['House']['geo_distance'];

        // both are null and, thus, equal
        if( is_null( $d1 ) && is_null( $d2 ) )  return 0;

        // house with no computed distance is sent to the bottom of the results
        if( is_null( $d1 ) )    return 1;
        if( is_null( $d2 ) )    return -1;

        // when both distances are valid, compare them and return integer
        if( $d1 < $d2 ) return 1;
        if( $d1 > $d2 ) return -1;
        return 0;
    }
     */

    // ------------------------------------------------------------------------
    // Manual [geo_distance] ordering. -- SECTION END *DEPRECATED*
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    // Facebook functions -- SECTION START
    // ------------------------------------------------------------------------

    // Posts an announcement on the application's page on Facebook.
    // The supplied [$house] parameter is a two-dimensional array which
    // contains the entries 'House', 'Municipality' and 'User'.
    // [$isUpdated] specifies whether to include a short note indicating the
    // house has been updated or created. Defaults to [false].
    protected function postToAppWall( $house, $isUpdated=false ) {

        if( is_null( $house ) ) return;

        $furnished = $this->describeFurnished( $house );
        $occupation_availability = $this->describeOccupation( $house );
        $geo_distance = $this->describeDistance( $house );
        $update = $isUpdated
            ? 'Τροποποιήθηκε η αγγελία: '
            : 'Ανερτήθη η αγγελία: ';

        $fb_app_uri = Configure::read( 'fb_app_uri' );
        $fb_app_uri = $this->appendIfAbsent( $fb_app_uri, '/' );
        $facebook = $this->Session->read( 'facebook' );

        try {
            $facebook->api( $facebook->getAppId( ) . '/feed', 'POST', array(

                'message' =>
                    $update
                    . $house['HouseType']['type'] . ' '
                    . $house['House']['area'] . 'τμ, '
                    . 'Ενοικίο ' . $house['House']['price'] . '€, '
                    . $furnished
                    . 'Δήμος ' . $house['Municipality']['name']
                    . $occupation_availability
                    . $geo_distance,

                'name' => 'Δείτε περισσότερα εδώ...',
                'link' => $fb_app_uri . 'houses/view/' . $house['House']['id'],
                'caption' => '«Συγκατοικώ»',
            ) );

        } catch( FacebookApiException $e ) {

            $this->Session->setFlash(
                'Προέκυψε ένα σφάλμα κατά την κοινοποίηση της αγγελίας στο '
                . 'Facebook.',
                'default',
                array('class' => 'flashRed') );
        }
    }

    // Creates a properly formatted sentence describing house furnishing.
    protected function describeFurnished( $house ) {
        $furnished = null;
        if( $house['House']['furnitured'] )  $furnished = 'Επιπλωμένο, ';
        else $furnished = 'Μη επιπλωμένο, ';
        return $furnished;
    }

    // Creates a properly formatted sentence describing house availability.
    protected function describeOccupation( $house ) {
        $occupation_availability = null;
        if( $house['User']['role'] != 'user' ) {

            $occupation_availability = '';
        } else {echo $house['User']['role'];
            $occupation_availability =
                ', Διαθέσιμες θέσεις '
                . Sanitize::html( $house['House']['free_places'] );
        }
        return $occupation_availability;
    }

    // Creates a properly formatted sentence describing house distance from TEI.
    protected function describeDistance( $house ) {
        $geo_distance = $house['House']['geo_distance'];
        if( !is_null( $geo_distance ) ) {
            $geo_distance = ', Αποστάση από ΤΕΙ '
                . number_format( $geo_distance, 2 ) . ' χλμ.';
        } else {
            $geo_distance = '';
        }
        return $geo_distance;
    }

    // Creates a Facebook instance which is then made available though
    // the Session.
    // Execute once per session. More won't hurt, but is not required.
    protected function facebookInit( ) {

        $fb_app_id = Configure::read( 'fb_app_id' );
        $fb_secret = Configure::read( 'fb_secret' );

        $facebook = new Facebook( array(
            'appId' => $fb_app_id,
            'secret' => $fb_secret ) );

        $this->Session->write( 'facebook', $facebook );
    }

    // ------------------------------------------------------------------------
    // Facebook functions -- SECTION END
    // ------------------------------------------------------------------------

    private function checkRole($role){
        if($this->Session->read('Auth.User.role') != $role){
            $this->cakeError('error403');
        }
    }

    // Appends [$char] to [$string] if the latter does not end with the
    // character contained withing the former. [$char] <b>must</b> be
    // one-character long.
    protected function appendIfAbsent( $string, $char ) {

        if( strpos ( $string, $char, strlen( $string ) - 1 ) == false ) {

            $string = $string . $char;
        }
        return $string;
    }


    private function getResponseXmlFields() {
        $fields = array('House.id',
                        'House.address',
                        'House.postal_code',
                        'House.area',
                        'House.bedroom_num',
                        'House.bathroom_num',
                        'House.price',
                        'House.construction_year',
                        'House.solar_heater',
                        'House.furnitured',
                        'House.aircondition',
                        'House.garden',
                        'House.parking',
                        'House.shared_pay',
                        'House.security_doors',
                        'House.disability_facilities',
                        'House.storeroom',
                        'House.availability_date',
                        'House.rent_period',
                        'House.description',
                        'House.created',
                        'House.modified',
                        'House.currently_hosting',
                        'House.total_places',
                        'House.user_id',
                        'House.visible',
                        'House.latitude',
                        'House.longitude',
                        'House.geo_distance',
                        'House.free_places',
                        'Municipality.name',
                        'Floor.type',
                        'HouseType.type',
                        'HeatingType.type'
                        );

        return $fields;
    }

    // Computes and sets the distance between '$this' House and the default
    // target (in private function haversinceDistance). It uses the structure
    // $this->data.
    private function computeDistance() {

        $location = array(
            'latitude' => $this->data['House']['latitude'],
            'longitude' => $this->data['House']['longitude'] );
        return $this->haversineDistance($location);
    }

    // Computes the haversine distance between the to supplied locations. Each
    // parameter must be an array that contains the keys 'latitude' and
    // 'longitude'. If the [to] parameter is omitted (i.d., is_null on it
    // returns true), the TEI of Athen's coordinates, will be used.
    private function haversineDistance($from, $to=null) {
        $radius = 6371;

        if( !is_numeric( $from['latitude'] ) ||
            !is_numeric( $from['longitude']) ) {

            return null;
        }

        if( is_null( $to ) ) {
            $to = array( 'latitude' => 38.004135, 'longitude' => 23.676619 );
        }

        $latFrom = deg2rad( $from['latitude'] );
        $latTo = deg2rad( $to['latitude'] );
        $latDiff = deg2rad( $to['latitude'] - $from['latitude'] );
        $lngDiff = deg2rad( $to['longitude'] - $from['longitude'] );

        $latHaversine = sin( $latDiff/2 )*sin( $latDiff/2 );
        $lngHaversine = sin( $lngDiff/2 )*sin( $lngDiff/2 );

        $root = sqrt(
            $latHaversine +
                cos( $latFrom )
                *cos( $latTo )
                *sin( $lngHaversine ) );

        $distance = 2*$radius*asin( $root );
        return $distance;
    }

    // ------------------------------------------------------------------------
    // REST - Web Services
    // ------------------------------------------------------------------------

    function handleGetRequest($id = null) {
        if ((strpos($this->params['url']['url'], 'houses') == true) &&
            ($id != null)) {
            $this->webServiceStatus(404);
            return;
        }
        $house_conds = $this->getHouseConditions();
        if ($id != null) array_push($house_conds, array('House.id' => $id));
        $result = $this->simpleSearch(  $house_conds,
                                        null, null, false, null,
                                        $this->getResponseXmlFields(),
                                        true);

        if (empty($result)) {
            $this->webServiceStatus(404);
            return;
        }

        // return the Image itself base64 encoded
        for ($i = 0; $i<count($result); $i++) {
            $result[$i]['Image'] = $this->get_house_bin_image($result[$i]['House']['id']);

            // latitude and longitude are never returned
            $result[$i]['House']['latitude'] = null;
            $result[$i]['House']['longitude'] = null;
        }

        $this->set('houses', $result);
        if ($_SERVER['HTTP_ACCEPT'] === 'application/json') {
            $this->layout = 'json/default';
            $this->render('json/public');
        } else {
            $this->layout = 'xml/default';
            $this->render('xml/public');
        }
    }

    function handlePostRequest($id = null) {

        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $json_data = json_decode(file_get_contents("php://input"), true);
            if (!empty($json_data)) $this->data = $this->jsonKeysToCamelcase($json_data);
        }

        if ($id != null) {
            $this->webServiceStatus(400);
            return;
        }
        $this->layout = 'xml/default';
        $user_id = $this->authenticate();
        if ($user_id == NULL) {
            // access denied
            $this->webServiceStatus(403);
            return;
        }

        if (!empty($this->data)) {
            $user_role = $this->get_role($user_id);
            if ($user_role == 'user') {
                $house_count = $this->count_houses($user_id);
                if ($house_count >= 1) {
                    // users add only one house
                    $this->webServiceStatus(412);
                    return;
                }
            }
            elseif ($user_role == 'admin') {
                // admin cannot add houses
                $this->webServiceStatus(412);
                return;
            }
            $this->data['House']['user_id'] = $user_id;
            $this->data['House']['geo_distance'] = $this->computeDistance();
            $this->unsetXmlElements();
            $this->setRequiredIds();

            if ($this->House->save($this->data) != false) {
                // success
                $this->webServiceStatus(200);
                return;
            } else {
                $this->webServiceStatus(500);
                return;
            }
        }
        // empty data
        $this->webServiceStatus(400);
        return;
    }

    function handlePutRequest($id = null) {
        $this->layout = 'xml/default';
        $user_id = $this->authenticate();
        if ($user_id == NULL) {
            $this->webServiceStatus(403);
            return;
        }

        if ($id != null) {
            if (! $this->house_exist($id) ) {
                $this->webServiceStatus(404);
                return;
            }

            if (! $this->owns_house($user_id, $id) ) {
                $this->webServiceStatus(403);
                return;
            }

            $this->unsetXmlElements();
            $this->data['House']['id'] = $id;
            $this->data['House']['geo_distance'] = $this->computeDistance();
            $this->data['House']['user_id'] = $user_id;
            $this->setRequiredIds();
            if ($this->House->saveAll($this->data)) {
                $this->webServiceStatus(200);
                return;
            } else {
                $this->webServiceStatus(500);
                return;
            }
        } else {
            // TODO if the $id === null then create the house
            // for now just say we didn't find the house
            $this->webServiceStatus(404);
            return;
        }
    }

    function handleDeleteRequest($id = null) {
        $user_id = $this->authenticate();
        if ($user_id == NULL) {
            $this->webServiceStatus(403);
            return;
        }

        if ($id != null) {
            if (! $this->house_exist($id) ) {
                $this->webServiceStatus(404);
                return;
            }

            if (! $this->owns_house($user_id, $id) ) {
                $this->webServiceStatus(403);
                return;
            }

            $this->House->id = $id;
            $this->House->begin();
            /* delete associated images first */
            if ( ! $this->House->Image->deleteAll(array("house_id" => $id)) ) {
                $this->House->rollback();
                $this->webServiceStatus(500);
                return;
            }
            else {
                /* remove from FS */
                if (! $this->House->Image->delete_all($id) ) {
                    $this->House->rollback();
                    $this->webServiceStatus(500);
                    return;
                }
                else {
                    /* delete house */
                    if (! $this->House->delete( $id ) ) {
                        $this->House->rollback();
                        $this->webServiceStatus(500);
                        return;
                    }
                }
            }
            $this->House->commit();
            $this->webServiceStatus(200);
            return;

        } else {
            $this->webServiceStatus(412);
            return;
        }

    }

    private function webServiceStatus($id) {
        if (array_key_exists($id, $this->xml_status) ) {
            $this->set('code', $id);
            $this->set('msg', $this->xml_status[$id]);
        } else {
            die('ERROR: UNDEFINED XML STATUS CODE');
        }
        $this->layout = 'xml/default';
        $this->render('xml/status');
    }

    //////////////////////////////////////////////////
    // Set the required ID fields in House model
    // (municipality, floor, house_type, heating_type)
    // and remove the respective types/names from
    // $this->data
    //////////////////////////////////////////////////
    private function setRequiredIds() {
        $house = $this->data['House'];

        // municipality
        $municipality = $this->Municipality->find('first', array(
                            'fields' => 'Municipality.id',
                            'conditions' => array(
                                'Municipality.name' => $house['Municipality']['name']
                            )
                        ));
        $this->data['House']['municipality_id'] = $municipality['Municipality']['id'];
        unset($this->data['House']['Municipality']);

        // floor
        $floor = $this->Floor->find('first', array(
                    'fields' => 'Floor.id',
                    'conditions' => array(
                        'Floor.type' => $house['Floor']['type']
                    )
                 ));
        $this->data['House']['floor_id'] = $floor['Floor']['id'];
        unset($this->data['House']['Floor']);

        // house type
        $house_type = $this->HouseType->find('first', array(
                        'fields' => 'HouseType.id',
                        'conditions' => array(
                            'HouseType.type' => $house['HouseType']['type']
                        )
                     ));
        $this->data['House']['house_type_id'] = $house_type['HouseType']['id'];
        unset($this->data['House']['HouseType']);

        // heating type
        $heating_type = $this->HeatingType->find('first', array(
                            'fields' => 'HeatingType.id',
                            'conditions' => array(
                                'HeatingType.type' => $house['HeatingType']['type']
                            )
                        ));
        $this->data['House']['heating_type_id'] = $heating_type['HeatingType']['id'];
        unset($this->data['House']['HeatingType']);
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

    // ------------------------------------------------------------------------
    // web services helper functions
    // ------------------------------------------------------------------------

    private function owns_house($user_id, $house_id) {
        // check if a given user owns a given house
        $this->House->recursive = -1;
        $conditions = array('House.id' => $house_id);
        $house = $this->House->find('first',
            array('conditions' => $conditions, 'fields' => 'user_id'));
        if ($house['House']['user_id'] == $user_id) {
            return true;
        }
        return false;
    }

    private function house_exist($id) {
        // check if house with given id exits
        $this->House->recursive = -1;
        $this->House->id = $id;
        $house = $this->House->read();
        if (empty($house)) {
            return false;
        }
        return true;
    }

    private function get_role($id) {
        // return role of user with given id
        $this->User->recursive = -1;
        $conditions = array('User.id' => $id);
        $user = $this->User->find('first',
            array('conditions' => $conditions, 'fields' => 'role'));
        return $user['User']['role'];
    }

    private function count_houses($id) {
        // return how many houses user with given id owns
        $conditions = array('user_id' => $id);
        $n = $this->House->find('count', array('conditions' => $conditions));
        return $n;
    }

    private function get_house_bin_image($id) {
        // returns default house image for given $id encoded in base64
        // params: $id -> house id
        if ($id == null) return null;

        $conditions = array('House.id' => $id, 'Image.is_default' => 1);
        $name = $this->House->Image->find('first',
            array('conditions' => $conditions, 'fields' => 'location'));

        if (empty($name)) {
            return null;
        }

        // build image file path
        $filepath = WWW_ROOT . "img/uploads/houses/" . $id . "/" .
            "thumb_" . $name['Image']['location'];

        if (! file_exists($filepath)) {
            return null;
        }

        $bin = fread(fopen($filepath, "r"), filesize($filepath));
        return base64_encode($bin);
    }

    private function unsetXmlElements() {
        unset($this->data['House']['xmlns']);
        unset($this->data['House']['id']);
        unset($this->data['House']['created']);
        unset($this->data['House']['modified']);
        unset($this->data['House']['free_places']);
    }

    private function jsonKeysToCamelcase ($json_data) {

        if (isset($json_data['house'])) {
            if (isset($json_data['house']['municipality'])) {
                $json_data['house']['Municipality'] = $json_data['house']['municipality'];
                unset($json_data['house']['municipality']);
            }

            if (isset($json_data['house']['floor'])) {
                $json_data['house']['Floor'] = $json_data['house']['floor'];
                unset($json_data['house']['floor']);
            }

            if (isset($json_data['house']['house_type'])) {
                $json_data['house']['HouseType'] = $json_data['house']['house_type'];
                unset($json_data['house']['house_type']);
            }

            if (isset($json_data['house']['heating_type'])) {
                $json_data['house']['HeatingType'] = $json_data['house']['heating_type'];
                unset($json_data['house']['heating_type']);
            }

            if (isset($json_data['house']['image'])) {
                $json_data['house']['Image'] = $json_data['house']['image'];
                unset($json_data['house']['image']);
            }

            $json_data['House'] = $json_data['house'];
            unset($json_data['house']);
        }

        return $json_data;
    }
}
?>
