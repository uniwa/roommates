<?php
class ImagesController extends AppController {

	var $name = 'Images';
    var $uses = array("Image", "House");
	
	function index() {
		$this->Image->recursive = 0;
		$this->set('images', $this->paginate());
	}

	function add( $id ) {
        if ( ! $this->hasAccess($id) ) {
            $this->cakeError( 'error403' );
        }
        if ( $this->imageCount($id) >= 5 ) {
            $this->Session->setFlash('Έχετε συμπληρώσει τον μέγιστο επιτρεπτό αριθμό φωτογραφιών');
            $this->redirect(array('controller' => 'houses', 'action' => 'view', $id));
        }
		if(!empty($this->data)) {
			$this->Image->create();
			
			// TODO: Code to warn user about duplicate files
			$newName = $this->Image->saveImage($id, $this->params['data']['Image']['location'],100,"ht",80);
            if (! $this->validType($newName)) {
                $this->Session->setFlash('Επιτρέπονται αρχεία εικόνας τύπου png ή jpeg');
                $this->redirect(array('controller' => 'houses', 'action' => 'view', $id));
            }
			if(isset($newName))
			{
				$this->params['data']['Image']['location'] = $newName;
			}
			else
			{
				$this->params['data']['Image']['location'] = null;
                /* TODO: add error message */
                $this->redirect(array('controller' => 'houses', 'action' => 'view', $id));
			}
			
			if ($this->Image->save($this->data)) {
				$this->Session->setFlash(__('Η εικόνα αποθηκεύτηκε με επιτυχία...', true));
                /* IMPORTANT: $this->referer() in this redirect will break on 5th image
                    upload due to max image count, redirect only on house view */
                $this->redirect(array('controller' => 'houses', 'action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('Η εικόνα ΔΕΝ αποθηκεύτηκε', true));
				$this->redirect(array('controller' => 'houses', 'action' => 'view', $id));
			}
		}
        $this->set('house_id' , $id);
	}

	function delete($id = null) {
        $this->Image->id = $id;
		$imageData = $this->Image->read();

        /* check if user owns house before removing images */
        $house_id = $imageData['Image']['house_id'];
        if (! $this->hasAccess($house_id)) {
            $this->cakeError( 'error403' );
        }

		if (!$id) {
			$this->Session->setFlash(__('Λαθος id', true));
		}
		if ($this->Image->delete($id)) {
			$this->Image->delImage($house_id, $imageData['Image']['location']);
			$this->Session->setFlash(__('Η εικόνα διαγραφήκε με επιτυχία.', true));
		}
        else {
		    $this->Session->setFlash(__('Η εικόνα δεν διαγραφηκε.', true));
        }
		$this->redirect(array('controller' => 'houses', 'action'=>'view', $house_id));
	}

    private function hasAccess($id) {
        /* check if user owns house with givven id */
        $this->House->id = $id;
        $house = $this->House->read();
        if ($this->Auth->user('id') == $house["House"]["user_id"]) {
            return True;
        }
        else {
            return False;
        }
    }

    private function imageCount($id) {
        /* return number of pictures associated with given house id */
        $this->House->id = $id;
        $house = $this->House->read();
        return count($house["Image"]);
    }

    private function validType($buffer) {
        /* check if uploaded image is a valid filetype */
        $valid_types = array("png", "jpg", "jpeg", "gif", "PNG", "JPG", "JPEG", "GIF");
        $type = pathinfo($buffer, PATHINFO_EXTENSION);

        if (in_array($type, $valid_types)) {
            return True;
        }
        return False;
    }
}
?>
