<?php

class AdminController extends AppController
{
    var $name = 'Admin';
    var $uses = array();
    var $paginate = array(
        'fields' => array( 'User.username', 'User.banned',
        'Profile.id', 'Profile.firstname', 'Profile.lastname',
        'Profile.email'),
        'limit' => 5
    );

    function beforeFilter() {
        parent::beforeFilter();
        /* if we are not admin bail out */
        if ($this->Session->read('Auth.User.role') != 'admin') {
            $this->cakeError('error403');
        }
    }


    function search(){

        App::import( 'Model', 'User' );
        $user = new User();
        $this->set( 'limit', $this->paginate[ 'limit' ] );

        if( isset( $this->params['url']['name'] ) || isset( $this->params['url']['banned'] ) ){

            $parameters = $this->params['url'];

            if( $parameters['banned'] != 1 ){

                $conditions = array(

                    'OR'=>array(
                        'User.username LIKE' =>"%".$parameters['name']."%",
                        'Profile.lastname LIKE' => "%".$parameters['name']."%",
                        'Profile.firstname LIKE ' => "%".$parameters['name']."%")
                    );

            } else {

                   $conditions['banned'] = 1;
            }


            $results = $this->paginate( 'User', $conditions  );
            if( $results == null ) {

                $this->Session->setFlash( 'Δεν βρέθηκαν χρήστες' );
            }
            $this->set( 'results', $results );


        } else {

            $results = $this->paginate( 'User' );
            $this->set( 'results', $results );
        }


    }

}
?>
