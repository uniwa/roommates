<?php
class ProfilesController extends AppController {

    var $name = 'Profiles';

    function index() {
        $this->set('profiles', $this->Profile->find('all', array('conditions' => array('Profile.visible' => 1))));
    }

    function view($id = null) {
        $this->Profile->id = $id;
        $this->set('profile', $this->Profile->read());
    }

}
?>
