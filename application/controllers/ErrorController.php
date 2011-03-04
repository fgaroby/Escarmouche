<?php
/**
 *  ErrorController
 *  
 *  LICENSE
 *  
 *  Copyright (C) 2011  windu.2b
 *  
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *  
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *  
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  
 *  @author windu.2b
 *  @license AGPL v3
 *  @since 0.1
 */

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

