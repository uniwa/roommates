<?php

class AdminsController extends AppController
{
    var $name = 'Admins';
    var $uses = array();

    function manage_users(){
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'manage_user');
        $this->set('title_for_layout','Διαχείριση χρηστών');

        $this->checkAccess();

        App::import('Model', 'User');
        $paginate = array(
            'fields' => array('User.username', 'User.banned',
            'Profile.id', 'Profile.firstname', 'Profile.lastname',
            'Profile.email'),
            'limit' => 50
        );
        $user = new User();
        $this->set('limit', $this->paginate['limit']);

        if(isset($this->params['url']['name']) || isset($this->params['url']['banned'])){
            $parameters = $this->params['url'];
            if($parameters['banned'] != 1){
                $conditions = array('OR'=>array(
                    'User.username LIKE' => "%".$parameters['name']."%",
                    'Profile.lastname LIKE' => "%".$parameters['name']."%",
                    'Profile.firstname LIKE ' => "%".$parameters['name']."%"));
            }else{
                $conditions['banned'] = 1;
            }
            $conditions['User.role'] = 'user';
            $results = $this->paginate('User', $conditions);
            $this->set('results', $results);
        }else{
            $conditions['User.role'] = 'user';
            $results = $this->paginate('User', $conditions);
            $this->set('results', $results);
        }
    }

    function manage_realestates(){
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'manage_realestate');
        $this->set('title_for_layout','Διαχείριση εκμισθωτών');

        $this->checkAccess();

        App::import('Model', 'User');
        $paginate = array(
            'fields' => array('User.username', 'User.banned', 'User.enabled',
            'RealEstate.id', 'RealEstate.firstname', 'RealEstate.lastname',
            'RealEstate.email'),
            'limit' => 50
        );
        $user = new User();
        $this->set('limit', $this->paginate['limit']);

        if (! empty($this->params['url'])) {
            $parameters = $this->params['url'];
        }
        elseif (! empty($this->params['named'])) {
            $parameters = $this->params['named'];
        }
        else {
            $parameters = array();
        }

        if(isset($parameters['name']) || isset($parameters['banned'])
            || isset($parameters['url']['disabled'])){

            if(isset($parameters['name']) && ($parameters['name'] != '')){
                $conditions = array('OR'=>array(
                    'User.username LIKE' => "%".$parameters['name']."%",
                    'RealEstate.company_name LIKE' => "%".$parameters['name']."%",));
            }
            if($parameters['banned'] == 1){
                $conditions['banned'] = 1;
            }
            if($parameters['disabled'] == 1){
                $conditions['enabled'] = 0;
            }
            $conditions['User.role'] = 'realestate';
            $results = $this->paginate('User', $conditions);
        }else{
            $conditions['User.role'] = 'realestate';
            $results = $this->paginate('User', $conditions);
        }
        $this->set('results', $results);
    }

    private function checkAccess() {
        if($this->Session->read('Auth.User.role') != 'admin'){
            $this->cakeError('error403');
        }
    }

}
?>
