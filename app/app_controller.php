<?php
class  AppController extends Controller{

	var $components = array('Auth', 'Session');
	var $Helpers  = Array('Html', 'Form', 'Session');

	function beforeFilter(){

		$this->Auth->loginError = "Δώστε έγκυρο όνομα χρήστη και συνθηματικό.";
		$this->Auth->authError = "Δώστε έγκυρο όνομα χρήστη και συνθηματικό.";
	}
}
?>
