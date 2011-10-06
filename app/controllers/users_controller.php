<?php
class UsersController extends AppController{

	var $name = "Users";

	function login(){
	
	}

	function logout(){

		//Provides a quick way to de-authenticate someone, 
		//and redirect them to where they need to go
		$this->redirect( $this->Auth->logout() );
	}
}
?>
