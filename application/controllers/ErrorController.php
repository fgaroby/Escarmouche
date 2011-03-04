<?php
class ErrorController extends Escarmouche_Controller_Abstract
{
	public function errorAction()
	{
		$errors = $this->_getParam( 'error_handler' );

		switch( $errors->type )
		{
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
				// 404 error -- controller or action not found
				$this->getResponse()->setHttpResponseCode( 404 );
				$this->view->setTitle( 'Page not found' );
			break;
			
			default:
				// application error
				$this->getResponse()->setHttpResponseCode( 500 );
				$this->view->setTitle( 'Application error' );
			break;
		}

		// Log exception, if logger available
		if( $log = Zend_Registry::get( 'log' ) )
		{
			$exception = $errors->exception;
			//$log->crit($this->view->message, $errors->exception );
			$log->debug( $exception->getMessage()
				. PHP_EOL
				. $exception->getTraceAsString()
				. PHP_EOL );
		}

		// conditionally display exceptions
		if( $this->getInvokeArg('displayExceptions') == true )
			$this->view->exception = $errors->exception;

		$this->view->request = $errors->request;
	}



	/**
	 * Action lors d'un refus des ACLs
	 */
	public function unauthorizedAction()
	{
		$this->_response->setHttpResponseCode( 403 );
		$this->view->setTitle( "Accès refusé" );
	}

}

