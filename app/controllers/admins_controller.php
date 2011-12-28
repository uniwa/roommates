<?php

class AdminsController extends AppController
{
    var $name = 'Admins';
    var $uses = array();
    var $paginate = array(
        'RealEstate' => array('fields' => array('User.id','User.username','User.banned',
            'User.enabled','RealEstate.id','RealEstate.firstname','RealEstate.type',
            'RealEstate.lastname','RealEstate.company_name','RealEstate.email'),
            'limit' => 50),
        'User' => array('fields' => array('User.id','User.username','User.banned',
            'Profile.id','Profile.firstname','Profile.lastname','Profile.email'),
            'limit' => 50)
    );

    function manage_users(){
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'manage_user');
        $this->set('title_for_layout','Διαχείριση χρηστών');

        $this->checkAccess();
$this->log('admin '.$this->Auth->User('id').' manage users', 'info');
        App::import('Model', 'User');
        $paginate = array('fields' => array('User.username', 'User.banned',
            'Profile.id', 'Profile.firstname', 'Profile.lastname',
            'Profile.email'),
            'limit' => 50);
        $user = new User();
        $this->set('limit', $this->paginate['User']['limit']);

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
$this->log('admin '.$this->Auth->User('id').' manage realestates', 'info');
        App::import('Model', 'User');
        $user = new User();
        $this->set('limit', $this->paginate['RealEstate']['limit']);

        // work around named parameters in url
        if (! empty($this->params['named'])) {
            if (isset($this->params['named']['name'])) {
                $this->params['url']['name'] = $this->params['named']['name'];
            }
            if (isset($this->params['named']['banned'])) {
                $this->params['url']['banned'] = $this->params['named']['banned'];
            }
            if (isset($this->params['named']['disabled'])) {
                $this->params['url']['disabled'] = $this->params['named']['disabled'];
            }
        }

        if(isset($this->params['url']['name']) || isset($this->params['url']['banned'])
            || isset($this->params['url']['disabled'])){
            $parameters = $this->params['url'];
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
            $results = $this->paginate('RealEstate', $conditions);
        }else{
            $conditions['User.role'] = 'realestate';
            $results = $this->paginate('RealEstate', $conditions);
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
