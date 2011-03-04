<?php
/**
 *  AuthController
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

class AuthController extends Escarmouche_Controller_Abstract
{
	public function init()
	{
		parent::init();
		$this->view->setTitle( 'Authentification' );
		$this->_commentMapper = new Application_Model_CommentMapper();
	}

	
	public function preDispatch()
	{
		if( !Zend_Auth::getInstance()->hasIdentity()
			&& 'logout' === $this->getRequest()->getActionName() )
		{
			$this->_redirect(	$this->view->url( 	array(	'controller'=> 'index',
															'action'	=> 'index' ),
															null,
															true ),
													array( 'prependBase' => false ) );
		}
	}
	
	
	public function indexAction()
	{
		return $this->_redirect( $this->view->url(	array(	'controller'	=> 'auth',
    														'action'		=> 'index' ),
															null,
															true ),
													array(	'prependBase'	=> false ) );
	}
	
	
	public function loginAction()
	{
		$auth = Zend_Auth::getInstance();
		// If we're already logged in, redirect
		if( $auth->hasIdentity() )
			$this->_redirect( $this->view->url( array( 'controller' => 'index', 'action' => 'display' ) ), array( 'prependBase' => false ) );
			
		$params = $this->getRequest()->getParams();
		
		$authForm = new Escarmouche_Form_Auth();
		$authForm->setAction( $this->view->link( 'auth', 'login', null, '', 'default' ) )
				 ->setMethod( 'post' );
			
		// if the form has been submitted
		if( $this->getRequest()->isPost() && $authForm->isValid( $_POST ) )
    	{
    		$adapter = Zend_Registry::get( 'authAdapter' );
    		// get the username and password from the form
    		$adapter->setIdentity( $authForm->getValue( 'username' ) );
    		$adapter->setCredential( md5( $authForm->getValue( 'password' ) ) );
    		$result = $adapter->authenticate();
    		
    		/*
    		 * If the authentication is valid,
    		 * whe store the identity
    		 */
    		if( $result->isValid() )
    		{
    			$auth->getStorage()->write( $adapter->getResultRowObject( null, 'password' ) );
    			
    			return $this->_redirect( $this->view->url(	array(	'controller'	=> 'index',
    																'action'		=> 'display' ),
																	null,
																	true ),
															array(	'prependBase'	=> false ) );
    		}
    		else
    		{
    			return $this->_redirect( $this->view->url(	array(	'controller'	=> 'auth',
    																'action'		=> 'login' ),
																	null,
																	true ),
															array(	'prependBase'	=> false ) );
    		}
    	}
    	
    	$this->view->form = $authForm;
	}
	
	
	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		// back to auth page
		$this->_redirect( $this->view->url( array( 'controller' => 'index', 'action' => 'index' ) ), array( 'prependBase' => false ) );
	}
}