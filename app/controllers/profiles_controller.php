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

    		function add(){
			if (!empty($this->data)) {
				if ($this->data['Profile']['sex'] == 0 ){

					$this->data['Profile']['sex'] = 'άνδρας';				
				}else{
				
					$this->data['Profile']['sex'] = 'γυναίκα';
				}

				if ($this->Profile->save($this->data)){
					$this->Session->setFlash('Το προφίλ προστέθηκε.');
					$this->redirect(array('action' => 'index'));
				}
			}
		}	

	
		function delete($id){
			if ($this->Profile->delete($id)){
				$this->Session->setFlash('Το προφίλ διεγράφη.');
				$this->redirect(array('action'=> 'index'));
			}
		}


		function edit($id = null){
			$this->Profile->id = $id;
			if (empty($this->data)){
				$this->data = $this->Profile->read();
			}else {
				if ($this->Profile->save($this->data)){
					$this->Session->setFlash('Το προφίλ ενημερώθηκε.');
					$this->redirect(array('action'=> 'index'));
				}
			}
		}


}
?>
