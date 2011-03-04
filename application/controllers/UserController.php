<?php

/**
 * UserController
 *
 * @author Francescu Garoby
 * @version 0.1
 */
class UserController extends Escarmouche_Controller_Abstract
{
	protected $_userMapper;
	
	
	public function init()
	{
		parent::init();
		$this->view->setTitle( 'Users' );
		$this->_userMapper = new Application_Model_UserMapper();
	}

	
	/**
	 * The default action - show the login form
	 */
	public function indexAction()
	{
		$this->view->setTitle( 'Login' );

		$this->render();
	}
	
	
	public function displayAction()
	{
		$params = $this->getRequest()->getParams();
		if( isset( $params['id'] ) )
		{
			$user = $this->_userMapper->find( $params['id' ] );
			$this->view->user = $user;
			$this->view->setTitle( $user->getName() );	
		}
		else
			$this->_redirect( $this->view->url( array( 'controller' => 'user', 'action' => 'index' ) ), array( 'prependBase' => false ) );
	}
}