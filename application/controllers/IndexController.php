<?php
/**
 * 
 * Enter description here ...
 * @author windu.2b
 *
 */
class IndexController extends Escarmouche_Controller_Abstract
{

	public function init()
	{
		parent::init();
	}

	
	public function indexAction()
	{
		$this->view->setTitle( 'Accueil' );
	}
	
	
	public function displayAction()
	{
		$this->view->setTitle( 'Tableau de bord' );
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