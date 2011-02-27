<?php

/**
 * ReleaseController
 *
 * @author Francescu Garoby
 * @version 0.1
 */


class ReleaseController extends Zend_Controller_Action
{
	private $_releaseTable;

	public function init()
	{
		parent::init();

		$this->_releaseTable = new Application_Model_Db_Table_Release();

		// par d�faut un appel � render() annule le rendu automatique
		// restauration du rendu via le helper viewRenderer.
		// (cette action rend une vue)
		$this->_helper->viewRenderer->setNoRender( false );
		$this->view->setTitle( 'Releases' );
	}


	/**
	 * The default action
	 */
	public function indexAction()
	{
		$this->view->setTitle( 'Liste des releases de votre produit' );

		$this->view->releases = $this->_releaseMapper->fetchAll();

		$this->render();
	}
}