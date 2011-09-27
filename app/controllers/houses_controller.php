<?php
class HousesController extends AppController {

    var $name = 'Houses';
    var $components = array('RequestHandler');
    var $helpers = array('Text', 'Time');

    function index() {
        if ($this->RequestHandler->isRss()) {
            $houses = $this->House->find('all',
                        array('limit' => 20, 'order' => 'House.modified DESC'));
            return $this->set(compact('houses'));
        }

        $this->set('houses', $this->House->find('all'));
    }

    function view($id = null) {
        $this->House->id = $id;
        $this->set('house', $this->House->read());
    }

    function add() {
        if (!empty($this->data)) {
            if ($this->House->save($this->data)) {
                $this->Session->setFlash('Your house has been saved.');
                $this->redirect(array('action' => 'index'));
            }
        }
            
        $this->set('floors', $this->House->Floor->find('list', array('fields' => array('type'))));
        $this->set('houseTypes', $this->House->HouseType->find('list', array('fields' => array('type'))));
        $this->set('heatingTypes', $this->House->HeatingType->find('list', array('fields' => array('type'))));
    }

    function delete($id) {
        $this->House->delete($id);
        $this->Session->setFlash('The house with id: '.$id.' has been deleted.');
        $this->redirect(array('action'=>'index'));
    }

    function edit($id = null) {
        $this->House->id = $id;
        if (empty($this->data)) {
            $this->data = $this->House->read();
        }
        else {
            if ($this->House->save($this->data)) {
                $this->Session->setFlash('The house has been updated.');
                $this->redirect(array('action' => 'index'));
            }
        }

        $this->set('floors', $this->House->Floor->find('list', array('fields' => array('type'))));
        $this->set('houseTypes', $this->House->HouseType->find('list', array('fields' => array('type'))));
        $this->set('heatingTypes', $this->House->HeatingType->find('list', array('fields' => array('type'))));
    }
}
?>
