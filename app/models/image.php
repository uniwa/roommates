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



function saveImage($fileData,$thumbSizeMax,$thumbSizeType,$thumbQuality) {
		App::import('Vendor','ccImageResize', array('file' => 'ccImageResize.class.php'));
		$fileData['name'] = $this->getLocationName($fileData['name']);
		$uploadedPath = WWW_ROOT . "img/original/" . $fileData['name'];
		if (move_uploaded_file($fileData['tmp_name'], $uploadedPath)) {
			$resizer = new ccImageResize;

			if ($resizer->resizeImage($uploadedPath, WWW_ROOT . "img/thumbnails/" . $fileData['name'], $thumbSizeMax,$thumbSizeType,$thumbQuality)) {
				// ok, everything is fine
			} else {
				// error with thumbnail resize
				return NULL;
			}

			if ($resizer->resizeImage($uploadedPath, WWW_ROOT . "img/medium/" . $fileData['name'], 600,$thumbSizeType,$thumbQuality)) {
				// ok, everything is fine
			} else {
				// error with medium resize
				return NULL;
			}

			if ($resizer->resizeImage($uploadedPath, WWW_ROOT . "img/large/" . $fileData['name'], 900,$thumbSizeType,$thumbQuality)) {
				// ok, everything is fine
			} else {
				// error with medium resize
				return NULL;
			}

		} else {
			// TODO: Set error message if move_uploaded_file fails
			//die("Could not move uploaded file ++" . $fileData['location'] . " ++ " . $fileData['name']);
			return NULL;
		}
		return $fileData['name'];
	}

	function delImage($filename)
	{
		// TODO: Code for condition where unlink fails
		// TODO: Retrieve different pictures from root model
		unlink(WWW_ROOT . "img/thumbnails/" . $filename);
		unlink(WWW_ROOT . "img/medium/" . $filename);
		unlink(WWW_ROOT . "img/large/" . $filename);
		unlink(WWW_ROOT . "img/original/" . $filename);
		return true;
	}

	private function getLocationName($fileName) {
		if(file_exists(WWW_ROOT . "img/original/" . $fileName))
		{
			$found = true;
			for($i = 1; $found == true; $i++)
			{
				$proposedName = $i . $fileName;
				$found = file_exists(WWW_ROOT . "img/original/" . $proposedName);
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