<?php
/**
 *  SprintController
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

class SprintController extends Escarmouche_Controller_Abstract
{
	protected $_sprintMapper;
	
	
	public function init()
	{
		parent::init();
		$this->view->setTitle( 'Sprints' );
		$this->_sprintMapper = Application_Model_SprintMapper::getInstance();
	}
	
	
	public function indexAction()
	{
		$this->view->sprints = $this->_sprintMapper->fetchAll();
	}
	
	
	public function displayAction()
	{
		$params = $this->getRequest()->getParams();
		$sprint = $this->_sprintMapper->find( $params['id' ] );
		if( null !== $sprint )
		{
			$this->view->sprint = $sprint;
			$this->view->setTitle( $sprint->getName() );	
		}
		else
			$this->_redirect(	$this->view->url(	array(	'controller'	=> 'sprint',
															'action'		=> 'index' ),
													null,
													true ),
								array( 'prependBase' => false ) );
	}
	
	
	/**
	 * 
	 * Edit the sprint, or create one if $param['id'] is empty
	 */
	public function editAction()
	{
		$params = $this->getRequest()->getParams();
		$isUpdate = isset( $params['id'] ) && !empty( $params['id'] );
		if( $isUpdate )
		{
			$params['id'] = (int ) $params['id'];
			
			$sprint = $this->_sprintMapper->find( $params['id'] );
			if( $sprint === null )
			{
				$this->getRequest()->clearParams();
				$this->_redirect(	$this->view->url(	array(	'controller'	=> 'sprint',
																'action'		=> 'edit',
																'id'			=> null ) ),
									array( 'prependBase' => false ) );
			}
		}
		else
		{
			$sprint = new Application_Model_Sprint( array( 'name' => 'default sprint' ) );
		}
		$form = new Escarmouche_Form_Sprint();
		$form->setAction( $this->view->link( 'sprint', 'edit', null, '', 'default', !$isUpdate ) )
			 ->setMethod( 'post' )
			 ->setDefaults( $sprint->toArray() );

		/*
		 *  if release has ever started, we can't change the start date anymore.
		 */
		if( Application_Model_Status::isStarted( $sprint->getStatus() ) )
		{
			$form->startDate->setAttrib( 'class', 'readonly' );
			$form->startDate->setAttrib( 'readonly', 'readonly' );
		}
		
		// if release has ever finished, we can't change the end date anymore.
		if( Application_Model_Status::isFinished( $sprint->getStatus() ) )
		{
			$form->endDate->setAttrib( 'class', 'readonly' );
			$form->endDate->setAttrib( 'readonly', 'readonly' );
		}
		
		// We define the referrer URL
		if( isset( $_SERVER['HTTP_REFERER'] ) && !empty( $_SERVER['HTTP_REFERER'] ) )
			$form->referrer->setValue( $_SERVER['HTTP_REFERER'] );
		else
			$form->referrer->setValue( $this->view->url( array( 'controller' => 'sprint', 'action' => 'index' ) ) );
			 
		if( isset( $params['release'] ) )
			$form->release->setValue( $params['release'] );
			
		
		// Création des informations et ajout/suppression
		if( $this->getRequest()->isPost() && $form->isValid( $_POST ) )
		{
			$values = $form->getValues();
			// $values['creator'] = Zend_Auth::getInstance()->getIdentity()->id;
			$sprint->setFromArray( array_intersect_key( $values, $sprint->toArray() ) );

			// We save (INSERT or UPDATE) the data
			$this->_sprintMapper->save( $sprint );
			
			$this->_helper->FlashMessenger( "Insertion du sprint '{$sprint->getName()}' effectuée ! " );
			// redirect to the referrer page or to the default, if referrer is empty
			$this->_redirect(	$form->referrer->getValue(),
								array( 'prependBase' => false ) );
		}
		else
		{
			$this->_helper->FlashMessenger( "L'insertion du sprint '{$sprint->getName()}' a échoué ! " );
			$this->view->form = $form;
		}
	}
	
	
	public function deleteAction()
	{
		
	}
}