<?php
/**
 *  Escarmouche_Controller_Abstract
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

abstract class Escarmouche_Controller_Abstract extends Zend_Controller_Action
{
	function init()
	{
		$this->view->setTitle( 'Default value' );
		/*
		 * We check if the user is authenticated (only the page aksed is not 'index/index' or 'auth/*')
		 */ 
		$params = $this->getRequest()->getParams();
		if( !Zend_Auth::getInstance()->hasIdentity()		// not authenticated
			&& ( $params['controller'] !== 'auth'			// not in the 'auth' controller
			&& ( $params['controller'] !== 'index'			// not in the 'index' controller
			|| ( $params['controller'] === 'index'			// in the 'index' controller...
				&& $params['action'] !== 'index' ) ) ) )	// ... but not in the 'index' action
		{
			$this->_redirect(	$this->view->url(	array(	'controller'	=> 'auth',
															'action'		=> 'login' ),
													null,
													true ),
								array( 'prependBase' => false ) );
		}
	}
}