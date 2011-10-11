<?php
class ImagesController extends AppController {

	var $name = 'Images';
	
	function index() {
		$this->Image->recursive = 0;
		$this->set('images', $this->paginate());
	}

	function add() {		
		if(!empty($this->data)) {
			$this->Image->create();
			
			// how to ensure unique file names?
			// TODO: Code to warn user about duplicate files
			$newName = $this->Image->saveImage($this->params['data']['Image']['location'],100,"ht",80);
			if(isset($newName))
			{
				$this->params['data']['Image']['location'] = $newName;
			}
			else
			{
				$this->params['data']['Image']['location'] = null;
				// TODO: Write code to graciously exit if Photo::saveImage fails for now just die()
				die("I am sorry to fail you my master but I could not save your photo");
			}
			
			if ($this->Image->save($this->data)) {
				$this->Session->setFlash(__('The image has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The image could not be saved. Please, try again.', true));
			}
		}
		//$categories = $this->Image->Categorie->find('list');
		//$tags = $this->Image->Tag->find('list');
$this->set('houses');
	}

	function delete($id = null) {
		$imageData = $this->Image->read();
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for image', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Image->delete($id)) {
			$this->Image->delImage($imageData['Image']['location']);
			$this->Session->setFlash(__('Image deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Image was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>