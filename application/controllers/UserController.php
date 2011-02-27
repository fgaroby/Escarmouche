<?php

/**
 * UserController
 *
 * @author Francescu Garoby
 * @version 0.1
 */
class UserController extends Zend_Controller_Action
{
	protected $_userMapper;
	
	
	public function init()
	{
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
}