<?php
/**
 *  TaskController
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

class TaskController extends Escarmouche_Controller_Abstract
{
	protected $_taskMapper;
	
	
	public function init()
	{
		parent::init();
		$this->view->setTitle( 'Tasks' );
		$this->_taskMapper = new Application_Model_TaskMapper();
	}

	
	/**
	 * The default action - show the home page
	 */
	public function indexAction()
	{
		$this->view->tasks = $this->_taskMapper->fetchAll();
	}
	
	
	/**
	 * 
	 * Display the task elected by $params['id']
	 * If doesn't exist, redirect to <code>task/index</code>
	 */
	public function displayAction()
	{
		$params = $this->getRequest()->getParams();
		$task = $this->_taskMapper->find( $params['id' ] );
		if( null !== $task )
		{
			$this->view->task = $task;
			$this->view->setTitle( $task->getName() );	
		}
		else
		{
			$this->_redirect(	$this->view->url(	array(	'controller'	=> 'task',
															'action'		=> 'index' ),
													null,
													true ),
								array( 'prependBase' => false ) );
		}
	}
	
	
	// Édite et enregistre un nouveau produit
	public function editAction()
	{
		$params = $this->getRequest()->getParams();
		$isUpdate = isset( $params['id'] ) && !empty( $params['id'] );
		if( $isUpdate )
		{
			$params['id'] = (int ) $params['id'];
			
			$task = $this->_taskMapper->find( $params['id'] );
			if( $task === null )
			{
				$this->getRequest()->clearParams();
				$this->_redirect( $this->view->url( array( 'controller' => 'task', 'action' => 'edit', 'id' => null ) ), array( 'prependBase' => false, 'code' => 303 ) );
			}
		}
		else
		{
			$task = new Application_Model_Task( array( 'name' => 'default task' ) );
		}
		
		$form = new Escarmouche_Form_Task();
		$form->setAction( $this->view->link( 'task', 'edit', null, '', 'default', !$isUpdate ) )
			 ->setMethod( 'post' )
			 ->setDefaults( $task->toArray() );
			 
		// Création des informations et ajout/suppression
		if( $this->getRequest()->isPost() && $form->isValid( $_POST ) )
		{
			$values = $form->getValues();
			// $values['creator'] = Zend_Auth::getInstance()->getIdentity()->id;
			$task->setFromArray( array_intersect_key($values, $task->toArray() ) );

			// Sauvegarde des informations
			$this->_taskMapper->save( $task );
			
			$this->_helper->FlashMessenger( "Insertion de la tâche '{$task->getName()}' effectuée ! " );
			$this->_redirect( $this->view->url( array( 'controller' => 'task', 'action' => 'index' ) ), array( 'prependBase' => false ) );
		}
		
		$this->view->form = $form;
	}
	
	
	public function deleteAction()
	{
		
	}
	
	
	public function postDispatch()
	{
		$this->view->flashMessenger = $this->_helper->FlashMessenger->getMessages();
	}
}