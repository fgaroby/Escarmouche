<?php

/**
 * ReleaseController
 *
 * @author Francescu Garoby
 * @version 0.1
 */
class Application_Controller_User extends Overlord_Controller_Abstract
{
	/**
	 * The default action - show the login form
	 */
	public function indexAction()
	{
		$this->view->setTitrePage( 'Login' );

		$this->render();
	}
}