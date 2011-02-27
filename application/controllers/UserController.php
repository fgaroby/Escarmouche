<?php

/**
 * ReleaseController
 *
 * @author Francescu Garoby
 * @version 0.1
 */
class Application_Controller_User extends Overlord_Controller_Abstract
{
		$this->view->setTitle( 'Users' );
	/**
	 * The default action - show the login form
	 */
	public function indexAction()
	{
		$this->view->setTitle( 'Login' );

		$this->render();
	}
}