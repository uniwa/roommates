<?php
class ImagesController extends AppController {

    var $name = 'Images';
    var $uses = array("Image", "House");
    var $max_images = 4;

    function add( $id ) {
        $this->set('title_for_layout', 'Προσθήκη εικόνας σπιτιού');

        // this variable is used to display properly
        // the selected element on header
        $this->set('selected_action', 'houses_view');

        /* get max allowed upload size from php conf */
        $max_upload = (int)(ini_get('upload_max_filesize'));
        $max_post = (int)(ini_get('post_max_size'));
        $memory_limit = (int)(ini_get('memory_limit'));
        $upload_mb = min($max_upload, $max_post, $memory_limit);
        $this->set('max_size', $upload_mb);

        if ( ! $this->hasAccess($id) ) {
            $this->cakeError( 'error403' );
        }

        // get house data
        $this->House->id = $id;
        $house = $this->House->read();

        // check if maximum image limit reached
        if ( $house['House']['image_count'] >= $this->max_images ) {
            $this->Session->setFlash('Έχετε συμπληρώσει τον μέγιστο επιτρεπτό αριθμό φωτογραφιών.',
                'default', array('class' => 'flashRed'));
            $this->redirect(array('controller' => 'houses', 'action' => 'view', $id));
        }

        if(!empty($this->data)) {
            /* check if user pressed upload without image */
            if ( empty($this->data["Image"]["location"]["name"]) ) {
                $this->Session->setFlash('Παρακαλώ επιλέξτε εικόνα.',
                    'default', array('class' => 'flashRed'));
                $this->redirect(array('controller' => 'images', 'action' => 'add', $id));
            }
            /* check if image is uploaded */
            if ( ! is_uploaded_file($this->data["Image"]["location"]["tmp_name"])) {
                $this->Session->setFlash('Υπερβολικά μεγάλο μέγεθος εικόνας, η εικόνα δεν αποθηκεύτηκε.',
                    'default', array('class' => 'flashRed'));
                $this->redirect(array('controller' => 'houses', 'action' => 'view', $id));
            }

            /* check file type */
            if ( ! $this->validType($this->data["Image"]["location"]["tmp_name"]) ) {
                $this->Session->setFlash('Επιτρέπονται μόνο αρχεία PNG και JPG.',
                    'default', array('class' => 'flashRed'));
                $this->redirect(array('controller' => 'houses', 'action' => 'view', $id));
            }

            $this->Image->create();
            $newName = $this->Image->saveImage($id, $this->params['data']['Image']['location'],100,"ht",80);

            if ($newName == NULL) {
                $this->Session->setFlash('Σφάλμα αποθήκευσης εικόνας, επικοινωνήστε με τον διαχειριστή.',
                    'default', array('class' => 'flashRed'));
                $this->redirect(array('controller' => 'houses', 'action' => 'view', $id));
            }
            // store new image name
            $this->params['data']['Image']['location'] = $newName;

            // if 1st image set as default
            if ($house['House']['image_count'] == 0) $this->params['data']['Image']['is_default'] = 1;

            // save in db
            if ($this->Image->save($this->data)) {
                $this->Session->setFlash('Η εικόνα αποθηκεύτηκε με επιτυχία.',
                    'default', array('class' => 'flashBlue'));

                // *ATTENTION*
                // $this->referer() in this redirect will break on Nth image
                // upload due to max image count, redirect only on house view
                $this->redirect(array('controller' => 'houses', 'action' => 'view', $id));
            } else {
                $this->Session->setFlash('Η εικόνα ΔΕΝ αποθηκεύτηκε.',
                    'default', array('class' => 'flashRed'));
                $this->redirect(array('controller' => 'houses', 'action' => 'view', $id));
            }
        }
        $this->set('house_id' , $id);
    }

    function delete($id = null) {
        $this->Image->id = $id;
        $image = $this->Image->read();

        /* check if user owns house before removing images */
        $house_id = $image['Image']['house_id'];
        if (! $this->hasAccess($house_id)) {
            $this->cakeError( 'error403' );
        }

        if (!$id) {
            $this->Session->setFlash('Λαθος id',
                'default', array('class' => 'flashRed'));
        }
        else {
            /* set new default image */
            if ($image['Image']['is_default'] == 1) {
                $new_img_id = $this->get_next_image($house_id, $id);
                $this->set_default_image($new_img_id);
            }

            if ($this->Image->delete($id)) {
                $this->Image->delImage($house_id, $imageData['Image']['location']);
                $this->Session->setFlash('Η εικόνα διαγραφήκε με επιτυχία.',
                    'default', array('class' => 'flashBlue'));
            }
            else {
                $this->Session->setFlash('Η εικόνα δεν διαγραφηκε.',
                    'default', array('class' => 'flashRed'));
            }
        }
        $this->redirect(array('controller' => 'houses', 'action'=>'view', $house_id));
    }

    function set_default($id = NULL) {
        /* handle authorization - calls private function set_default_image()
         * to update database field is_default
         */
        $this->Image->id = $id;
        $imageData = $this->Image->read();

        // get current default image (is available)
        $conditions = array('is_default' => 1, 'house_id' => $imageData['Image']['house_id']);
        $current = $this->Image->find('first', array('conditions' => $conditions));
        if (! empty($current)) {
            $this->unset_default_image($current['Image']['id']);
        }

        /* check if user owns house before setting the default on */
        $house_id = $imageData['Image']['house_id'];
        if ( ! $this->hasAccess($house_id) ) {
            $this->cakeError( 'error403' );
        }

        if (!$id) {
            $this->Session->setFlash('Λαθος id', 'default', array('class' => 'flashRed'));
        }
        else {
            $ret = $this->set_default_image($id);
            if ($ret == False) {
                $this->Session->setFlash('Σφάλμα ανάθεσης προεπιλεγμένης εικόνας.',
                    'default', array('class' => 'flashRed'));
            }
            else {
                $this->Session->setFlash('Η νέα προεπιλεγμένη εικόνα ορίστικε με επιτυχία.',
                    'default', array('class' => 'flashBlue'));
            }
        }
        $this->redirect(array('controller' => 'houses', 'action' => 'view', $house_id));
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

    private function set_default_image($image_id) {
        $this->Image->id = $image_id;
        $ret = $this->Image->saveField('is_default', 1, false);
        if ($ret === false) return false;
        return true;
    }

    private function unset_default_image($image_id) {
        $this->Image->id = $image_id;
        $ret = $this->Image->saveField('is_default', 0, false);
        if ($ret === false) return false;
        return true;
    }

    private function get_next_image($house_id, $image_id) {
        /* get next available image associated with house_id
            we do not really care which one, return NULL if we don't find any
            Note: run this after deleting an image to avoid getting
            the same image
        */
        $conditions = array('Image.house_id' => $house_id,
                            'Image.id !=' => $image_id);
        $img = $this->Image->find('first', array('conditions' => $conditions));
        if (isset($img["Image"]["id"])) {
            return $img["Image"]["id"];
        }
        return NULL;
    }

    // ========================================================================
    // image retrieval API
    // ========================================================================
    private function get_default_image_for_house($id) {
        /* return default image name of house with givven id */
        $this->House->id = $id;
        $house = $this->House->read();
        $img_id = $house['House']['default_image_id'];

        if (empty($img_id)) {
            return NULL;
        }

        foreach ($house['Image'] as $img) {
            if ($img['id'] == $img_id) return $img['location'];
        }
    }

    private function get_all_images_for_house($id) {
        $conditions = array('house_id' => $id);
        $this->Image->recursive = -1;
        $images = $this->Image->find('all', array('conditions' => $conditions));
        return $images;
    }

    function h($id) {
        /* do not complain about missing view */
        $this->autoRender = false;

        /* process url parameters */
        if (! empty($this->params['url']['type'])) {
            /* get image type from url; thumb or medium */

            switch($this->params['url']['type']) {
            case 't':
                $type = 'thumb_';
                break;
            case 'm':
                $type = 'medium_';
                break;
            default:
                exit('malformed request');
            }
        }
        else {
            /* return thumbs by default */
            $type = 'thumb_';
        }

        if (! empty($this->params['url']['img'])) {
            $img_num = $this->params['url']['img'];

            if (($img_num < 1) || ($img_num > $this->max_images)) {
                exit ('malformed request');
            }
        }
        else {
            /* return default house image (1) by default */
            $img_num = 1;
        }

        /* get default house image */
        $default_image = $this->get_default_image_for_house($id);
        /* if default image in NULL then we don't have any images */
        if ($default_image == NULL) return ''; //TODO: return bad request header ?!

        /* return default image */
        if ($img_num == 1) {
            $path = 'img/uploads/houses/' . $id. '/' . $type . $default_image;
            $this->redirect(Router::url('/', true). $path);
        }

        /* we need image from 2 to max_image */
        $image_list = $this->get_all_images_for_house($id);

        /* check if requested image number is within result range */
        $count = count($image_list);
        if ($img_num > $count) return ''; //TODO: return bad request header?!

        $ordered_image_list = array();
        // push the default image as first element
        array_push($ordered_image_list, $default_image);
        foreach ($image_list as $image) {
            if (! in_array($image['Image']['location'], $ordered_image_list)) {
                array_push($ordered_image_list, $image['Image']['location']);
            }
        }
        // return image
        $path = 'img/uploads/houses/' . $id . '/' . $type . $ordered_image_list[$img_num - 1];
        $this->redirect(Router::url('/', true). $path);
    }

    // TODO
    /* function u($id) { } */
}
?>
