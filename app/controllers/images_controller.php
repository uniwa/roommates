<?php
class ImagesController extends AppController {

    var $name = 'Images';
    var $uses = array("Image", "House");
    var $max_images = 4;

    function index() {
        $this->Image->recursive = 0;
        $this->set('images', $this->paginate());
    }

    function add( $id ) {
        if ( ! $this->hasAccess($id) ) {
            $this->cakeError( 'error403' );
        }
        $image_count = $this->imageCount($id);
        if ( $image_count >= $this->max_images ) {
            $this->Session->setFlash('Έχετε συμπληρώσει τον μέγιστο επιτρεπτό αριθμό φωτογραφιών.');
            $this->redirect(array('controller' => 'houses', 'action' => 'view', $id));
        }

        if(!empty($this->data)) {
            /* check if user pressed upload without image */
            if ( empty($this->data["Image"]["location"]["name"]) ) {
                $this->Session->setFlash('Παρακαλώ επιλέξτε εικόνα.');
                $this->redirect(array('controller' => 'images', 'action' => 'add', $id));
            }
            /* check if image is uploaded */
            if ( ! is_uploaded_file($this->data["Image"]["location"]["tmp_name"])) {
                $this->Session->setFlash('Υπερβολικά μεγάλο μέγεθος εικόνας, η εικόνα δεν αποθηκεύτηκε.');
                $this->redirect(array('controller' => 'houses', 'action' => 'view', $id));
            }

            /* check file type */
            if ( ! $this->validType($this->data["Image"]["location"]["tmp_name"]) ) {
                $this->Session->setFlash('Επιτρέπονται μόνο αρχεία PNG και JPG.');
                $this->redirect(array('controller' => 'houses', 'action' => 'view', $id));
            }

            $this->Image->create();
            $newName = $this->Image->saveImage($id, $this->params['data']['Image']['location'],100,"ht",80);


            if ($newName == NULL) {
                $this->Session->setFlash('Σφάλμα αποθήκευσης εικόνας, επικοινωνήστε με τον διαχειριστή.');
                $this->redirect(array('controller' => 'houses', 'action' => 'view', $id));
            }

            $this->params['data']['Image']['location'] = $newName;
            if ($this->Image->save($this->data)) {
                /* set 1st image as default */
                if ($image_count == 0) {
                    $this->set_default_image($id, $this->Image->id); //TODO: check success/fail
                }
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
        else {
            if ($this->Image->delete($id)) {
                $this->Image->delImage($house_id, $imageData['Image']['location']);
                $this->Session->setFlash(__('Η εικόνα διαγραφήκε με επιτυχία.', true));
            }
            else {
                $this->Session->setFlash(__('Η εικόνα δεν διαγραφηκε.', true));
            }
        }
        $this->redirect(array('controller' => 'houses', 'action'=>'view', $house_id));
    }

    private function hasAccess($id) {
        /* check if user owns house with givven id */
        $this->House->id = $id;
        $house = $this->House->read();

        if ( $this->Auth->user('id') == $house["User"]["id"] ) {
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

    private function validType($file) {
        /* check if uploaded image is a valid filetype */
        $valid_types = array("png", "jpg", "jpeg");
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file);
        $mime_type = explode("/", $mime_type);

        if (in_array($mime_type[1], $valid_types)) {
            return True;
        }
        return False;
    }

    private function set_default_image($house_id, $image_id) {
        $this->House->id = $house_id;
        $house = $this->House->read();

        $new["House"] = $house["House"];
        $new["House"]["default_image_id"] = $image_id;

        $this->House->begin();
        if ($this->House->save($new)) {
            $this->House->commit();
            return True;
        }
        else {
            $this->House->rollback();
            return False;
        }
    }
}
?>
