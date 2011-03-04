<?php

/**
 * AbstractController
 * 
 * @author
 * @version 
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