<?php

class IndexController extends Zend_Controller_Action
{

	public function init()
	{
		$this->view->setTitle( 'Accueil' );
	}

	
	public function indexAction()
	{
		
	}
	
	
	public function infoAction()
	{
		if( $this->getInvokeArg( 'debug' ) == 1 )
		{
			$this->getResponse()->setHeader( 'Cache-control', 'no-cache' );
			$this->view->setTitle( 'Contenu de request et response' );
			$this->view->request = $this->getRequest();
			$this->view->response = $this->getResponse();
		}
	}
}