<?php

class AdminsController extends AppController
{
    var $name = 'Admins';
    var $uses = array();
    var $paginate = array(
        'fields' => array( 'User.username', 'User.banned',
        'Profile.id', 'Profile.firstname', 'Profile.lastname',
        'Profile.email'),
        'limit' => 50
    );

    function search(){
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'admin_search');
        $this->set('title_for_layout','Αναζήτηση χρηστών');

        $this->checkAccess();

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

    private function checkAccess() {

        if ($this->Session->read('Auth.User.role') != 'admin') {
            $this->cakeError('error403');
        }
    }

}
?>
