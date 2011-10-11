<?php
/**
* ccGD2ImageResize.class.php
*
* Class to resize image using gd2
*
* based on http://cakeforge.org/projects/cheesecake/
*/
class ccImageResize {

	function ccImageResize()
	{
	
	}
	
	/**
	* resize_image()
	*
	* Create a file containing a resized image
	*
	* @param  $src_file the source file
	* @param  $dest_file the destination file
	* @param  $new_size the size of the square within which the new image must fit
	* @param  $resize_aspect the aspect of thumbnail to scale "ht" or "wd" or "sq"
	* @param  $quality the quality of the resultant thumbnail
	* @return 'true' in case of success
	*/
	function resizeImage($src_file, $dest_file, $new_size=100, $resize_aspect="sq", $quality="80")
	{
		$imginfo = getimagesize($src_file);
		
		if ($imginfo == null) {
		return false;
		}	  
		
		// GD2 can only handle JPG, PNG & GIF images
		if (!$imginfo[2] > 0 && $imginfo[2] <= 3 ) {
		  return false;
		}
		
		// height/width
		$srcWidth = $imginfo[0];
		$srcHeight = $imginfo[1];
		//$resize_aspect = "sq";
		if ($resize_aspect == 'ht') {
		  $ratio = $srcHeight / $new_size;
		} elseif ($resize_aspect == 'wd') {
		  $ratio = $srcWidth / $new_size;
		} elseif ($resize_aspect == 'sq') {
		  $ratio = min($srcWidth, $srcHeight) / $new_size;
		} else {
		  $ratio = max($srcWidth, $srcHeight) / $new_size;
		}
		
		/**
		* Initialize variables
		*/
		$clipX = 0;
		$clipY = 0;
		
		$ratio = max($ratio, 1.0);
		if ($resize_aspect == 'sq'){
		$destWidth = (int)(min($srcWidth,$srcHeight) / $ratio);
		$destHeight = (int)(min($srcWidth,$srcHeight) / $ratio);
		if($srcHeight > $srcWidth){
		$clipX = 0;
		$clipY = ((int)($srcHeight - $srcWidth)/2);
		$srcHeight = $srcWidth;
		}elseif($srcWidth > $srcHeight){
		$clipX = ((int)($srcWidth - $srcHeight)/2);
		$clipY = 0;
		$srcWidth = $srcHeight;
		}
		}else{
		$destWidth = (int)($srcWidth / $ratio);
		$destHeight = (int)($srcHeight / $ratio);
		}
		
		if (!function_exists('imagecreatefromjpeg')) {
		  echo 'PHP running on your server does not support the GD image library, check with your webhost if ImageMagick is installed';
		  exit;
		}
		if (!function_exists('imagecreatetruecolor')) {
		  echo 'PHP running on your server does not support GD version 2.x, please switch to GD version 1.x on the admin page';
		  exit;
		}
		
		if ($imginfo[2] == 1 )
		  $src_img = imagecreatefromgif($src_file);
		elseif ($imginfo[2] == 2)
		  $src_img = imagecreatefromjpeg($src_file);
		else
		  $src_img = imagecreatefrompng($src_file);
		if (!$src_img){
		  return false;
		}
		if ($imginfo[2] == 1 ) {
		$dst_img = imagecreate($destWidth, $destHeight);
		} else {
		$dst_img = imagecreatetruecolor($destWidth, $destHeight);
		}
		
		imagecopyresampled($dst_img, $src_img, 0, 0, $clipX, $clipY, (int)$destWidth, (int)$destHeight, $srcWidth, $srcHeight);
		imagejpeg($dst_img, $dest_file, $quality);
		imagedestroy($src_img);
		imagedestroy($dst_img);
		
		// We check that the image is valid
		$imginfo = getimagesize($dest_file);
		if ($imginfo == null) {
			@unlink($dest_file);
			return false;
		} else {
			return true;
		}
	}
}
?>