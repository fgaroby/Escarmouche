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
	protected $_storyMapper;
	
	
	public function init()
	{
		parent::init();
		$this->view->setTitle( 'Sprint Backlog' );
		$this->_storyMapper = Application_Model_StoryMapper::getInstance();
	}

	
	/**
	 * The default action - show the home page
	 */
	public function indexAction()
	{
		$this->view->stories = $this->_storyMapper->fetchAll();
	}
	
	
	/**
	 * 
	 * @todo gérer les cas où l'id ne correspond à aucun sprint
//	 * @todo gérer les colonnes à afficher (TODO, WIP, DONE, TEST, FAILED/PASSED, DONE)
	 */
	public function displayAction()
	{
		$sm = Application_Model_StoryMapper::getInstance();
		$stm = Application_Model_StoryMapper::getInstance();
		$params = $this->getRequest()->getParams();
		if( isset( $params['id'] ) )
			$id = $params['id'];
		/*
		 * @todo gérer la sélection de l'ID de la release par défaut
		 */
		else
			$id = Zend_Registry::get( 'session' )->defaultProduct->getCurrentRelease()->getId();
		
		$selectLastStatus = $sm->getDbTable()
							->select()
							->setIntegrityCheck( false )
							->from(	array( 'sta'	=> 'status' ),
									'id' )
							->join(	array( 'st'		=> 'story_status'	),
									'sta.id = st.status',
									null )
							->where( 'st.story = s.id' )
							->order( 'st.changed DESC' )
							->limit( 1 );
		$selectStories = $sm->getDbTable()
							->select()
							->setIntegrityCheck( false )
							->from(	array(	's'		=> 'story' ),
									array(	's.*',
											'status' => '(' . $selectLastStatus->__toString() . ')' ) )
							->join(	array( 'sp'		=> 'sprint' ),
						 			'sp.id = s.sprint',
									null )
							->joinLeft(	array( 'f'	=> 'feature' ),
						 				's.feature = f.id',
										null )
						 	->where( 'sp.id = ?', $id )
						 	->where( 'status >= ?', Application_Model_Status::TODO )
						 	->order( array(	'status DESC',
						 					's.id ASC' ) );
		//Zend_Debug::dump( $selectStories->__toString() );die();
		$this->view->stories = $this->_storyMapper->fetchAll(	$selectStories,
																'status DESC' );
	}
	
	
	public function editAction()
	{
		
	}
}