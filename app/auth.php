<?php

class AuthHelper extends AppHelper{

	public $helpers = array( 'Html' );
	private $nm = null;

	function beforeRender( $viewFile ){
		$nm = $viewFile;
		pr( $viewFile ); die();
	}

	function doSomething(){

		pr( $nm ); die();
	}
}

?>
