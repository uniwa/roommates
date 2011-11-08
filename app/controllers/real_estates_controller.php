<?php

class RealEstatesController extends AppController {

    var $name = 'RealEstates';
    var $helpers = array('Html');


    function index() {

    }


    function view($id = null) {

        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'real_estates_view');

        $this->checkExistance($id);
        $this->RealEstate->id = $id;
        $estate = $this->RealEstate->read();

        /* hide banned users unless we are admin */
        if ($this->Auth->User('role') != 'admin' &&
            $this->Auth->User('id') != $estate['RealEstate']['user_id']) {
            if ($estate["User"]["banned"] == 1) {
                $this->cakeError('error404');
            }
        }
        $this->set('real_estate', $estate);

        pr($estate); die();
    }


    private function checkExistance($estate_id){
        $this->RealEstate->id = $estate_id;
        $estate = $this->RealEstate->read();
        if($estate == NULL ){
            $this->cakeError( 'error404' );
        }
    }
}

?>