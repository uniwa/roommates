<?php
/*
 * This helper class extract data from authenticated user
 */
class AuthHelper extends AppHelper{

	private $user_id;
	private $profile_id;
	private $house_id;

	/*
	 * Extract Auth array from $this->data
	 */
	function beforeRender(){
	
	 	$this->user_id = $this->data['Auth']['User']['id'];	
		$this->profile_id = $this->data['Auth']['Profile']['id'];
		$this->house_id = $this->data['Auth']['House']['id'];

	}

	/*
 	 * Gets info about loged in user.
	 * for now only ids 
	 */
	function get( $choice ){

		list( $model, $property ) = explode(  "." , $choice );

		switch( $model ) {

		case 'User':
			switch( $property ){
			 case 'id':
			  return $this->user_id;
			  break;
			}
		 	break;	
		 case 'House':
			switch( $property ){
			 case 'id':
			  return $this->house_id;
			  break;
			}
			break;
     		 case 'Profile':
                         switch( $property ){
			  case 'id':
			   return $this->profile_id;
			   break;
			 }
			 break;

		}
	}

	/*
	 * Call after render to clear $this->data map
	 * which set in AppController's beforeRender
	 */
	function afterRender(){

		$this->data['Auth'] = array();
	}

}

?>
