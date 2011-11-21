<?php

App::import('Sanitize');
class RealEstatesController extends AppController {

    var $name = 'RealEstates';
    var $helpers = array('Html');
    var $components = array('RequestHandler', 'Email');
    var $uses = array('RealEstate', 'Municipality');

    function index() {

    }

    function view($id = null){
        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'real_estates_view');
        $this->set('title_for_layout','Στοιχεία επικοινωνίας');

    	$this->checkExistence($id);
        $this->RealEstate->id = $id;
        $this->RealEstate->recursive = 2;
        $estate = $this->RealEstate->read();

        // hide banned users unless we are admin
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

    private function set_ban_status($id, $status) {
        // sets ban status for user with the given profile id
        $this->RealEstate->id = $id;
        $realEstate = $this->RealEstate->read();

        // exit if this profile belongs to another admin
        if ($realEstate["User"]["role"] == "admin") {
            $this->Session->setFlash('Ο διαχειριστής δεν μπορεί να κλειδώσει άλλο διαχειριστή.',
                'default', array('class' => 'flashRed'));
            $this->redirect(array("action" => "view", $id));
        }

        $user["User"] = $realEstate["User"];
        $user["User"]["banned"] = $status;

        $this->User->begin();
        $this->User->id = $realEstate["RealEstate"]["user_id"];
        if ($this->User->save($user, array('validate'=>'first'))) {
            $this->User->commit();
            return True;
        } else {
            $this->User->rollback();
            return False;
        }

    }

    function ban($id) {
        if ($this->Auth->user('role') != 'admin') {
            $this->cakeError('error403');
        }
        $success = $this->set_ban_status($id, 1);
        if ($success) {
            $this->Session->setFlash('Ο λογαριασμός χρήστη κλειδώθηκε με επιτυχία.',
                'default', array('class' => 'flashBlue'));
            $this->email_banned_user($id);
        } else {
            $this->Session->setFlash(
                'Παρουσιάστηκε σφάλμα κατά την αλλαγή στοιχείων του λογαριασμού του χρήστη.',
                'default', array('class' => 'flashRed'));
        }
        $this->redirect(array('action'=> "view", $id));
    }

    function unban($id) {
        if ($this->Auth->user('role') != 'admin') {
            $this->cakeError('error403');
        }
        $success = $this->set_ban_status($id, 0);
        if ($success) {
            $this->Session->setFlash('Ο λογαριασμός χρήστη ξεκλειδώθηκε με επιτυχία.',
            'default', array('class' => 'flashBlue'));
        } else {
            $this->Session->setFlash(
                'Παρουσιάστηκε σφάλμα κατά την αλλαγή στοιχείων του λογαριασμού του χρήστη.',
                'default', array('class' => 'flashRed'));
        }
        $this->redirect(array('action'=> 'view', $id));

    }

    private function set_disable_status($id, $status){
        // sets disable status for user with the given profile id
        $this->RealEstate->id = $id;
        $realEstate = $this->RealEstate->read();

        // exit if this profile belongs to another admin
        if($realEstate['User']['role'] == 'admin'){
            $this->Session->setFlash('Ο διαχειριστής δεν μπορεί να απενεργοποιήσει άλλο διαχειριστή.',
                'default', array('class' => 'flashRed'));
            $this->redirect(array('action' => 'view', $id));
        }

        $user['User'] = $realEstate['User'];
        $user['User']['enabled'] = !$status;

        $this->User->begin();
        $this->User->id = $realEstate['RealEstate']['user_id'];
        if($this->User->save($user, array('validate'=>'first'))){
            $this->User->commit();
            return True;
        }else{
            $this->User->rollback();
            return False;
        }

    }

    function disable($id){
        if($this->Auth->user('role') != 'admin'){
            $this->cakeError('error403');
        }
        $success = $this->set_disable_status($id, 1);
        if($success){
            $this->Session->setFlash('Ο λογαριασμός χρήστη απενεργοποιήθηκε με επιτυχία.',
                'default', array('class' => 'flashBlue'));
            $this->email_disabled_user($id);
        }else{
            $this->Session->setFlash(
                'Παρουσιάστηκε σφάλμα κατά την αλλαγή στοιχείων του λογαριασμού του χρήστη.',
                'default', array('class' => 'flashRed'));
        }
        $this->redirect(array('action'=> 'view', $id));
    }

    function enable($id){
        if($this->Auth->user('role') != 'admin'){
            $this->cakeError('error403');
        }
        $success = $this->set_disable_status($id, 0);
        if($success){
            $this->Session->setFlash('Ο λογαριασμός χρήστη ενεργοποιήθηκε με επιτυχία.',
            'default', array('class' => 'flashBlue'));
        }else{
            $this->Session->setFlash(
                'Παρουσιάστηκε σφάλμα κατά την αλλαγή στοιχείων του λογαριασμού του χρήστη.',
                'default', array('class' => 'flashRed'));
        }
        $this->redirect(array('action'=> 'view', $id));

    }

    private function email_banned_user($id){
        // TODO: make more abstract to use in other use cases
        $this->RealEstate->id = $id;
        $realEstate = $this->RealEstate->read();
        $this->Email->to = $realEstate["RealEstate"]["email"];
        $this->Email->subject = 'Κλείδωμα λογαριασμού της υπηρεσίας roommates ΤΕΙ Αθήνας';
        //$this->Email->replyTo = 'support@example.com';
        $this->Email->from = 'admin@roommates.edu.teiath.gr';
        $this->Email->template = 'banned';
        $this->Email->sendAs = 'both';
        $this->Email->send();
    }
    
    private function email_disabled_user($id){
        // TODO: make more abstract to use in other use cases
        $this->RealEstate->id = $id;
        $realEstate = $this->RealEstate->read();
        $this->Email->to = $realEstate['RealEstate']['email'];
        $this->Email->subject = 'Απενεργοποίηση λογαριασμού της υπηρεσίας roommates ΤΕΙ Αθήνας';
        //$this->Email->replyTo = 'support@example.com';
        $this->Email->from = 'admin@roommates.edu.teiath.gr';
        $this->Email->template = 'disabled';
        $this->Email->sendAs = 'both';
        $this->Email->send();
    }
}
?>
