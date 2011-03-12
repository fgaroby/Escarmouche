<?php
/**
 *  Zend_View_Helper_SetTitle
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
 * SetTitle helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_SetTitle extends Zend_View_Helper_Abstract
{
	public function setTitle( $titre )
	{
		$config = Zend_Registry::get( 'config' )->application;
		$this->view->headTitle = $this->view->translate( $titre )
						. ' '
						. $config->separator
						. ' '
						. $config->name
						. ' v' . $config->version;
		$this->view->pageTitle = $this->view->translate( $titre );
	}
}
