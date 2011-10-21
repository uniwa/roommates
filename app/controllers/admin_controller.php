<?php

class AdminController extends AppController
{
    var $name = 'Admin';
    var $uses = array();

    function beforeFilter() {
        parent::beforeFilter();
        /* if we are not admin bail out */
        if ($this->Session->read('Auth.User.role') != 'admin') {
            $this->cakeError('error403');
        }
    }

    function banned() {
        /* get list of banned users */
        App::import('Model', 'User');
        $Users = new User();

        $conditions = array("banned" => 1);
        $banned = $Users->find('all', array("conditions" => $conditions));

        $this->set('banned', $banned);
    }

}
?>
