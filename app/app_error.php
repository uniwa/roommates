<?php

/*
 *
 * Extend ErrorHandler and mimicking the error404 code for 403 error
 * see http://api.cakephp.org/view_source/error-handler/#l-74
 *
 */
class AppError extends ErrorHandler {

	/*
	 * params:
	 * <<message>> set your message if you want change the default
	 * <<title>> html title 
	 * <<name>> heading title
	 */
	function error403($params) {
		          
		( isset( $params['title']  ) )?$this->controller->set( 'title', $params['title'] ):$this->controller->set( 'title', 'Forbidden' );
		( isset( $params['name']  ) )?$this->controller->set( 'name', $params['name'] ):$this->controller->set( 'name', 'Forbidden' );
		$this->controller->set( 'url', $this->controller->here );
		(isset( $params['message'] ) )?$this->controller->set( 'message', $params['message'] ):$this->controller->set( 'message', NULL );
		$this->_outputMessage( 'error403');
	}
}

?>
