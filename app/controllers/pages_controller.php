<?php

App::import('Sanitize');

class PagesController extends AppController{

    var $name = 'Pages';
//    var $components = array('RequestHandler', 'Token');
    var $helpers = array('Text', 'Time', 'Html');
//    var $paginate = array('limit' => 15);
    var $uses = array('House', 'HouseType', 'Municipality');

    function beforeFilter() {
        $this->Auth->allow('display');
    }

    function display(){
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'site_index');
        $this->set('title_for_layout','Αρχική σελίδα');
        
        $this->lastModified();
        // lastPreferred only for students
        if($this->Session->read('Auth.User.role') == 'user'){
            $this->lastPreferred();
        }
        $this->setHouseTypes();
        $this->setMunicipalities();
    }
    
    private function setHouseTypes(){
        $houseTypes = $this->HouseType->find('list',
            array('fields' => array('type')));
        $this->set('house_types', $houseTypes);
    }
    
    private function setMunicipalities(){
        $municipalityOptions = $this->Municipality->find('list',
            array('fields' => array('name')));
        $this->set('municipality_options', $municipalityOptions);
    }
    
    private function lastModified(){
        $houses = $this->requestAction(array('controller' => 'houses',
            'action' => 'getLastModified'));
        $this->set('housesModified', $houses);
    }
    
    private function lastPreferred(){
        $houses = $this->requestAction(array('controller' => 'houses',
            'action' => 'getLastPreferred'));
        $this->set('housesPreferred', $houses);
    }

}
?>
