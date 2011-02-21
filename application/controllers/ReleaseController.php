<?php

/**
 * ReleaseController
 *
 * @author Francescu Garoby
 * @version 0.1
 */


class ReleaseController extends Overlord_Controller_Abstract
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
	}


	/**
	 * The default action
	 */
	public function indexAction()
	{
		$this->view->setTitrePage( 'Liste des releases de votre projet' );

		$this->view->entries = $this->_releaseTable->fetchAll();

		$this->render();
	}
}