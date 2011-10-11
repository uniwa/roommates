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
            'order' => $order
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
        $this->House->id = $id;
        $this->House->recursive = 2;
        $house = $this->House->read();

        $this->set('house', $house);
        /* profile id of the house owner */
        $this->set('userid', $house["User"]["Profile"]["id"]);
    }

    function add() {
        if (!empty($this->data)) {
            $profile = $this->House->Profile->find('first', array('conditions' => array(
                                                       'Profile.user_id' => $this->Auth->user('id'))));
            $this->data['House']['profile_id'] = $profile['Profile']['id'];
            /* debug: var_dump($this->data); die(); */
            if ($this->House->save($this->data)) {
                $this->Session->setFlash('Your house has been saved.');
                $this->redirect(array('action' => 'index'));
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

	$this->checkAccess( $id );
	$this->House->id = $id;

        if (empty($this->data)) {
            $this->data = $this->House->read();
        }
        else {
            if ($this->House->save($this->data)) {
                $this->Session->setFlash('The house has been updated.');
                $this->redirect(array('action' => 'index'));
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
}
?>
