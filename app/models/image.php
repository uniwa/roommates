<?php

class Image extends AppModel {

    var $name = 'Image';

    var $belongsTo = array(
            'House' => array(
            'className' => 'House',
            'foreignKey' => 'house_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /* NOTE: trailing slash is needed to pass a dir in is_writable() !!! */
    var $upload_path = "{WWW_ROOT}img/uploads/houses/";

    function saveImage($house_id, $fileData,$thumbSizeMax,$thumbSizeType,$thumbQuality) {
		App::import('Vendor','ccImageResize', array('file' => 'ccImageResize.class.php'));
		$fileData['name'] = $this->getLocationName($fileData['name']);

        /* catch permission errors before calling move_uploaded_file() */
        if (! is_writable($this->upload_path)) return NULL;

        /* base path to store this file */
        $base_path = WWW_ROOT . "img/uploads/houses/$house_id/";

        /* create destination folder if it does not exist*/
        if(!is_dir($base_path)) mkdir($base_path, 0700, true);

        /* get extension */
        $ext = substr(strrchr($fileData['name'], '.'), 1);

        /* generate random photo name */
        $new_name = sha1($fileData['name'] . time()) . "." . strtolower($ext);

        /* generate photo paths */
		$original = $base_path . "orig_" . $new_name;
		$thumbnail = $base_path . "thumb_" . $new_name;
		$medium = $base_path . "medium_" . $new_name;
		if (move_uploaded_file($fileData['tmp_name'], $original)) {
			$resizer = new ccImageResize;

			if ($resizer->resizeImage($original, $thumbnail, $thumbSizeMax,$thumbSizeType,$thumbQuality)) {

				// ok, everything is fine
			} else {
				// error with thumbnail resize
				return NULL;
			}

			if ($resizer->resizeImage($original, $medium, 600,$thumbSizeType,$thumbQuality)) {
				// ok, everything is fine
			} else {
				// error with medium resize
				return NULL;
			}
            /*
			if ($resizer->resizeImage($uploadedPath, WWW_ROOT . "img/uploads/large/" . $fileData['name'], 900,$thumbSizeType,$thumbQuality)) {
				// ok, everything is fine
			} else {
				// error with medium resize
				return NULL;
			}
              */
		} else {
			// TODO: Set error message if move_uploaded_file fails
			//die("Could not move uploaded file ++" . $fileData['location'] . " ++ " . $fileData['name']);
			return NULL;
		}
		//return $fileData['name'];
        return $new_name;
	}

	function delImage($house_id, $filename)
	{
        $base_path = WWW_ROOT . "img/uploads/houses/$house_id/";
        $original = $base_path . "orig_" . $filename;
        $thumbnail = $base_path . "thumb_" . $filename;
        $medium = $base_path . "_medium" . $filename;

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
