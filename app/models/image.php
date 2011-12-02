<?php

class Image extends AppModel {

    var $name = 'Image';

    var $belongsTo = array('House' => array('counterCache' => true));

    private function has_permissions($id = NULL, $type = 'house') {
        /* check for write permissions
         *
         * check in house image upload paths
         * check in user avatar image upload paths (TODO)
         */
        if ($id == NULL) {
            return false;
        }

        // build paths
        if ($type == 'house') {
            $base = WWW_ROOT . "img/uploads/houses/";
            $id_path = WWW_ROOT . "img/uploads/houses/" . $id . "/";
        } else {
            $base = WWW_ROOT . "img/uploads/profiles/";
            $id_path = WWW_ROOT . "img/uploads/profiles/" . $id . "/";
        }

        if (! is_writable($base)) return false;
        if (! is_writable($id_path)) return false;
        return true;
    }

    function saveImage($id, $fileData,$thumbSizeMax,$thumbSizeType,$thumbQuality, $type='house') {
        App::import('Vendor','ccImageResize', array('file' => 'ccImageResize.class.php'));
        $fileData['name'] = $this->getLocationName($fileData['name']);

        /* base path to store this file */
        if ($type == 'house') {
            $base_path = WWW_ROOT . "img/uploads/houses/$id/";
        } else {
            $base_path = WWW_ROOT . "img/uploads/profiles/$id/";
        }

        /* create destination folder if it does not exist*/
        if(!is_dir($base_path)) mkdir($base_path, 0700, true);

        /* catch permission errors before calling move_uploaded_file() */
        if ($this->has_permissions($id, $type) != true) {
            return NULL;
        }

        /* get extension */
        $ext = substr(strrchr($fileData['name'], '.'), 1);

        /* generate random photo name */
        $new_name = sha1($fileData['name'] . time()) . "." . strtolower($ext);

        if ($type == 'house') {
            return $this->save_house_image($base_path, $fileData['tmp_name'], $new_name, $thumbSizeMax,
                                            $thumbSizeType, $thumbQuality);
        } else {
            return $this->save_profile_image($base_path, $fileData['tmp_name'], $new_name);
        }

//        /* generate photo paths */
//        $original = $base_path . "orig_" . $new_name;
//        $thumbnail = $base_path . "thumb_" . $new_name;
//        $medium = $base_path . "medium_" . $new_name;
//        if (move_uploaded_file($fileData['tmp_name'], $original)) {
//            $resizer = new ccImageResize;
//
//            if (! $resizer->resizeImage($original, $thumbnail, $thumbSizeMax,$thumbSizeType,$thumbQuality)) {
//                return NULL;
//            }
//
//            if (! $resizer->resizeImage($original, $medium, 600,$thumbSizeType,$thumbQuality)) {
//                return NULL;
//            }
//        } else {
//            return NULL;
//        }
//        return $new_name;
    }

    private function save_house_image($base_path, $upload_path, $new_name, $thumbSizeMax,
                                        $thumbSizeType, $thumbQuality) {
        /* generate photo paths */
        $original = $base_path . "orig_" . $new_name;
        $thumbnail = $base_path . "thumb_" . $new_name;
        $medium = $base_path . "medium_" . $new_name;
        if (move_uploaded_file($upload_path, $original)) {
            $resizer = new ccImageResize;

            if (! $resizer->resizeImage($original, $thumbnail, $thumbSizeMax,$thumbSizeType,$thumbQuality)) {
                return NULL;
            }

            if (! $resizer->resizeImage($original, $medium, 600,$thumbSizeType,$thumbQuality)) {
                return NULL;
            }
        } else {
            return NULL;
        }
        return $new_name;
    }

    private function save_profile_image($base_path, $upload_path, $new_name) {
        $avatar = $base_path . $new_name;

        if (move_uploaded_file($upload_path, $avatar)) {
            return $new_name;
        }
            return NULL;
    }


    function delImage($house_id, $filename)
    {
        $base_path = WWW_ROOT . "img/uploads/houses/$house_id/";
        $original = $base_path . "orig_" . $filename;
        $thumbnail = $base_path . "thumb_" . $filename;
        $medium = $base_path . "medium_" . $filename;

        if (! unlink($original)) return False;
        if (! unlink($thumbnail)) return False;
        if (! unlink($medium)) return False;
        return true;
    }

    function delete_all($house_id) {
        $base_path = WWW_ROOT . "img/uploads/houses/$house_id/";

        # exit gracefully if user did not upload photos
        if (! is_dir($base_path)) return True;

        # delete directory contents
        $handle = opendir($base_path);
        if ($handle === False) return False;
        while ( false !== ($file = readdir($handle)) ) {
            if (! is_dir($base_path . $file)) unlink($base_path . $file);
        }
        closedir($handle);

        # remove directory
        return (rmdir($base_path) ? True : False);
    }

    private function getLocationName($fileName) {
        if(file_exists(WWW_ROOT . "img/uploads/original/" . $fileName))
        {
            $found = true;
            for($i = 1; $found == true; $i++)
            {
                $proposedName = $i . $fileName;
                $found = file_exists(WWW_ROOT . "img/uploads/original/" . $proposedName);
            }
            return $proposedName;
        }
        else
        {
            return $fileName;
        }
    }
}
?>
