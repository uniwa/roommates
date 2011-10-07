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
        //$koko = $this->House->read();
        //echo '<pre>';print_r($koko);echo'</pre>';die();       
        $this->set('house', $this->House->read());
    }

    function add() {
        if (!empty($this->data)) {
            /* replace with user id after implementing authentication */
            $this->data['House']['profile_id'] = '1';
            /* debug: var_dump($this->data); die(); */
            if ($this->House->save($this->data)) {
                $this->Session->setFlash('Your house has been saved.');
                $this->redirect(array('action' => 'index'));
            }
        }

        $this->setAddEditVars();
    }

    function delete($id) {
        $this->House->delete($id);
        $this->Session->setFlash('The house with id: '.$id.' has been deleted.');
        $this->redirect(array('action'=>'index'));
    }

    function edit($id = null) {
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
}
?>
