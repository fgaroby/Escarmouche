<?php

class IndexController extends Zend_Controller_Action
{

	public function init()
	{
		$this->view->setTitrePage( 'Overlord :: Accueil' );
	}

	
	public function indexAction()
	{
		
	}
	
	
	public function infoAction()
	{
		if( $this->getInvokeArg( 'debug' ) == 1 )
		{
			$this->getResponse()->setHeader( 'Cache-control', 'no-cache' );
			$this->view->setTitrePage( 'Contenu de request et response' );
			$this->view->request = $this->getRequest();
			$this->view->response = $this->getResponse();
		}
	}
}