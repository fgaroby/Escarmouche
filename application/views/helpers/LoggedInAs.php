<?php
class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract
{
	public function loggedInAs()
	{
		$auth = Zend_Auth::getInstance();
		if( $auth->hasIdentity() )
		{
			$identity = $auth->getIdentity();
			$logout = $this->view->url( array( 'controller' => 'auth', 'action' => 'logout' ) );
			$homePage = $this->view->url( array( 'controller' => 'user', 'action' => 'home' ) );
			return 'Bienvenue <a href="' . $homePage . '">' . $identity->name . '</a> <a href="' . $logout . '">Logout</a>';
		}
			
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		if( $controller === 'auth' && $action === 'index' )
			return '';

		$loginUrl = $this->view->url(array( 'controller'=>'auth', 'action'=>'login' ) );
		
		return '<a href="' . $loginUrl . '">Login</a>';
			
	}
}