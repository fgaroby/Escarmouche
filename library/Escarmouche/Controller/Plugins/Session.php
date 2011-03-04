<?php
/**
 *  Escarmouche_Controller_Plugins_Session
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

/**
 * Plugin mémorisant la dernière page vue
 * 
 * @package escarmouche
 * @subpackage controller
 */
class Escarmouche_Controller_Plugins_Session extends Zend_Controller_Plugin_Abstract
{


	/**
	 * Session récupérée du registre
	 *
	 * @var Zend_Session_Namespace
	 */
	private $_session;


	/**
	 * En-têtes du navigateur
	 * 
	 * @var string
	 */
	private $_clientHeaders;


	/**
	 * Constructeur
	 */
	public function __construct()
	{
		$this->_session = Zend_Registry::get( 'session' );
		$this->_clientHeaders = $_SERVER['HTTP_USER_AGENT'];
		if( array_key_exists( 'HTTP_ACCEPT', $_SERVER ) )
		{
			$this->_clientHeaders .= $_SERVER['HTTP_ACCEPT'];
		}
		$this->_clientHeaders = md5( $this->_clientHeaders );
	}


	/**
	 * Hook à l'entrée dans la boucle de dispatching
	 * Vérifie si il n'y a pas eu tentative de vol de la session
	 * en comparant les en-têtes du navigateur
	 *
	 * @param $request Zend_Controller_Request_Abstract
	 */
	public function dispatchLoopStartup( Zend_Controller_Request_Abstract $request )
	{
		if( Zend_Auth::getInstance()->hasIdentity() )
		{
			if( $this->_session->clientBrowser != $this->_clientHeaders )
			{
				Zend_Session::destroy();
				$this->_response->setHttpResponseCode( 403 );
				$this->_response->clearBody();
				$this->_response->sendResponse();
				exit();
			}
		}
	}


	/**
	 * Hook à la sortie de la boucle de dispatching.
	 * 
	 * Mémorise l'Uri actuelle en vue de la réutiliser pour redirection
	 * sur la page précédente.
	 * 
	 * @return void
	 */
	public function dispatchLoopShutdown()
	{
		$session = Zend_Registry::get( 'session' );
		$requestUri = $this->getRequest()->getRequestUri();
		$session->requestUri = $requestUri;
	}
}