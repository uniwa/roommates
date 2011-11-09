<?php

App::import('Sanitize');
class RealEstatesController extends AppController {

    var $name = 'RealEstates';
    var $helpers = array('Html');
    var $uses = array('RealEstate', 'Municipality');

    function index() {

    }

    function view($id = null){
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'real_estates_view');
        $this->set('title_for_layout','Στοιχεία επικοινωνίας');
showDebug($id);
    	$this->checkExistence($id);
        $this->RealEstate->id = $id;
        $this->RealEstate->recursive = 2;
        $estate = $this->RealEstate->read();
showDebug($estate);

        /* hide banned users unless we are admin */
        if ($this->Auth->User('role') != 'admin' &&
            $this->Auth->User('id') != $estate['RealEstate']['user_id']) {
            if ($estate["User"]["banned"] == 1) {
                $this->cakeError('error404');
            }
        }
        $this->set('realEstate', $estate);

        $mid = $estate['RealEstate']['municipality_id'];
        if(isset($mid)){
            $options['fields'] = array('Municipality.name');
            $options['conditions'] = array('Municipality.id = '.$mid);
            $municipality = $this->Municipality->find('list', $options);
            $municipality = $municipality[$mid];
            $this->set('municipality', $municipality);
        }
    }

    private function checkExistence($estate_id){
        $this->RealEstate->id = $estate_id;
        $estate = $this->RealEstate->read();
        if($estate == NULL ){
            $this->cakeError( 'error404' );
        }
    }
}

?>
