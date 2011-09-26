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
				if ($this->Profile->save($this->data)){
					$this->Session->setFlash('Profile has been added');
					$this->redirect(array('action' => 'index'));
				}
			}
		}	

	
		function delete($id){
			if ($this->Profile->delete($id)){
				$this->Session->setFlash('Profile deleted');
				$this->redirect(array('action'=> 'index'));
			}
		}


		function edit($id = null){
			$this->Profile->id = $id;
			if (empty($this->data)){
				$this->data = $this->Profile->read();
			}else {
				if ($this->Profile->save($this->data)){
					$this->Session->setFlash('Profile updated.');
					$this->redirect(array('action'=> 'index'));
				}
			}
		}


}
?>
