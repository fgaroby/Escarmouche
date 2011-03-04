<?php
/**
 *  Zend_View_Helper_Link
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
 * Crée un lien de manière plus intuitive que l'aide "Url"
 * 
 * @package application
 * @subpackage viewhelpers
 */
class Zend_View_Helper_Link extends Zend_View_Helper_Url
{


	/**
	 * Crée un lien de manière plus intuitive que l'aide "Url"
	 * 
	 * @param string $controllerName
	 * @param string $actionName
	 * @param string $moduleName
	 * @param array $params
	 * @param string $name
	 * @param boolean $reset
	 * @return string
	 */
	public function link( $controllerName = null, $actionName = null, $moduleName = null, $params = '', $name = 'default', $reset = true )
	{
		if( $controllerName === null )
		{
			$controllerName = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'controller' );
		}
		if( $actionName === null )
		{
			$actionName = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'action' );
		}
		if( is_array( $params ) )
		{
			$params = '?' . http_build_query( $params );
		}
		
		return parent::url( array( 'controller' => $controllerName, 'action' => $actionName, 'module' => $moduleName ), $name, $reset ) . $params;
	}
}