<?php
/**
 *  ReleasePlanController
 *  
 *  LICENSE
 *  
 *  Copyright (C) 2011  windu.2b
 *  
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *  
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *  
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  
 *  @author windu.2b
 *  @license AGPL v3
 *  @since 0.1
 */

require_once 'Zend/Controller/Action.php';

class SprintBacklogController extends Escarmouche_Controller_Abstract
{
	protected $_sprintMapper;
	
	
	public function init()
	{
		parent::init();
		$this->view->setTitle( 'Sprint Backlog' );
		$this->_sprintMapper = Application_Model_SprintMapper::getInstance();
	}

	
	/**
	 * The default action - show the home page
	 */
	public function indexAction()
	{
		$this->view->stories = $this->_sprintMapper->fetchAll();
	}
	
	
	/**
	 * 
	 * @todo gérer les cas où l'id ne correspond à aucun sprint
//	 * @todo pouvoir filtrer les colonnes à afficher (TODO, WIP, DONE, TEST, FAILED/PASSED, DONE)
	 */
	public function displayAction()
	{
		$params = $this->getRequest()->getParams();
		if( isset( $params['id'] ) )
				$id = $params['id'];
		/*
		 * @todo gérer la sélection de l'ID de la release par défaut
		 */
		else
			$id = Zend_Registry::get( 'session' )->defaultProduct->getCurrentRelease()->getId();
			
		$sprint = $this->_sprintMapper->find( $params['id' ] );
		if( null !== $sprint )
		{
			$this->view->sprint = $sprint;
			$this->view->setTitle( 'Sprint Backlog : ' . $sprint->getName() );
		}
		else
			$this->_redirect( $this->view->url( array(	'controller' => 'sprint-backlog',
														'action' => 'index' ),
														null,
														true ),
												array(	'prependBase'	=> false ) );
	}
	
	
	public function editAction()
	{
		
	}
}