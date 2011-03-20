<?php
/**
 *  UserController
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

class UserController extends Escarmouche_Controller_Abstract
{
	protected $_userMapper;
	
	
	public function init()
	{
		parent::init();
		$this->view->setTitle( 'Users' );
		$this->_userMapper = Application_Model_UserMapper::getInstance();
	}

	
	/**
	 * The default action - show the login form
	 */
	public function indexAction()
	{
		$this->view->setTitle( 'Login' );

		$this->render();
	}
	
	
	public function homeAction()
	{
		$auth = Zend_Auth::getInstance();
		if( $auth->hasIdentity() )
		{
			$identity = $auth->getIdentity();
			$user = $this->_userMapper->find( $identity->id );
			$this->view->user = $user;
			$this->view->setTitle( 'Page perso : ' . $user->getName() );
		}
		else
			$this->_redirect(	$this->view->url(	array(	'controller'	=> 'user',
															'action'		=> 'index' ),
													null,
													true ),
								array( 'prependBase' => false ) );
	}
}