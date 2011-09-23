<?php
class HomesController extends AppController {

	var $name = 'Homes';


	function index() {
		$this->set('homes', $this->Home->find('all'));

    }

    function view($location = null) {
        $this->Home->location = $location;
        $this->set('home', $this->Home->read());
    }



       function add() {
            if (!empty($this->data)) {
                if ($this->Home->save($this->data)) {
                    $this->Session->setFlash('Your post has been saved.');
                    $this->redirect(array('action' => 'index'));
                }
            }
       }

function delete($id) {
	$this->Home->delete($id);
	$this->Session->setFlash('The home with id: '.$id.' has been deleted.');
	$this->redirect(array('action'=>'index'));
}





function edit($id = null) {
	$this->Home->id = $id;
	if (empty($this->data)) {
		$this->data = $this->Home->read();
	} else {
		if ($this->Home->save($this->data)) {
			$this->Session->setFlash('This home has been updated.');
			$this->redirect(array('action' => 'index'));
		}
	}
}



}


?>

