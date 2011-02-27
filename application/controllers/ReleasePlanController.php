<?php

/**
 * ReleasePlanController
 *
 * @author
 * @version
 */

require_once 'Zend/Controller/Action.php';

class ReleasePlanController extends Zend_Controller_Action
{
	protected $_sprintMapper;
	
	
	public function init()
	{
		$this->view->setTitrePage( 'Release Plan' );
		$this->_sprintMapper = new Application_Model_SprintMapper();
	}

	
	/**
	 * The default action - show the home page
	 */
	public function indexAction()
	{
		$this->view->sprints = $this->_sprintMapper->fetchAll();
	}
	
	
	public function editAction()
	{
		
	}
	
	
	/**
	 * 
	 * Clôture la release. Vérifie que les sprints sont tous finis.
	 */
	public function closeAction()
	{
		
	}
}