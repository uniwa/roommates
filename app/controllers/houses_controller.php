<?php

App::import('Sanitize');

class HousesController extends AppController {

    var $name = 'Houses';
    var $components = array('RequestHandler');
    var $helpers = array('Text', 'Time');
    var $paginate = array('limit' => 15);

    function index() {
        if ($this->RequestHandler->isRss()) {
            $houses = $this->House->find('all',
                        array('limit' => 20, 'order' => 'House.modified DESC'));
            return $this->set(compact('houses'));
        }

		$order = array('House.modified' => 'desc');
		$selectedOrder = 0;

		if(isset($this->params['form']['selection'])){
			$selectedOrder = $this->params['form']['selection'];
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
					$orderField = 'House.currently_hosting';
					$ascDesc = $ascoptions[0];
					break;
				case 8:
					$orderField = 'House.currently_hosting';
					$ascDesc = $ascoptions[1];
					break;
			}
			$order = array($orderField => $ascDesc);
		}

        $orderOptions = array('τελευταία ενημέρωση', 'ενοίκιο αύξουσα', 'ενοίκιο φθίνουσα', 'δήμος αύξουσα', 'δήμος φθίνουσα', 'τετραγωνικά αύξουσα', 'τετραγωνικά φθίνουσα', 'ένοικοι αύξουσα', 'ένοικοι φθίνουσα');
        $this->set('order_options', array('options' => $orderOptions, 'selected' => $selectedOrder));

        $this->paginate = array(
            'order' => $order, 'limit' => 15
        );
        $houses = $this->paginate('House');
        $this->set('houses', $houses);
        //$this->set('houses', $this->House->find('all'));
    }

    function beforeFilter() {
        parent::beforeFilter();
        
        if( $this->RequestHandler->isRss()){
            $this->Auth->allow( 'index' );
        }

        if(!class_exists('L10n'))
            App::import('Core','L10n');

        $this->L10n = new L10n();
        $this->L10n->get('gr');
    }

    function view($id = null) {
        $this->checkExistance($id);
        $this->House->id = $id;
        $this->House->recursive = 2;
        $house = $this->House->read();

        $this->set('house', $house);

        $images = $this->House->Image->find('all',array('conditions'=>array('house_id'=>$id)));
        $this->House->Image->recursive = 0;
		$this->set('House.images', $this->paginate());
		$this->set('images', $images);

    }

    function add() {
		
        /* if user already owns a house bail out */
        $conditions = array("user_id" => $this->Auth->user('id'));
        $res = $this->House->find('first', array('conditions' => $conditions));
        if (isset($res["House"]["id"])) {
            $this->Session->setFlash('Έχετε ήδη ένα σπίτι αποθηκευμένο.');
            $this->redirect(array('action' => 'index'));
        }
        //pr($res); die();

        if (!empty($this->data)) {
            $this->data['House']['user_id'] = $this->Auth->user('id');
            /* debug: var_dump($this->data); die(); */
            if ($this->House->save($this->data)) {
                $this->Session->setFlash('Your house has been saved.');
		$hid = $this->House->id;
		//pr($hid); die();
                $this->redirect(array('action' => "view/$hid"));
            }
        }

        $this->setAddEditVars();
    }

    function delete($id) {
        $this->checkAccess( $id );
        $this->House->delete( $id );
        $this->Session->setFlash('The house with id: '.$id.' has been deleted.');
        $this->redirect(array('action'=>'index'));
    }

    function edit($id = null) {
        $this->checkExistance($id);
        $this->checkAccess($id);
        $this->House->id = $id;

        if (empty($this->data)) {
            $this->data = $this->House->read();
        }
        else {
            if ($this->House->save($this->data)) {
                $this->Session->setFlash('The house has been updated.');
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


        if( ($this->Auth->user('id') != $user_id) && ($this->Auth->user('role') != 'admin')){
            /*
            * More info about params in app/app_error.php
            */
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
        $municipalities = $this->House->Municipality->find('list');
        $this->set('municipalities', $municipalities);
        
        if(isset($this->params['url']['simple_search'])) {

            $options['joins'] = array(
                array(  'table' => 'users',
                        'alias' => 'User',
                        'type'  => 'inner',
                        'conditions' => array('House.user_id = User.id')
                ),
                array(  'table' => 'profiles',
                        'alias' => 'Profile',
                        'type'  => 'inner',
                        'conditions' => $this->getMatesConditions()
                )
            );

            $options['conditions'] = $this->getHouseConditions();
            $this->House->recursive = -1;
            $results = $this->House->find('all', $options);

            $this->set('results', $results);
            $this->set('defaults', $this->params['url']);
        }
    }

    private function getHouseConditions() {
        $house_prefs = $this->params['url'];

        $house_conditions = array();
        if(!empty($house_prefs['max_price'])) {
            $house_conditions['House.price <='] = $house_prefs['max_price'];
        }
        if(!empty($house_prefs['min_area'])) {
            $house_conditions['House.area >='] = $house_prefs['min_area'];
        }
        if(!empty($house_prefs['max_area'])) {
            $house_conditions['House.area <='] = $house_prefs['max_area'];
        }
        if(!empty($house_prefs['municipality'])) {
            $house_conditions['House.municipality_id'] = $house_prefs['municipality'];
        }
        if($house_prefs['furnitured'] < 2) {
            $house_conditions['House.furnitured'] = $house_prefs['furnitured'];
        }
        if(isset($this->params['url']['accessibility'])) {
            $house_conditions['House.disability_facilities'] = 1;
        }
        $house_conditions['House.user_id !='] = $this->Auth->user('id');

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
        if($mates_prefs['gender'] < 2) {
            $mates_conditions['Profile.gender'] = $mates_prefs['gender'];
        }
        if($mates_prefs['smoker'] < 2) {
            $mates_conditions['Profile.smoker'] = $mates_prefs['smoker'];
        }
        if($mates_prefs['pet'] < 2) {
            $mates_conditions['Profile.pet'] = $mates_prefs['pet'];
        }
        if($mates_prefs['child'] < 2) {
            $mates_conditions['Profile.child'] = $mates_prefs['child'];
        }
        if($mates_prefs['couple'] < 2) {
            $mates_conditions['Profile.couple'] = $mates_prefs['couple'];
        }
        $mates_conditions['Profile.user_id !='] = $this->Auth->user('id');
        // required condition for the inner join
        array_push($mates_conditions, 'User.id = Profile.user_id');

        return $mates_conditions;
    }
}
?>
